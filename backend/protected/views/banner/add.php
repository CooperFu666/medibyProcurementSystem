<?php
$this->breadcrumbs=array(
	array('name' => '首页', 'url' => array('site/index')),
	array('name' => 'banner图片管理'),
	array('name' => '添加banner图片')
);

$this->pageTitle = 'banner列表';
$this->title = '首页banner图片管理';
?>
<div class="page-bar">
    <?php echo $this->renderPartial('_form',array('model'=>$model));?>
</div>

