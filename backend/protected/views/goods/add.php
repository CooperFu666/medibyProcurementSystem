<?php
/* @var $this ProjectController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('/site/index')),
    array('name'=>'商品管理'),
    array('name' => '商品列表', 'url' => array('goods/index')),
    array('name'=>'商品添加')
);
$this->pageTitle = '商品添加';
$this->title = '商品添加 <small>商品配置</small>';
?>

<?php echo $this->renderPartial('_form',array('model'=>$model));?>



