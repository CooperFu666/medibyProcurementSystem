<?php
$this->breadcrumbs=array(
	array('name' => '首页', 'url' => array('site/index')),
	array('name' => '商品配置'),
	array('name' => '型号添加')
);

$this->pageTitle = '型号添加';
$this->title = '型号添加';
?>
<div class="page-bar">
    <?php echo $this->renderPartial('_form',array('model'=>$model));?>
</div>

