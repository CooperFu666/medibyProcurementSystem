<?php

class AuthController extends LoginedController{
	
    public function actionIndex() {
        if($_POST){
            $pageSize = Yii::app()->request->getParam('length',10);
            $start = Yii::app()->request->getParam('start');
            $phone=Yii::app()->request->getParam('phone');
            $corporate_name=Yii::app()->request->getParam('corporate_name');
            $status=Yii::app()->request->getParam('status');
            $type=Yii::app()->request->getParam('type');
            $page = $start / $pageSize;
            $criteria = new CDbCriteria;
            if($phone){
                $criteria->addSearchCondition('user.phone',$phone);
            }
            if($corporate_name){
                $criteria->addSearchCondition('detail.corporate_name',$corporate_name);
            }
            if($status){
                $criteria->addCondition('t.status=:status');
                $criteria->params[':status']=$status;
            }
            if($type){
                $criteria->addCondition('t.type=:type');
                $criteria->params[':type']=$type;
            }
            $criteria->order = 't.id DESC ' ;//排序条件
            $criteria->with = array('detail','user');
            $order=$_POST['order'];
            switch($order[0]['column']){
                case 4:
                    $criteria->order='t.updated_at '.$order[0]['dir'];
                    break;
                case 5:
                    $criteria->order='t.created_at '.$order[0]['dir'];
                    break;
            }

            $total = UserAuthModel::model()->count($criteria);
            $dataProvider = new CActiveDataProvider('UserAuthModel',array(
	            'criteria'=>$criteria,
	            'pagination'=>array(
	                'pageSize'=>$pageSize,
	                'currentPage'=>$page,
	            )) 
        	);
        	$datas = $dataProvider->getData();
        	$auth = array();

            foreach ($datas as $data) {
            	$btn = '<a href="' . $this->createUrl("auth/usersInfo/id/{$data->id}") . '" class="btn btn-xs default btn-editable"><i class="fa fa-pencil">详情</i></a>';

                switch($data->status){
                    case UserAuthModel::STATUS_ING:
                        $status="认证中";
                        break;
                    case UserAuthModel::STATUS_FAIL:
                        $status="失败";
                        break;
                    case UserAuthModel::STATUS_SUCCESS:
                        $status="成功";
                        break;
                }

                switch($data->type){
                    case UserAuthModel::TYPE_BUS:
                        $type="营业执照";
                        break;
                    case  UserAuthModel::TYPE_LIC:
                        $type="医疗器械许可证";
                        break;
                }
                $auth[] = array(
                    $data->user->phone,
                    $data->detail->corporate_name,
                    $status,
                    $type,
                    $data->updated_at ? date('Y-m-d H:i:s',$data->updated_at):'',
                    $data->created_at ? date('Y-m-d H:i:s',$data->created_at):'',
                	$btn
                );
            }
            die(json_encode(array('data' => $auth, 'recordsTotal' => $total, 'recordsFiltered' => $total)));
        }else{
        	$this->render('index');
        }
    }
    //认证信息详情
    public function actionUsersInfo($id){
        $model=UserAuthModel::model()->with(array('detail','user'))->findByPk($id);
        $this->render("usersInfo",array('model'=>$model));
    }
    //用户认证通过
    public function actionUsersPass($id){
        $model=UserAuthModel::model()->findByPk($id);
        if(isset($_POST['type'])){
            $transaction=Yii::app()->db->beginTransaction();
            try{
                $model->status= UserAuthModel::STATUS_SUCCESS;
                $model->updated_at = time();
                $model->save();
                $user = UserModel::model()->findByPk($model->userid);
                $user->type = $_POST['type']==""?UserModel::TYPE_ONE:$_POST['type'];
                $user->save();
                NotifyModel::sendNotify($model->userid,NotifyModel::AUTH_SUCCESS,'');
                $transaction->commit();//提交事务会真正的执行数据库操作
                $this->showSuccess("认证成功！",$this->createUrl("index"));
            }catch (Exception $e) {
                $transaction->rollback();//如果操作失败, 数据回滚
                $this->showJsonResult(0);
                $this->showError("操作失败！");
            }
        }
    }
    //用户认证拒绝
    public function actionUsersRefuse($id){
        $model=UserAuthModel::model()->with('user')->findByPk($id);
        $transaction=Yii::app()->db->beginTransaction();
        try{
            $model->status= UserAuthModel::STATUS_FAIL;
            $model->updated_at = time();
            $model->save();
            $user = UserModel::model()->findByPk($model->userid);
            $user->type = "0";
            $user->save();
            NotifyModel::sendNotify($model->userid,NotifyModel::AUTH_FAIL,'');
            $transaction->commit();//提交事务会真正的执行数据库操作
            $this->showSuccess("已拒绝！",$this->createUrl("index"));
        }catch (Exception $e) {
            $transaction->rollback();//如果操作失败, 数据回滚
            $this->showJsonResult(0);
            $this->showError("操作失败！");
        }
    }
    //待认证发票信息
    public function actionInvoiceAuth() {
        if($_POST){
            $pageSize = Yii::app()->request->getParam('length',10);
            $start = Yii::app()->request->getParam('start');
            $phone=Yii::app()->request->getParam('phone');
            $corporate_name=Yii::app()->request->getParam('corporate_name');
            $invoice_corporate_name=Yii::app()->request->getParam('invoice_corporate_name');

            $page = $start / $pageSize;
            $criteria = new CDbCriteria;
            if($phone){
                $criteria->addSearchCondition('user.phone',$phone);
            }
            if($corporate_name){
                $criteria->addSearchCondition('detail.corporate_name',$corporate_name);
            }
            if($invoice_corporate_name){
                $criteria->addSearchCondition('t.corporate_name',$invoice_corporate_name);
            }
            $criteria->addCondition('t.auth_status=:auth_status');
            $criteria->params[':auth_status']=UserInvoiceModel::AUTH_STATUS_NONE;
            $criteria->order = 't.id DESC ' ;//排序条件
            $criteria->with = array('detail','user');

            $total = UserInvoiceModel::model()->count($criteria);
            $dataProvider = new CActiveDataProvider('UserInvoiceModel',array(
                    'criteria'=>$criteria,
                    'pagination'=>array(
                        'pageSize'=>$pageSize,
                        'currentPage'=>$page,
                    ))
            );
            $datas = $dataProvider->getData();
            $invoice = array();

            foreach ($datas as $data) {
                $btn = '<a href="' . $this->createUrl("auth/invoice/id/{$data->id}") . '" class="btn btn-xs default btn-editable"><i class="fa fa-pencil">详情</i></a>';

                $invoice[] = array(
                    $data->user->phone,
                    isset($data->detail->corporate_name)?$data->detail->corporate_name:"",
                    $data->corporate_name,
                    $btn
                );
            }
            die(json_encode(array('data' => $invoice, 'recordsTotal' => $total, 'recordsFiltered' => $total)));
        }else{
            $this->render('invoiceAuth');
        }
    }
    //发票审核详情
    public function actionInvoice($id){
        $model=UserInvoiceModel::model()->with(array('detail','user'))->findByPk($id);
        $this->render("invoice",array('model'=>$model));
    }
    //发票审核拒绝
    public function actionInvoiceRefuse($id){
        $model=UserInvoiceModel::model()->findByPk($id);
        $model->auth_status = UserInvoiceModel::AUTH_STATUS_FAIL;
        if($model->save()){
            NotifyModel::sendNotify($model->userid,NotifyModel::INVOICE_FAIL,'');
            $this->showSuccess("已拒绝！",$this->createUrl("invoiceAuth"));
        }else{
            $this->showError("操作失败！");
        }
    }
    //发票审核通过
    public function actionInvoicePass($id){
        $model=UserInvoiceModel::model()->findByPk($id);
        $model->auth_status = UserInvoiceModel::AUTH_STATUS_SUCCESS;
        if($model->save()){
            NotifyModel::sendNotify($model->userid,NotifyModel::INVOICE_SUCCESS,'');
            $this->showSuccess("已通过！",$this->createUrl("invoiceAuth"));
        }else{
            $this->showError("操作失败！");
        }
    }
}
