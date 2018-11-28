<?php

class OrderController extends LoginedController
{
    //商品管理
    public function actionIndex()
    {
        $category=Yii::app()->request->getParam('category');
        if($_POST){
            $product = [];
            $search = new OrderSerach();
            $countCriteria = $search->getCriteria();
            $products = $search->getProvider($countCriteria);
            foreach ($products as $p) {
                $product[] = array(
                    $p->deal_number,
                    $p->user->phone,
                    $p->place_time ? date("Y-m-d H:i:s", $p->place_time) : "",
                    $p->pay_time ? date("Y-m-d H:i:s", $p->pay_time) : "",
                    OrderModel::getStatus($p->status),
                    OrderModel::getStatusButton($p->status, $p->id, $p->user->nickname, $p->user->id),
                );
            }
            $recordsFiltered=$total = (int)OrderModel::model()->count($countCriteria);
            echo json_encode(array('data' => $product, 'recordsTotal' => $total, 'recordsFiltered' => $recordsFiltered, ));
        }else{
            $this->render('index',array("category"=>$category));
        }
    }

    public function actionCheck() {
        $order_id = $_GET['order_id'];
        $user_id = $_GET['user_id'];
        if (!$_POST) {
            $user_auth_list = UserAuthModel::getAuthInfo($user_id);
            $user_info = UserModel::model()->findByPk($user_id);
            $goods_info_list = OrderGoodsRelationModel::getGoodsInfoList($order_id);
            $order_info = OrderModel::model()->getOrderInfo($order_id);
            $order_express_list = OrderExpressModel::model()->findAll('order_id=:order_id', [':order_id' => $order_id]);
            $invoice = json_decode($order_info['invoice'], true);
            $this->render('check' ,['user_info' => $user_info, 'order_express_list' => $order_express_list, 'user_auth_list' => $user_auth_list, 'goods_info_list' => $goods_info_list, 'order_info' => $order_info, 'invoice' => $invoice]);
        }elseif(!empty($_POST['orderId'] && !empty($_POST['check']))) {
            $order = OrderModel::model()->findByPk($_POST['orderId']);
            $tr = OrderModel::model()->getDbConnection()->beginTransaction();
            try{
                if ($_POST['check'] !== "false") {
                    OrderModel::changeStatus($_POST['orderId'], OrderModel::STATUS_WATE_PAY);
                    if ($_POST['alter_price'] !== "") {
                        OrderModel::changePrice($_POST['orderId'], $_POST['alter_price']);
                    }
                    NotifyModel::sendNotify($order->user_id,NotifyModel::ORDER_SUCCESS,$order->deal_number);
                }else{
                    if ($order->is_coupon) {
                        UserCouponModel::model()->updateByPk($order->user_coupon_id, ['status' => UserCouponModel::STATUS_UNUSE], 'userid=:userid', [':userid' => $user_id]);
                    }
                    NotifyModel::sendNotify($order->user_id,NotifyModel::ORDER_FAIL,$order->deal_number);
                    OrderModel::changeStatus($_POST['orderId'], OrderModel::STATUS_NOT_PASS);
                    $orderGoodsRelationList = OrderGoodsRelationModel::model()->findAll('order_id=:order_id', [':order_id' => $_POST['orderId']]);
                    foreach ($orderGoodsRelationList as $value) {
                        GoodsBindModel::stockHandle($value->goods_bind_id, $value->count, 'add');
                    }
                }
                $tr->commit();
            }catch (Exception $e){
                $tr->rollback();
            }
            $this->redirect('index');
        }
    }

    public function actionShip() {
        if (!$_POST) {
            $order_id = $_GET['order_id'];
            $user_id = $_GET['user_id'];
            $goods_info_list = OrderGoodsRelationModel::getGoodsInfoList($order_id);
            $order_info = OrderModel::model()->getOrderInfo($order_id);
            $invoice = json_decode($order_info['invoice'], true);
            $order_express_list = OrderExpressModel::model()->findAll('order_id=:order_id AND user_id=:user_id', [':order_id' => $order_id, ':user_id' => $user_id]);
            $express_list = OrderExpressModel::getExpressList();
            $this->render('ship',['express_list' => $express_list, 'goods_info_list' => $goods_info_list, 'order_info' => $order_info, 'invoice' => $invoice, 'order_express_list' => $order_express_list]);
        }else {
            $tr = OrderExpressModel::model()->getDbConnection()->beginTransaction();
            try {
                $orderExpressModel = new OrderExpressModel();
                $attr = ['express_number' => Yii::app()->request->getParam('express_number_goods')];
                $orderExpressModel->updateAll($attr, "order_id=:order_id AND type=" . OrderExpressModel::TYPE_GOODS, [':order_id' => Yii::app()->request->getParam('orderId')]);
                $orderExpressModel = new OrderExpressModel();
                $attr = ['express_number' => Yii::app()->request->getParam('express_number_invoice')];
                $orderExpressModel->updateAll($attr, "order_id=:order_id AND type=" . OrderExpressModel::TYPE_INVOICE, [':order_id' => Yii::app()->request->getParam('orderId')]);
                OrderModel::changeStatus($_POST['orderId'], OrderModel::STATUS_WATE_REC);
                $tr->commit();
                $this->redirect('index');
            }catch (Exception $e) {
                $tr->rollback();
            }
        }
    }

    public function actionDetail() {
        $order_id = $_GET['order_id'];
        $user_id = $_GET['user_id'];
        $user_info = UserModel::model()->findByPk($user_id);
        $user_auth_list = UserAuthModel::getAuthInfo($user_id);
        $order_express_list = OrderExpressModel::model()->findAll('order_id=:order_id', [':order_id' => $order_id]);
        $goods_info_list = OrderGoodsRelationModel::getGoodsInfoList($order_id);
        $order_info = OrderModel::model()->getOrderInfo($order_id);
        $invoice = json_decode($order_info['invoice'], true);
        $this->render('detail' ,['user_info' => $user_info, 'order_express_list' => $order_express_list, 'user_auth_list' => $user_auth_list, 'goods_info_list' => $goods_info_list, 'order_info' => $order_info, 'invoice' => $invoice]);
    }

    public function actionConfirm() {
        $order_id = $_GET['order_id'];
        $tr = OrderModel::model()->getDbConnection()->beginTransaction();
        try{
            OrderModel::changeStatus($order_id, OrderModel::STATUS_WATE_SHIP);
            $res = OrderGoodsRelationModel::model()->findAll('order_id=:order_id', [':order_id' => $order_id]);
            foreach ($res as $v) {
                GoodsCensusModel::change($v['goods_id'],'sales',1);
            }
            $tr->commit();
        }catch (Exception $e) {
            $tr->rollback();
        }

        $this->redirect('index');
    }

    public function loadModel($id)
    {
        $model=GoodsModel::model()->findByPk($id);
        if($model===null){
            throw new CHttpException(404,'The requested page does not exist.');
        }
        return $model;
    }
}