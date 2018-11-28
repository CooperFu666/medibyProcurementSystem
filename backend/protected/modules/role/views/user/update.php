
<?php
/* @var $this CategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name' => '系统管理', ),
    array('name'=>'管理员管理','url' => array('user/index')),
    array('name'=>'编辑管理员')
);
$this->title = '管理员管理<small>编辑管理员</small>';
$this->pageTitle = "-编辑管理员";
?>
<div class="page-bar">
           <?php echo $this->renderPartial('form',array('model'=>$model,'role'=>$role));?>
    </div>
