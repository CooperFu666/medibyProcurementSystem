<?php
/* @var $this CategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name'=>'系统管理'),
    array('name' => '菜单管理', 'url' => array('nav/index')),
    array('name' => '子菜单管理', 'url' => array('nav/groupIndex')),
    array('name'=>'编辑子菜单')
);
$this->pageTitle = '编辑子菜单';
$this->title = '菜单管理<small>菜单管理</small>';
?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="<?php echo Yii::app()->createUrl('nav/index')?>">菜单管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="<?php echo Yii::app()->createUrl('nav/groupIndex/id/'.$id)?>">子菜单管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="javascript:void(0);">子菜单修改</a>
        </li>
    </ul>

    <?php echo $this->renderPartial('groupForm',array('model'=>$model, 'id'=>$id));?>
</div>
