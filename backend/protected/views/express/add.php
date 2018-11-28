<?php
$this->breadcrumbs=array(
	array('name' => '首页', 'url' => array('site/index')),
    array('name' => '快递管理','url'=>array('express/index')),
    array('name' => '添加快递')
);

$this->pageTitle = '添加快递';
$this->title = '添加快递';
?>
<div class="page-bar">
    <?php echo $this->renderPartial('_form',array('model'=>$model));?>
</div>

