<?php
/* @var $this RoleModelController */
/* @var $model RoleModel */

$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('/site/index')),
    array('name' => '关键字列表','url'=>array('comment/Keyword')),
    array('name'=>'添加关键字')
);
?>
<?php $this->renderPartial('_form', $result); ?>