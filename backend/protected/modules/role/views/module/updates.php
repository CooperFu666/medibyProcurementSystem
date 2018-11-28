<?php
$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name' => '系统管理'),
    array('name'=>'模块管理','url'=>array('module/actions')),
    array('name'=>'模块管理列表')
);
$this->pageTitle = '修改模块方法';
$this->title = '模块管理<small>模块管理</small>';
?>
<div class="page-bar">
    <?php echo $this->renderPartial('_form',array('model'=>$model,'group'=>$group));?>
</div>
