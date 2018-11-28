<?php
$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name'=>'系统管理'),
    array('name' => '模块菜单管理'),
    array('name' => '模块列表', 'url' => array('module/index')),
    array('name' => '添加模块方法'),
);
$this->pageTitle = '添加模块方法';
$this->title = '模块方法 <small>模块方法</small>';
?>
<div class="page-bar">
    <?php echo $this->renderPartial("_form",array('model'=>$model,'group'=>$group,'models'=>$models));?>

</div>

