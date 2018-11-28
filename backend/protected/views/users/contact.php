<?php
$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name' => '客服管理'),
    array('name' => '预约联系表')
);

$this->pageTitle = '预约联系表';
$this->title = '预约联系表';

?>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js"></script>
<div class="row">
    <div class="col-md-12">
        <div class="portlet">
        
            <div class="portlet-title form-horizontal">
                <div class="caption">
                    <i class="fa fa-paper-plane"></i>预约联系表
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-container">
                    <table class="table table-striped table-bordered table-hover" id="datatable_list">
                        <thead>
	                        <tr role="row" class="heading">
	                            <th width="15%">手机号</th>
	                            <th width="20%">公司名称</th>
                                <th width="10%">固定电话</th>
                                <th width="7%">状态</th>
                                <th width="8%">类型</th>
                                <th width="10%">提交时间</th>
	                            <th width="15%">预约时间</th>
                                <th width="15%">操作</th>
                            </tr>
                            <tr>
                                <th>
                                    <?php echo CHtml::textField('phone','',array('class'=>'form-control form-filter input-sm','placeholder'=>'手机号')); ?>
                                </th>
                                <th>
                                    <?php echo CHtml::textField('corporate_name','',array('class'=>'form-control form-filter input-sm','placeholder'=>'公司名称')); ?>
                                </th>
                                <th></th>
                                <th>
                                    <?php echo CHtml::dropDownList('status',"", UserContactModel::$statusArray , array('prompt' => '选择状态', 'class'=>'form-control form-filter',)); ?>
                                </th>
                                <th>
                                    <?php echo CHtml::dropDownList('type',"", UserContactModel::$typeArray , array('prompt' => '选择类型', 'class'=>'form-control form-filter',)); ?>
                                </th>
                                <th></th>
                                <th></th>
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
   	 var url = '<?php echo $this->createUrl("contact",array('isAjax'=>1))?>';
        var token = $("input[name='csrf']").val();
        EcommerceList.init(url,token);
    });

    $(document).on('click','.bootbox-miss', function() {
        var button = $(this);
        bootbox.confirm("确认设为联系不上？", function(result) {
            if(result) {
                var url = button.attr('rel');
                $.getJSON(url,function(backdata){
                    if(backdata.success==1)
                    {
                        bootbox.alert("设置成功", function() {
                            window.location.href='';
                        });
                    }else{
                        var message = backdata.message?backdata.message:'';
                        bootbox.alert("设置失败"+message);
                    }
                });
            }
        });
    });

    $(document).on('click','.bootbox-com', function() {
        var button = $(this);
        bootbox.confirm("确认设为已联系？", function(result) {
            if(result) {
                var url = button.attr('rel');
                $.getJSON(url,function(backdata){
                    if(backdata.success==1)
                    {
                        bootbox.alert("设置成功", function() {
                            window.location.href='';
                        });
                    }else{
                        var message = backdata.message?backdata.message:'';
                        bootbox.alert("设置失败"+message);
                    }
                });
            }
        });
    });
</script>