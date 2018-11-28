<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/20
 * Time: 10:22
 */
class AjaxController extends AdminController
{
    //获取子分类
    public function actionCategory()
    {
        $id = Yii::app()->request->getParam('id');
        if(!$id){
            echo 1;
            exit();
        }
        $data = CategoryModel::model()->findAll('pid=:pid',array(':pid'=>$id));
        if(!$data){
            echo 1;
            exit();
        }
        $data = CHtml::listData($data,'id','title');
        echo "<option value=''>选择分类</option>";
        foreach($data as $value=>$title)
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($title), true);
    }
    //搜索用户
    public function actionSearchUser()
    {
        $phone = Yii::app()->request->getParam('phone');
        if(!$phone){
            $criteria = false;
        }else{
            $criteria=new CDbCriteria;
            $criteria->addSearchCondition('phone',$phone);
            $criteria->addCondition('t.status=:status');
            $criteria->params[':status']=UserModel::STATUS_NORMAL;
        }
        $data = UserModel::model()->findAll($criteria);
        if(!$data){
            echo "<option value=''>没有结果</option>";
            exit();
        }
        $data = CHtml::listData($data,'id','phone');
        foreach($data as $value=>$phone)
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($phone), true);
    }


}

