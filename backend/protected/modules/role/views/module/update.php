
<?php
/* @var $this CategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name' => '系统管理', ),
    array('name'=>'模块管理','url' => array('module/index')),
    array('name'=>'修改模块')
);
$this->pageTitle = '修改模块';
$this->title = '模块管理列表<small>修改模块</small>';
?>
<div class="page-bar">
           <?php echo $this->renderPartial('form',array('model'=>$model));?>
    </div>
