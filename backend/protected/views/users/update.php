<?php
$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name' => '用户管理','url'=>array('users/index')),
	array('name' => '更新用户')
);

$this->pageTitle = '更新用户';
$this->title = '更新用户<small>用户管理</small>';
?>
<div class="page-bar">
    <?php echo $this->renderPartial('_form',array('model'=>$model));?>
</div>
