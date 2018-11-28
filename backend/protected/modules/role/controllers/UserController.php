<?php
/**
 * Created by JetBrains PhpStorm.
 * User: home
 * Date: 15-7-31
 * Time: 下午5:37
 * To change this template use File | Settings | File Templates.
 */

class UserController extends LoginedController
{
    public function actionIndex(){
        if($_POST){

            $product=array();
            $pageSize=Yii::app()->request->getParam('length',10);
            $start=Yii::app()->request->getParam('start');
            $username=Yii::app()->request->getParam('username');
            $phone=Yii::app()->request->getParam('phone');
            $name=Yii::app()->request->getParam('name');
            $login_ats=Yii::app()->request->getParam('login_at');
            $login_at=strtotime($login_ats);
            $page=$start / $pageSize;
            
            $criteria=new CDbCriteria;
            if($username){
                $criteria->addCondition('t.username=:username');
                $criteria->params[':username']=$username;
            }
            if($phone){
                $criteria->addSearchCondition('t.phone',$phone);
            }
            if($login_at){
                $criteria->addCondition('t.login_at=:login_at');
                $criteria->params[':login_at']=$login_at;
            }
            if($name){
                $criteria->addCondition('role.name=:name');
                $criteria->params[':name']=$name;
            }
            $criteria->addCondition('role_id>0');
            $criteria->with='role';
            $criteria->select='id,username,phone,is_system,login_at,login_ip';
            $order=$_POST['order'];
            switch($order[0]['column']){
                case 1:
                    $criteria->order='phone '.$order[0]['dir'];
                    break;
                case 3:
                    $criteria->order='login_at '.$order[0]['dir'];
                    break;
                case 4:
                    $criteria->order='login_ip '.$order[0]['dir'];
                    break;
            }
            $countCriteria = $criteria;
            $dataProvider=new CActiveDataProvider('AdminModel',array(
                'criteria'=>$criteria,
                'pagination'=>array(
                    'pageSize'=>$pageSize,
                    'currentPage'=>$page,
                ),
            ));
            $products=$dataProvider->getData();
            foreach ($products as $p) {
                $delete = !$p->is_system ? '<a rel="'.$this->createUrl("user/delete/id/{$p->id}").'"  class="btn btn-xs red default bootbox-confirm"><i class="fa fa-times"></i> 删除</a>':
                    '<a href="javascript:void(0)" onclick="bootbox.alert(\'不能删除\')" class="btn btn-xs default btn-editable"><i class="fa fa-times"></i> 删除</a>';
                $product[] = array(
                    $p->username,
                    $p->phone,
                    $p->is_system ? '是':'否',
                    $p->login_at ? date('Y-m-d H:i:s',$p->login_at):'',
                    $p->login_ip ?$p->login_ip :'',
                    isset($p->role->name)?$p->role->name:'',
                    $delete.
                    '<a href="'.$this->createUrl("user/update/id/{$p->id}") . '" class="btn btn-xs default btn-editable"><i class="fa fa-pencil">修改</i></a>',
                );
            }
            $recordsFiltered=$total = (int)AdminModel::model()->count($countCriteria);
            echo json_encode(array('data' => $product, 'recordsTotal' => $total, 'recordsFiltered' => $recordsFiltered, ));
        }else{
            $this->render('index');
        }
    }
    public function actionAdd()
    {
        $form=new AdminForm();
        $form->scenario = 'add';
        $role=RoleModel::model()->findAll();
        if(isset($_POST['AdminForm'])){
            $form->attributes=$_POST['AdminForm'];
            if($form->validate()){
                $form->save();
                $this->showSuccess("新增成功！",$this->createUrl("index"));
            }
        }
        $this->render('add',array('model'=>$form,'role'=>$role));
    }
    public function actionUpdate($id){
        $model=AdminModel::model()->findByPk($id);
        $form=new AdminForm();
        $form->attributes = $model->attributes;
        if(isset($_POST['AdminForm'])){
//        	$oldPassword = $model->password;
//            $form->password = isset($_POST['AdminForm']['password']) && !empty($_POST['AdminForm']['password'])?AdminLoginForm::hashPassword($_POST['AdminForm']['password']):$oldPassword;
            $form->attributes  =$_POST['AdminForm'];
            $form->password=$_POST['AdminForm']['password'];
            if($form->validate()){
                $form->save();
                $this->showSuccess("修改成功！",$this->createUrl("index"));
            }
        }
        $role = RoleModel::model()->findAll();
        $this->render("update",array('model'=>$form,'role'=>$role));
    }
    public function actionDelete($id)
    {
        $model=AdminModel::model()->deleteByPk($id);
        if($model){
            $this->showJsonResult(1);
        }else{
            $this->showJsonResult(0);
        }
    }
    public function loadModel($id)
    {
        $model=AdminModel::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }
}