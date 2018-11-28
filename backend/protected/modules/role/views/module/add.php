<?php
$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name'=>'系统管理'),
    array('name' => '模块菜单管理'),
    array('name' => '模块列表', 'url' => array('module/index')),
    array('name' => '添加模块'),
);
$this->pageTitle = '添加模块';
$this->title = '模块管理 <small>添加模块</small>';
?>
<div class="page-bar">
          <?php echo $this->renderPartial("form",array('model'=>$model));?>
        <!-- END PORTLET-->

</div>
