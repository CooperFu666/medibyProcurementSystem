<?php

class RoleController extends LoginedController
{
    public function actionIndex()
    {
        if ($_POST) {
            $role = array();
            $pageSize = Yii::app()->request->getParam('length', 15);
            $start = (int)Yii::app()->request->getParam('start');
            $page = $start / $pageSize;
            $criteria = new CDbCriteria();
            $criteria->order = 't.id DESC';
            $dataProvider = new CActiveDataProvider('RoleModel', array(
                'criteria' => $criteria,
                //'pagination' => false,
                'pagination' => array(
                    'pageSize' => $pageSize,
                    'currentPage' => $page
                ),
            ));
            $roles = $dataProvider->getData();
            foreach ($roles as $r) {
                $delete = !$r->is_system ? '<a rel="' . $this->createUrl("role/delete/id/{$r->id}") . '"  class="btn btn-xs red default bootbox-confirm"><i class="fa fa-times"></i> 删除</a>' :
                    '<a href="javascript:void(0)" onclick="bootbox.alert(\'不能删除\')" class="btn btn-xs default btn-editable"><i class="fa fa-times"></i> 删除</a>';
                $role[] = array(
                    '<input type="checkbox" name="id[]" value="' . $r->id . '">',
                    $r->name,
                    $r->is_system ? '是' : '否',
                    '<a href="' . $this->createUrl("role/update/id/{$r->id}") . '" class="btn btn-xs default btn-editable"><i class="fa fa-pencil"></i> 编辑</a>' . $delete

                );
            }
            $recordsFiltered = $total = (int)RoleModel::model()->count();
            $draw = (int)Yii::app()->request->getParam('draw');
            echo json_encode(array('data' => $role, 'recordsTotal' => $total, 'recordsFiltered' => $recordsFiltered, 'draw' => $draw));
        } else {
            $this->render('index');
        }
    }

    public function actionCreate()
    {
        $model = new RoleModel;
        if (isset($_POST['RoleModel'])) {
            $model->attributes = $_POST['RoleModel'];
            $actions = $_POST['role_access'];
            if ($model->save()) {
                foreach ($actions as $a) {
                    list($module, $action) = explode('_', $a);
                    $accessModel = new RoleAccessModel();
                    $accessModel->action_id = $action;
                    $accessModel->module_id = $module;
                    $accessModel->role_id = $model->id;
                    $accessModel->save();
                }
                $this->showSuccess('添加成功', $this->createUrl('index'));
            }
        }
        $access_list = RoleModuleModel::model()->with('module_id')->findAll('t.is_effect=1');
        $this->render('create', array(
            'model' => $model,
            'access_list' => $access_list,
            'role_access' => array()
        ));
    }

   /*
    * 删除角色
    */
    public function actionDelete($id)
    {
        $transaction=Yii::app()->db->beginTransaction();
        try{
            $this->loadModel($id)->delete();
            RoleAccessModel::model()->deleteAll('role_id=:role_id',array(':role_id'=>$id));
            $transaction->commit();//提交事务会真正的执行数据库操作
            $this->showJsonResult(1);
        }catch (Exception $e) {
            $transaction->rollback();//如果操作失败, 数据回滚
            $this->showJsonResult(0);
        }
    }
        /*
         * 编辑角色
         */
    public function actionUpdate($id)
    {
        $model=$this->loadModel($id);
        $role_access=array();
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if(isset($_POST['RoleModel']))
        {
            $model->attributes=$_POST['RoleModel'];
            $actions = $_POST['role_access'];
            if($model->save()){
                //role access
                RoleAccessModel::model()->deleteAll('role_id=:role_id',array(':role_id'=>$id));
                foreach ($actions as $a) {
                    list($module, $action) = explode('_', $a);
                    $accessModel = new RoleAccessModel();
                    $accessModel->action_id = $action;
                    $accessModel->module_id = $module;
                    $accessModel->role_id = $model->id;
                    $accessModel->save();
                }
                $this->showSuccess('编辑成功',$this->createUrl('index'));
            }
        }
        $access_list = RoleModuleModel::model()->with('module_id')->findAll('t.is_effect=1');
        $role_list = RoleAccessModel::model()->findAll('role_id=:role_id',array(':role_id'=>$id));
        foreach($role_list as $r){
            $role_access[$r['module_id']][$r['action_id']] = $r;
        }
        $this->render('update',array(
            'model'=>$model,
            'access_list'=>$access_list,
            'role_access'=>$role_access,
        ));
    }
    public function loadModel($id)
    {
        $model=RoleModel::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }
}