<?php
/**
 * Created by JetBrains PhpStorm.
 * User: home
 * Date: 15-10-26
 * Time: 上午11:10
 * To change this template use File | Settings | File Templates.
 */

class FriendLinkController extends LoginedController
{
    public function actionIndex(){
        if($_POST){
            $start=Yii::app()->request->getParam('start');
            $pageSize=Yii::app()->request->getParam('length',10);
            $page=$start / $pageSize;
            $criteria=new CDbCriteria();
            $criteria->order="t.sort asc";
            //$criteria->addCondition('is_effect=1');
            $data=new CActiveDataProvider('FriendlinkModel',array(
                'criteria'=>$criteria,
                'pagination'=>array(
                    'pageSize'=>$pageSize,
                    'currentPage'=>$page,
                )
            ));
            $datas=$data->getData();
            $link=array();
            foreach($datas as $da){
                $del='<a rel="'.$this->createUrl('FriendLink/delete',array('id'=>$da->id)).'" class="btn btn-xs red bootbox-confirm"><i class="fa fa-times"></i>删除</a>';
                $up='<a href="'.$this->createUrl('FriendLink/update',array('id'=>$da->id)).'" class="btn btn-xs green btn-editable"><i class="fa fa-pencil"></i>修改</a>';
                $space="&nbsp;&nbsp;&nbsp;&nbsp;";
                $link[]=array(
                    isset($da->title)?$da->title:'',
                    isset($da->url)?$da->url:'',
                    isset($da->sort)?$da->sort:'',
                    isset($da->is_effect)&& $da->is_effect ? "是":"否",
                    $up.$space.$del
                );
            }
            $total=FriendlinkModel::model()->count($criteria);
            die(json_encode(array('data' => $link, 'recordsTotal' => $total, 'recordsFiltered' => $total)));
        }
        $this->render('index');
    }
    public function actionAdd(){
        $model=new FriendlinkModel();
        if(isset($_POST['FriendlinkModel'])){
            $model->attributes=$_POST['FriendlinkModel'];
            if($model->save()){
                $this->showSuccess("添加数据成功！",$this->createUrl('FriendLink/index'));
            }
        }

        $this->render('add',array('model'=>$model));
    }
    public function actionUpdate($id){
        $model=FriendlinkModel::model()->findByPk($id);
        if(isset($_POST['FriendlinkModel'])){
            $model->attributes=$_POST['FriendlinkModel'];
            if($model->save()){
                $this->showSuccess("修改成功！",$this->createUrl('FriendLink/index'));
            }
        }
        $this->render('update',array('model'=>$model));
    }
    public function actionDelete($id){
        $model=FriendlinkModel::model()->deleteByPk($id);
        if($model){
            $this->showJsonResult(1);
        }else{
            $this->showJsonResult(0);
        }
    }
}