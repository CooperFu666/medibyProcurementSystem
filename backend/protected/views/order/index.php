<?php
$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name'=>'订单列表','url'=>array('order/index')),
    array('name'=>'订单列表')
);
$this->title = '订单列表<small>订单列表</small>';
$this->pageTitle = "-订单列表";
?>
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- Begin: life time stats -->
        <div class="portlet">
            <div class="portlet-title form-horizontal">
                <div class="caption">
                    <i class="fa fa-paper-plane"></i>订单列表
                </div>
<!--                <div class="actions">-->
<!--                    <a class="btn default yellow-stripe" href="--><?php //echo Yii::app()->createUrl('goods/add')?><!--">-->
<!--                        <i class="fa fa-plus"></i>-->
<!--                        <span class="hidden-480">商品添加 </span>-->
<!--                    </a>-->
<!--                </div>-->
            </div>
            <div class="portlet-body">
                <div class="table-container">
                    <table class="table table-striped table-bordered table-hover" id="datatable_list">
                        <thead>
                        <tr role="row" class="heading">
                            <th width="16.7%">订单号</th>
                            <th width="16.7%">用户名</th>
                            <th width="16.7%">下单时间</th>
                            <th width="16.7%">支付时间</th>
                            <th width="16.7%">状态</th>
                            <th width="16.7%">操作</th>
                        </tr>
                        <tr>
                            <th><?php echo CHtml::textField('order_id','',array('class'=>'form-control form-filter input-sm','placeholder'=>'订单号')); ?></th>
                            <th><?php echo CHtml::textField('nickname','',array('class'=>'form-control form-filter input-sm','placeholder'=>'用户名')); ?></th>
                            <th></th>
                            <th></th>
                            <th><?php echo CHtml::dropDownList('status',"", OrderModel::$statusArray , array('prompt' => '选择状态', 'class'=>'form-control form-filter',)); ?></th>
                            <th>
                                <button class="btn btn-sm yellow filter-submit margin-bottom"><i class="fa fa-search">搜索</i></button>
                                <button class="btn btn-sm red filter-cancel"><i class="fa fa-times">重置</i></button>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- End: life time stats -->
    </div>
</div>
<input type="hidden" name="csrf" value="<?php echo Yii::app()->request->getCsrfToken()?>">
<!-- END PAGE CONTENT-->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/js/datatable.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/js/ecommerce-list.js"></script>
<!--style-->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css" rel="stylesheet" type="text/css"/>
<script>
    jQuery(document).ready(function() {
        <?php
        if($category){
            $url = array('isAjax'=>1,'category'=>$category);
        }else{
            $url = array('isAjax'=>1);
        }
        ?>
        var url = '<?php echo $this->createUrl("index",$url)?>';
        var token = $("input[name='csrf']").val();
        EcommerceList.init(url,token);
    });

    function confirm_pay() {
        var msg = "确认已收款吗？";
        if (confirm(msg)==true){
            return true;
        }else{
            return false;
        }
    }
</script>