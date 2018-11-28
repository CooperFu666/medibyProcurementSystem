<?php
$this->breadcrumbs=array(
	array('name' => '首页', 'url' => array('site/index')),
    array('name' => '品牌管理', 'url' => array('brand/index')),
    array('name' => '更新品牌')
);

$this->pageTitle = '更新品牌';
$this->title = '更新品牌';
?>
<div class="page-bar">
    <?php echo $this->renderPartial('_form',array('model'=>$model));?>
</div>
