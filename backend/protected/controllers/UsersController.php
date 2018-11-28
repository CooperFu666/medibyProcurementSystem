<?php

class UsersController extends LoginedController{
	
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
                $criteria->addSearchCondition('t.phone',$phone);
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
            $criteria->with = 'detail';
            $order=$_POST['order'];
            switch($order[0]['column']){
                case 4:
                    $criteria->order='t.regtime '.$order[0]['dir'];
                    break;
                case 5:
                    $criteria->order='t.lasttime '.$order[0]['dir'];
                    break;
            }

            $total = UserModel::model()->count($criteria);
            $dataProvider = new CActiveDataProvider('UserModel',array(
	            'criteria'=>$criteria,
	            'pagination'=>array(
	                'pageSize'=>$pageSize,
	                'currentPage'=>$page,
	            )) 
        	);
        	$datas = $dataProvider->getData();
        	$users = array();
            //print_r($datas);exit();
            foreach ($datas as $data) {
            	$btn = '<a href="' . $this->createUrl("users/update/id/{$data->id}") . '" class="btn btn-xs default btn-editable"><i class="fa fa-pencil">修改</i></a>';

                switch($data->status){
                    case UserModel::STATUS_NORMAL:
                        $status="正常";
                        $btn .= '<a rel="' . $this->createUrl("users/disable/id/{$data->id}") . '" class="btn btn-xs red default bootbox-disable"><i class="fa fa-times"></i>禁用</a>';
                        break;
                    case  UserModel::STATUS_DISABLE:
                        $status="禁用";
                        $btn .= '<a rel="' . $this->createUrl("users/enable/id/{$data->id}") . '" class="btn btn-xs green default bootbox-enable"><i class="fa fa-pencil"></i>启用</a>';
                        break;
                }

                switch($data->type){
                    case UserModel::TYPE_ONE:
                        $type="一类";
                        break;
                    case  UserModel::TYPE_TWO:
                        $type="二类";
                        break;
                    case  UserModel::TYPE_THREE:
                        $type="三类";
                        break;
                    default:
                        $type="未认证";
                }
                $btn .= '<a href="' . $this->createUrl("users/info/id/{$data->id}") . '" class="btn btn-xs default"><i class="fa fa-pencil"></i>详情</a>';
                $btn .= '<a href="' . $this->createUrl("coupon/users/id/{$data->id}") . '" class="btn btn-xs default"><i class="fa fa-pencil"></i>优惠券</a>';

                $users[] = array(
                    $data->phone,
                    $data->detail->corporate_name,
                    $status,
                    $type,
                    $data->regtime ? date('Y-m-d H:i:s',$data->regtime):'',
                    $data->lasttime ? date('Y-m-d H:i:s',$data->lasttime):'',
                	$btn
                );
            }
            die(json_encode(array('data' => $users, 'recordsTotal' => $total, 'recordsFiltered' => $total)));
        }else{
        	$this->render('index');
        }
    }
    //添加用户
    public function actionAdd(){
        $form = new UsersForm();
        $form->scenario = 'add';
        if(isset($_POST['UsersForm'])){
            $form->attributes=$_POST['UsersForm'];
            if($form->validate()){
                $form->save();
                $this->showSuccess("新增成功！",$this->createUrl("index"));
            }
        }
        $this->render('add',array('model'=>$form));
    }
    //修改用户
    public function actionUpdate($id){
        $model=UserModel::model()->with('detail')->findByPk($id);
        $form=new UsersForm();
        $form->attributes = $model->attributes;
        $form->corporate_name = $model->detail->corporate_name;
        $form->corporate_type = $model->detail->corporate_type;
        $form->id = $id;
        if(isset($_POST['UsersForm'])){
            $form->attributes  =$_POST['UsersForm'];
            $form->password=$_POST['UsersForm']['password'];
            if($form->validate()){
                $form->save();
                $this->showSuccess("修改成功！",$this->createUrl("index"));
            }
        }
        $this->render("update",array('model'=>$form));
    }
    //启用
    public function actionEnable($id){
        $model=$this->loadModel($id);
        $model->status = UserModel::STATUS_NORMAL;
        if($model->save()){
            $this->showJsonResult(1);
        }else{
            $this->showJsonResult(0);
        }
    }
    //禁用
    public function actionDisable($id){
        $model=$this->loadModel($id);
        $model->status = UserModel::STATUS_DISABLE;
        if($model->save()){
            $this->showJsonResult(1);
        }else{
            $this->showJsonResult(0);
        }
    }
    //用户详情
    public function actionInfo($id){
        $model=UserModel::model()->with(array('detail','address','invoice','auth'))->findByPk($id);
        $this->render("info",array('model'=>$model));
    }
    //用户预约联系
    public function actionContact() {
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
                $criteria->addSearchCondition('t.phone',$phone);
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
            $criteria->with = 'detail';
            $order=$_POST['order'];
            switch($order[0]['column']){
                case 4:
                    $criteria->order='t.regtime '.$order[0]['dir'];
                    break;
                case 5:
                    $criteria->order='t.lasttime '.$order[0]['dir'];
                    break;
            }

            $total = UserContactModel::model()->count($criteria);
            $dataProvider = new CActiveDataProvider('UserContactModel',array(
                    'criteria'=>$criteria,
                    'pagination'=>array(
                        'pageSize'=>$pageSize,
                        'currentPage'=>$page,
                    ))
            );
            $datas = $dataProvider->getData();
            $users = array();

            foreach ($datas as $data) {
                $btn = '';
                switch($data->status){
                    case UserContactModel::STATUS_UN:
                        $status="未联系";
                        $btn .= '<a rel="' . $this->createUrl("users/contactStatus/id/{$data->id}/status/".UserContactModel::STATUS_MISS) . '" class="btn btn-xs red default bootbox-miss"><i class="fa fa-times"></i>联系不上</a>';
                        $btn .= '<a rel="' . $this->createUrl("users/contactStatus/id/{$data->id}/status/".UserContactModel::STATUS_COM) . '" class="btn btn-xs green default bootbox-com"><i class="fa fa-pencil"></i>已联系</a>';

                        break;
                    case UserContactModel::STATUS_COM:
                        $status="已联系";
                        break;
                    case UserContactModel::STATUS_MISS:
                        $status="联系不上";
                        $btn .= '<a rel="' . $this->createUrl("users/contactStatus/id/{$data->id}/status/".UserContactModel::STATUS_COM) . '" class="btn btn-xs green default bootbox-com"><i class="fa fa-pencil"></i>已联系</a>';
                        break;
                }
                foreach (UserContactModel::$typeArray as $typeKey=>$typeObj){
                    if($typeKey==$data->type){
                        $type = $typeObj;break;
                    }
                }
                $users[] = array(
                    $data->phone,
                    $data->detail->corporate_name,
                    $data->telnumber,
                    $status,
                    $type,
                    $data->created_at ? date('Y-m-d H:i:s',$data->created_at):'',
                    $data->calltime ? date('Y-m-d H:i:s',$data->calltime):'',
                    $btn
                );
            }
            die(json_encode(array('data' => $users, 'recordsTotal' => $total, 'recordsFiltered' => $total)));
        }else{
            $this->render('contact');
        }
    }
    //用户联系状态修改
    public function actionContactStatus($id){
        $model=UserContactModel::model()->findByPk($id);
        $model->status = Yii::app()->request->getParam('status');
        if($model->save()){
            $this->showJsonResult(1);
        }else{
            $this->showJsonResult(0);
        }
    }
    //载入数据模型
    public function loadModel($id){
    	$model = UserModel::model()->findByPk($id);
    	if(!$model){
    		throw new CHttpException(404,'The requested page does not exist.');
    	}
    	return $model;
    }

   
    
    
    
}
