<?php
/* @var $this CategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name'=>'商品管理'),
    array('name'=>'商品分类管理'),
    array('name' => '商品主分类列表', 'url' => array('category/index')),
    array('name'=>'添加商品主分类')
);
$this->pageTitle = '添加商品主分类';
$this->title = '商品分类管理<small>添加商品主分类</small>';
?>
<div class="page-bar">
          
    <?php echo $this->renderPartial("form",array('model'=>$model));?>

</div>
