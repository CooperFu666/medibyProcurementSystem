<?php
$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name' => '系统管理'),
    array('name'=>'管理员管理','url'=>array('user/index')),
    array('name'=>'添加管理员')
);
$this->title = '管理员管理<small>管理员管理</small>';
$this->pageTitle = "-添加员管理";
?>
<div class="page-bar">
          <?php echo $this->renderPartial("form",array('model'=>$model,'role'=>$role));?>
        <!-- END PORTLET-->

</div>
