<?php
/* @var $this ProjectController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('/site/index')),
    array('name'=>'商品管理'),
    array('name' => '商品列表', 'url' => array('goods/index')),
    array('name'=>'商品编辑')
);
$this->pageTitle = '商品编辑';
$this->title = '商品编辑 <small>商品配置</small>';
?>

<?php echo $this->renderPartial('_form',array('model'=>$model,'categoryTree'=>$categoryTree));?>



