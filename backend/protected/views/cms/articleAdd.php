<?php
/* @var $this CategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('/site/index')),
    array('name'=>'文章管理'),
    array('name' => '文章列表', 'url' => array('cms/article')),
    array('name'=>'添加文章')
);
$this->pageTitle = '添加文章';
$this->title = '添加文章<small>文章管理</small>';
?>
<div class="page-bar">
          
    <?php echo $this->renderPartial("articleform",array('model'=>$model));?>

</div>
