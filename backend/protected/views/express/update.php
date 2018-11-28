<?php
$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name' => '快递管理','url'=>array('express/index')),
	array('name' => '更新快递')
);

$this->pageTitle = '更新快递';
$this->title = '更新快递';
?>
<div class="page-bar">
    <?php echo $this->renderPartial('_form',array('model'=>$model));?>
</div>
