<?php
/* @var $this ProjectController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('/site/index')),
    array('name'=>'商品管理'),
    array('name' => '商品列表', 'url' => array('goods/index')),
    array('name'=>'商品库存')
);
$this->pageTitle = '商品发布';
$this->title = $model->title.' <small>商品库存</small>';
?>
<div class="row" xmlns="http://www.w3.org/1999/html">
    <div class="col-md-12">
        <!-- Begin: life time stats -->
        <div class="portlet">
            <div class="portlet-title form-horizontal">
                <div class="caption">
                    <i class="fa fa-paper-plane"></i>库存设置
                </div>
            </div>
            <form class="form-horizontal form-row-seperated" id="yw0" method="post">
                <div class="portlet-body">
                    <div class="table-container">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr role="row" class="heading">
                                <th width="32%"> 型号/属性 </th>
                                <th width="30%"> 库存 </th>
                                <th width="30%"> 单价 </th>
                                <th width="8%"> 不存在 </th>
                            </tr>
                            </thead>
                            <tbody id="goodsbind">
                                <?php
                                if($bind){
                                foreach ($bind as $key=>$obj):?>
                                <tr role="row" class="odd">
                                    <td><?php echo $obj->version->title ?>   <?php if(isset($obj->attr))echo " / ".$obj->attr->title ?><input type="hidden" value="<?php echo $obj->id ?>" name="gooodsbind[<?php echo $key?>][id]"></td>
                                    <td><input type="number" value="<?php echo $obj->stock ?>" class="form-control" name="gooodsbind[<?php echo $key?>][stock]"></td>
                                    <td><input type="text" onkeyup="value=value.replace(/[^\d\.]/g,'')" onblur="value=value.replace(/[^\d\.]/g,'')" value="<?php echo $obj->price ?>" class="form-control" name="gooodsbind[<?php echo $key?>][price]"></td>
                                    <td><input name="gooodsbind[<?php echo $key?>][is_non]" <?php if($obj->is_non)echo 'checked="checked"'?> value="1" type="checkbox"></td>
                                </tr>
                                <?php endforeach;}else{echo '<tr class="odd"><td valign="top" colspan="3" class="dataTables_empty">请先设置型号和属性</td></tr>';}?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="actions btn-set">
                    <button class="btn green" type="submit"><i class="fa fa-check"></i> 发布</button>
                    <button class="btn default" type="reset"><i class="fa fa-reply"></i> 重置</button>
                </div>
            </form>
        </div>
    </div>
</div>


<input type="hidden" name="csrf" value="<?php echo Yii::app()->request->getCsrfToken()?>">
<!--style-->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/js/datatable.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/js/ecommerce-list.js"></script>
<link href="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css" rel="stylesheet" type="text/css"/>