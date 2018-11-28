<?php

class CouponController extends LoginedController{
	//优惠券列表
    public function actionIndex() {
        if($_POST){
            $pageSize = Yii::app()->request->getParam('length',10);
            $start = Yii::app()->request->getParam('start');
            $page = $start / $pageSize;
            $criteria = new CDbCriteria;
            $total = CouponModel::model()->count($criteria);
            $dataProvider = new CActiveDataProvider('CouponModel',array(
	            'criteria'=>$criteria,
	            'pagination'=>array(
	                'pageSize'=>$pageSize,
	                'currentPage'=>$page,
	            )) 
        	);
        	$datas = $dataProvider->getData();
        	$arts = array();

            foreach ($datas as $data) {
                switch($data->type){
                    case CouponModel::TYPE_DISC:
                        $type ='折扣卡';
                        $discount = $data->discount."折";
                        break;
                    case CouponModel::TYPE_PIC:
                        $type ='代金券';
                        $discount = $data->price."元";
                        break;
                }
            	$btn = '<a href="' . $this->createUrl("coupon/update/id/{$data->id}") . '" class="btn btn-xs default btn-editable"><i class="fa fa-pencil">修改</i></a>';
                if($data->is_limit)$btn .= '<a href="' . $this->createUrl("coupon/limits/id/{$data->id}") . '" class="btn btn-xs default btn-editable"><i class="fa fa-pencil">限制设置</i></a>';
                $btn .= '<a href="' . $this->createUrl("coupon/info/id/{$data->id}") . '" class="btn btn-xs default btn-editable"><i class="fa fa-pencil">发放列表</i></a>';
            	$btn .= '<a rel="' . $this->createUrl("coupon/delete/id/{$data->id}") . '" class="btn btn-xs red default bootbox-confirm"><i class="fa fa-times"></i>删除</a>';
                $arts[] = array(
                    $data->title,
                    $discount,
                    $type,
                    $data->is_limit ? '是':'否',
                    $data->mix_price,
                    $data->starttime ? date('Y-m-d',$data->starttime):'',
                    $data->endtime ? date('Y-m-d',$data->endtime):'',
                	$data->number,
                	$btn
                );
            }
            die(json_encode(array('data' => $arts, 'recordsTotal' => $total, 'recordsFiltered' => $total)));
        }else{
        	$this->render('index');
        }
     	
    }
    //添加优惠券
    public function actionAdd(){
    	$model = new CouponModel();
        if(isset($_POST['CouponModel'])){
        	$model->attributes = $_POST['CouponModel'] ;
        	$model->starttime = strtotime($_POST['CouponModel']['starttime']);
            $model->endtime =strtotime($_POST['CouponModel']['endtime']);
            if($model->type==CouponModel::TYPE_DISC && !$model->discount){
                $this->showError("折扣度无效");
            }
            if($model->type==CouponModel::TYPE_PIC && !$model->price){
                $this->showError("优惠金额无效");
            }
	        if($model->validate()){
	            $model->save();
	            $this->showSuccess('添加成功',$this->createUrl('coupon/index'));
	        }else{
                $this->showError("操作失败");
            }
       }else{
          $this->render('add', array('model'=>$model));
        }
    }
    //删除
    public function actionDelete($id){
        $model=$this->loadModel($id);
        $transaction=Yii::app()->db->beginTransaction();
        try{
            UserCouponModel::model()->deleteAll('couponid=:couponid',array(':couponid'=>$id));
            $model->delete();
            $transaction->commit();//提交事务会真正的执行数据库操作
            $this->showJsonResult(1);
        }catch (Exception $e) {
            $transaction->rollback();//如果操作失败, 数据回滚
            $this->showJsonResult(0);
        }
    }
    //修改
    public function actionUpdate($id){
    	$model = $this->loadModel($id);
        $model->starttime = date('Y-m-d',$model->starttime);
        $model->endtime = date('Y-m-d',$model->endtime);
    	if (isset($_POST['CouponModel'])) {
            $model->attributes = $_POST['CouponModel'] ;
            $model->starttime = strtotime($_POST['CouponModel']['starttime']);
            $model->endtime =strtotime($_POST['CouponModel']['endtime']);
            if($model->type==CouponModel::TYPE_DISC && !$model->discount){
                $this->showError("折扣度无效");
            }
            if($model->type==CouponModel::TYPE_PIC && !$model->price){
                $this->showError("优惠金额无效");
            }
            if($model->validate()){
                $model->save();
                $this->showSuccess('修改成功',$this->createUrl('coupon/index'));
            }else{
                $this->showError("操作失败");
            }
    	}
    	$this->render("update", array('model' => $model));
    }
    //限制设置
    public function actionLimits($id){
        $model = $this->loadModel($id);
        if (isset($_POST['CouponModel'])) {
            $model->brandid = $_POST['CouponModel']['brandid'] ;
            if(Yii::app()->request->getParam('c1')){
                $model->categoryid = $_POST['c1'];
            }else{
                $model->categoryid = 0;
            }
            if(Yii::app()->request->getParam('c2'))$model->categoryid = $_POST['c2'];
            if(Yii::app()->request->getParam('c3'))$model->categoryid = $_POST['c3'];
            if($model->validate()){
                $model->save();
                $this->showSuccess('设置成功',$this->createUrl('coupon/index'));
            }else{
                $this->showError("操作失败");
            }
        }
        $brandList = BrandModel::model()->findAll();
        $brandArray = array(0=>"---未选择---");
        foreach ($brandList as $brand){
            $brandArray[$brand->id] = $brand->title;
        }
        $categoryInfo =false;
        if($model->categoryid>0){
            $categoryInfo = CategoryModel::getCategoryInTree($model->categoryid);
        }
        $this->render("limits", array('model'=>$model,'brandList'=>$brandArray,'categoryInfo'=>$categoryInfo));
    }
    //发放列表
    public function actionInfo($id) {
        $model = $this->loadModel($id);
        if($_POST){
            $couponuser=array();
            $pageSize=Yii::app()->request->getParam('length',50);
            $start=Yii::app()->request->getParam('start');
            $phone=Yii::app()->request->getParam('phone');
            $code=Yii::app()->request->getParam('code');
            $status=Yii::app()->request->getParam('status');
            $page=$start / $pageSize;

            $criteria=new CDbCriteria;
            $criteria->addCondition('t.couponid=:couponid');
            $criteria->params[':couponid']=$id;
            if($phone){
                $criteria->addSearchCondition('user.phone',$phone);
            }
            if($code){
                $criteria->addSearchCondition('t.code',$code);
            }
            if($status){
                $criteria->addCondition('t.status=:status');
                $criteria->params[':status']=$status;
            }
            $criteria->with="user";
            $criteria->order='t.created_at DESC';
            $order=$_POST['order'];
            switch($order[0]['column']){
                case 2:
                    $criteria->order='t.status '.$order[0]['dir'];
                    break;
                case 3:
                    $criteria->order='t.updated_at '.$order[0]['dir'];
                    break;
                case 4:
                    $criteria->order='t.created_at '.$order[0]['dir'];
                    break;
            }
            $countCriteria = $criteria;
            $dataProvider=new CActiveDataProvider('UserCouponModel',array(
                'criteria'=>$criteria,
                'pagination'=>array(
                    'pageSize'=>$pageSize,
                    'currentPage'=>$page,
                ),
            ));
            $couponusers=$dataProvider->getData();

            foreach ($couponusers as $data) {
                $btn = '<a rel="' . $this->createUrl("coupon/objDelete/id/{$data->id}") . '" class="btn btn-xs red default bootbox-confirm"><i class="fa fa-times"></i>删除</a>';
                switch($data->status){
                    case UserCouponModel::STATUS_UNUSE:
                        $btn .= '<a rel="' . $this->createUrl("coupon/use/id/{$data->id}") . '" class="btn btn-xs green default bootbox-use"><i class="fa fa-recycle"></i>使用</a>';
                        $status ='未使用';
                        break;
                    case UserCouponModel::STATUS_USE:
                        $status ='已使用';
                        break;
                }
                $couponuser[] = array(
                    $data->user->phone,
                    $data->code,
                    $status,
                    $data->updated_at ? date('Y-m-d H:i:s',$data->updated_at):'',
                    $data->created_at ? date('Y-m-d H:i:s',$data->created_at):'',
                    $btn
                );
            }
            $recordsFiltered=$total = (int)UserCouponModel::model()->count($countCriteria);
            echo json_encode(array('data' => $couponuser, 'recordsTotal' => $total, 'recordsFiltered' => $recordsFiltered, ));
        }else{
            $this->render('info',array('model'=>$model));
        }
    }
    //发放优惠券
    public function actionGrant($id){
        $model = $this->loadModel($id);
        if(isset($_POST['user'])){
               $transaction=Yii::app()->db->beginTransaction();
                try{
                    $criteria=new CDbCriteria;
                    if($_POST['user'] == "all") {
                        $criteria->addCondition('t.status=:status');
                        $criteria->params[':status']=UserModel::STATUS_NORMAL;
                    }elseif(is_array($_POST['user'])){
                        $user = array_unique($_POST['user']);
                        $criteria->addInCondition('t.id', $user);
                    }
                    $userList = CHtml::listData(UserModel::model()->findAll($criteria), 'id', 'phone');
                    foreach ($userList as $key => $phone) {
                        UserCouponModel::grant($id,$key);
                    }
                    $transaction->commit();//提交事务会真正的执行数据库操作
                    $this->showJsonResult(1);
                }catch (Exception $e) {
                    $transaction->rollback();//如果操作失败, 数据回滚
                    $this->showJsonResult(0);
                }
        }
        $this->render('grant', array('model'=>$model));
    }
    //删除发放券
    public function actionObjDelete($id){
        $model=UserCouponModel::model()->findByPk($id);
        if($model->delete()){
            $this->showJsonResult(1);
        }else{
            $this->showJsonResult(0);
        }
    }
    //使用
    public function actionUse($id){
        $model=UserCouponModel::model()->findByPk($id);
        $model->updated_at = time();
        $model->status = UserCouponModel::STATUS_USE;
        if($model->save()){
            $this->showJsonResult(1);
        }else{
            $this->showJsonResult(0);
        }
    }
    //用户优惠券列表
    public function actionUsers($id) {
        $model = UserModel::model()->findByPk($id);
        if($_POST){
            $couponuser=array();
            $pageSize=Yii::app()->request->getParam('length',50);
            $start=Yii::app()->request->getParam('start');
            $phone=Yii::app()->request->getParam('phone');
            $code=Yii::app()->request->getParam('code');
            $status=Yii::app()->request->getParam('status');
            $page=$start / $pageSize;

            $criteria=new CDbCriteria;
            $criteria->addCondition('t.userid=:userid');
            $criteria->params[':userid']=$id;
            if($phone){
                $criteria->addSearchCondition('user.phone',$phone);
            }
            if($code){
                $criteria->addSearchCondition('t.code',$code);
            }
            if($status){
                $criteria->addCondition('t.status=:status');
                $criteria->params[':status']=$status;
            }
            $criteria->with="coupon";
            $criteria->order='t.created_at DESC';
            $order=$_POST['order'];
            switch($order[0]['column']){
                case 4:
                    $criteria->order='t.status '.$order[0]['dir'];
                    break;
                case 5:
                    $criteria->order='t.updated_at '.$order[0]['dir'];
                    break;
                case 6:
                    $criteria->order='t.created_at '.$order[0]['dir'];
                    break;
            }
            $countCriteria = $criteria;
            $dataProvider=new CActiveDataProvider('UserCouponModel',array(
                'criteria'=>$criteria,
                'pagination'=>array(
                    'pageSize'=>$pageSize,
                    'currentPage'=>$page,
                ),
            ));
            $couponusers=$dataProvider->getData();

            foreach ($couponusers as $data) {
                $btn = '<a rel="' . $this->createUrl("coupon/objDelete/id/{$data->id}") . '" class="btn btn-xs red default bootbox-confirm"><i class="fa fa-times"></i>删除</a>';
                switch($data->status){
                    case UserCouponModel::STATUS_UNUSE:
                        $btn .= '<a rel="' . $this->createUrl("coupon/use/id/{$data->id}") . '" class="btn btn-xs green default bootbox-use"><i class="fa fa-recycle"></i>使用</a>';
                        $status ='未使用';
                        break;
                    case UserCouponModel::STATUS_USE:
                        $status ='已使用';
                        break;
                }
                switch($data->coupon->type){
                    case CouponModel::TYPE_DISC:
                        $type ='折扣卡';
                        $discount = $data->coupon->discount."折";
                        break;
                    case CouponModel::TYPE_PIC:
                        $type ='代金券';
                        $discount = $data->coupon->price."元";
                        break;
                }
                $couponuser[] = array(
                    $data->coupon->title,
                    $data->code,
                    $type,
                    $discount,
                    $status,
                    $data->updated_at ? date('Y-m-d H:i:s',$data->updated_at):'',
                    $data->created_at ? date('Y-m-d H:i:s',$data->created_at):'',
                    $btn
                );
            }
            $recordsFiltered=$total = (int)UserCouponModel::model()->count($countCriteria);
            echo json_encode(array('data' => $couponuser, 'recordsTotal' => $total, 'recordsFiltered' => $recordsFiltered, ));
        }else{
            $this->render('users',array('model'=>$model));
        }
    }


    public function loadModel($id){
    	$model = CouponModel::model()->findByPk($id);
    	if(!$model){
    		throw new CHttpException(404,'The requested page does not exist.');
    	}
    	return $model;
    }

   
    
    
    
}
