<?php
$this->breadcrumbs=array(
	array('name' => '首页', 'url' => array('site/index')),
    array('name' => '页面管理','url'=>array('home/index')),
    array('name' => '更新分类')
);

$this->pageTitle = '更新分类';
$this->title = '更新分类';
?>
<div class="page-bar">
    <?php echo $this->renderPartial('_form',array('model'=>$model));?>
</div>
