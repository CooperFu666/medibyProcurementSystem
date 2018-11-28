<?php
/* @var $this RoleModelController */
/* @var $model RoleModel */

$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('/site/index')),
    array('name' => '系统管理'),
    array('name'=>'角色管理'),
    array('name'=>'添加角色')
);
$this->pageTitle = '角色管理';
$this->title = '管理员 <small>角色管理</small>';
?>
<?php $this->renderPartial('_form', array('model'=>$model,'access_list'=>$access_list,'role_access'=>$role_access)); ?>