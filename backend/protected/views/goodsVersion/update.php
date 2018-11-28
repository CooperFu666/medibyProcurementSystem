<?php
$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name' => '商品配置'),
    array('name' => '型号修改')
);

$this->pageTitle = '型号修改';
$this->title = '型号修改';
?>
<div class="page-bar">
    <?php echo $this->renderPartial('_form',array('model'=>$model));?>
</div>
