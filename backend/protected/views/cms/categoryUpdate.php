<?php
/* @var $this CategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('/site/index')),
    array('name'=>'文章管理'),
    array('name' => '文章分类列表', 'url' => array('cms/category')),
    array('name'=>'更新文章分类')
);
$this->pageTitle = '更新文章分类';
$this->title = '更新文章分类<small>文章分类管理</small>';
?>
<div class="page-bar">
    <?php echo $this->renderPartial('form',array('model'=>$model));?>
</div>
