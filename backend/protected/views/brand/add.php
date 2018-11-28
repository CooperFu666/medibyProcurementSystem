<?php
$this->breadcrumbs=array(
	array('name' => '首页', 'url' => array('site/index')),
	array('name' => '品牌管理', 'url' => array('brand/index')),
	array('name' => '添加品牌')
);

$this->pageTitle = '品牌列表';
$this->title = '品牌管理';
?>
<div class="page-bar">
    <?php echo $this->renderPartial('_form',array('model'=>$model));?>
</div>

