<?php
$this->breadcrumbs=array(
	array('name' => '首页', 'url' => array('site/index')),
	array('name' => 'banner管理','url'=>array('article/index')),
	array('name' => '更新banner')
);

$this->pageTitle = 'banner列表';
$this->title = 'banner管理';
?>
<div class="page-bar">
    <?php echo $this->renderPartial('_form',array('model'=>$model));?>
</div>
