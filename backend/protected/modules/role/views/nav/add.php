<?php
/* @var $this CategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name'=>'系统管理'),
    array('name'=>'模块菜单管理'),
    array('name' => '菜单列表', 'url' => array('nav/index')),
    array('name'=>'添加菜单')
);
$this->pageTitle = '添加菜单';
$this->title = '菜单管理<small>添加菜单</small>';
?>
<div class="page-bar">
          
    <?php echo $this->renderPartial("form",array('model'=>$model));?>

</div>
