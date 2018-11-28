<?php
/**
 * Created by JetBrains PhpStorm.
 * User: home
 * Date: 15-8-1
 * Time: 下午4:26
 * To change this template use File | Settings | File Templates.
 */
class ModuleController extends LoginedController
{
    public function actionIndex(){
        if($_POST){

            $product=array();
            $pageSize=Yii::app()->request->getParam('length',10);
            $start=Yii::app()->request->getParam('start');
            $page=$start / $pageSize;
            $criteria=new CDbCriteria;
            $criteria->select='*';
            $criteria->order="t.id desc";
            $countCriteria = $criteria;
            $dataProvider=new CActiveDataProvider('RoleModuleModel',array(
                'criteria'=>$criteria,
                'pagination'=>array(
                    'pageSize'=>$pageSize,
                    'currentPage'=>$page,
                ),
            ));
            $products=$dataProvider->getData();
            foreach ($products as $p) {
                $product[] = array(
                    $p->name,
                    $p->module,
                    $p->is_effect?"是":"否",
                     '<a href="'.$this->createUrl("module/actions/id/{$p->id}") .'" class="btn btn-xs green default">模块方法</a>'.
                   '<a rel="' . $this->createUrl("module/delete/id/{$p->id}") . '" class="btn btn-xs red default bootbox-confirm"><i class="fa fa-times"></i> 删除</a>'.
                    '<a href="'.$this->createUrl("module/update/id/{$p->id}") . '" class="btn btn-xs default btn-editable"><i class="fa fa-pencil">修改</i></a>'
                );
            }
            $recordsFiltered=$total = (int)RoleModuleModel::model()->count($countCriteria);
            echo json_encode(array('data' => $product, 'recordsTotal' => $total, 'recordsFiltered' => $recordsFiltered));
        }else{
            $this->render('index');
          }
    }

    public function actionAdd()
    {
        $model=new RoleModuleModel();
        if(isset($_POST['RoleModuleModel'])){
            $model->attributes=$_POST['RoleModuleModel'];
            if($model->save()){
                $this->redirect(array("module/index"));
            }
        }
        $this->render('add',array('model'=>$model));
    }

    public function actionUpdate($id){
        $model=RoleModuleModel::model()->findByPk($id);
        if(isset($_POST['RoleModuleModel'])){
            $model->attributes=$_POST['RoleModuleModel'];
            if($model->save()){
                $this->showSuccess('修改成功！',$this->createUrl('index'));
            }
        }
        $this->render("update",array('model'=>$model));
    }

    public function actionDelete($id)
    {
        if($this->loadModel($id)->delete()){
            $this->showJsonResult(1);
           }else{
            $this->showJsonResult(0);
        }

    }

    public function loadModel($id)
    {
        $model=RoleModuleModel::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    public function actionActions($id){
        if($_POST){
        $product=array();
        $pageSize=Yii::app()->request->getParam('length',10);
        $start=Yii::app()->request->getParam('start');
        $page=$start / $pageSize;
        $criteria=new CDbCriteria;
        $criteria->select='name,action,id';
        $criteria->addcondition('module_id=:module_id');
        $criteria->params=array(':module_id'=>$id);
        $countCriteria = $criteria;
        $dataProvider=new CActiveDataProvider('RoleActionModel',array(
                'criteria'=>$criteria,
                'pagination'=>array(
                    'pageSize'=>$pageSize,
                    'currentPage'=>$page,
                ),
            ));
        $products=$dataProvider->getData();
        foreach ($products as $p) {
          $product[] = array(
                $p->action,
                $p->name,
                 '<a rel="'.$this->createUrl("module/deletes/id/{$p->id}").'" class="btn btn-xs red default bootbox-confirm"><i class="fa fa-times"></i> 删除</a>'.
                 '<a href="'.$this->createUrl("module/updates/id/{$p->id}").'" class="btn btn-xs default btn-editable"><i class="fa fa-pencil">修改</i></a>'
            );
        }
            $recordsFiltered=$total = (int)RoleActionModel::model()->count($countCriteria);
            echo json_encode(array('data' => $product, 'recordsTotal' => $total, 'recordsFiltered' => $recordsFiltered));
        }else{
            $this->render('actions',array('id'=>$id));
        }
    }

    public function actionAddWay($id){
        $model=new RoleActionModel();
        $models=new RoleNavGroupModel();
        $group=RoleNavGroupModel::model()->findAll();
        if(isset($_POST['RoleActionModel'])){
            $model->attributes=$_POST['RoleActionModel'];
            $model->module_id = $id;
            $model->group_id = $_POST['RoleActionModel']['group_id'];
            if($model->save()){
                $this->showSuccess('添加成功',$this->createUrl('module/actions/id/'.$id));
            }
        }
        $this->render('addway',array('model'=>$model,'group'=>$group,'models'=>$models));
    }

    public function actionDeletes($id){
       $id = $this->filterIntval($id);
       $model=RoleActionModel::model()->deleteByPk($id);
         RoleAccessModel::model()->delete('action_id='.$id);
        if($model)
        {
            $this->showJsonResult(1);
        }else{
            $this->showJsonResult(0);
        }
    }

    public function actionUpdates($id){
        $group=RoleNavGroupModel::model()->findAll();
        $model=RoleActionModel::model()->findByPk($id);
        if(isset($_POST['RoleActionModel'])){
            $model->attributes=$_POST['RoleActionModel'];
            $model->group_id=$model->group_id?$_POST['group_id']:0;
            if($model->save()){
                $this->showSuccess('修改成功',$this->createUrl('module/actions/id/'.$model->module_id));
            }
        }
        $this->render('updates',array('model'=>$model,'group'=>$group));
    }
}