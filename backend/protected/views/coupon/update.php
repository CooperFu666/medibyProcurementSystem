<?php
$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name' => '优惠券管理','url'=>array('coupon/index')),
    array('name' => '更新优惠券')
);

$this->title = '更新优惠券<small>优惠券管理</small>';
$this->pageTitle = '更新优惠券';
?>
<div class="page-bar">
    <?php echo $this->renderPartial('_form',array('model'=>$model));?>
</div>
