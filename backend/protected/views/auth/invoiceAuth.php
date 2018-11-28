<?php
$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name' => '发票审核','url'=>array('auth/invoiceAuth')),
    array('name' => '发票信息列表')
);

$this->pageTitle = '发票信息列表';
$this->title = '待审核发票信息列表';

?>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js"></script>
<div class="row">
    <div class="col-md-12">
        <div class="portlet">
        
            <div class="portlet-title form-horizontal">
                <div class="caption">
                    <i class="fa fa-paper-plane"></i>待审核发票信息列表
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-container">
                    <table class="table table-striped table-bordered table-hover" id="datatable_list">
                        <thead>
	                        <tr role="row" class="heading">
	                            <th width="25%">手机号</th>
	                            <th width="25%">公司名称</th>
                                <th width="25%">发票公司名称</th>
                                <th width="25%">操作</th>
                            </tr>
                            <tr>
                                <th>
                                    <?php echo CHtml::textField('phone','',array('class'=>'form-control form-filter input-sm','placeholder'=>'手机号')); ?>
                                </th>
                                <th>
                                    <?php echo CHtml::textField('corporate_name','',array('class'=>'form-control form-filter input-sm','placeholder'=>'公司名称')); ?>
                                </th>
                                <th>
                                    <?php echo CHtml::textField('invoice_corporate_name','',array('class'=>'form-control form-filter input-sm','placeholder'=>'发票公司名称')); ?>
                                </th>
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
    </div>
</div>

<input type="hidden" name="csrf" value="<?php echo Yii::app()->request->getCsrfToken()?>">

<link href="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css" rel="stylesheet" type="text/css"/>

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/static/js/datatable.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/static/js/ecommerce-list.js"></script>
<script>
    $(document).ready(function() {
   	 var url = '<?php echo $this->createUrl("invoiceAuth",array('isAjax'=>1))?>';
        var token = $("input[name='csrf']").val();
        EcommerceList.init(url,token);
    });
</script>