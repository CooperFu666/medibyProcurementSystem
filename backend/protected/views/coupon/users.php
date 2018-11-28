<?php
$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name' => '用户管理'),
    array('name' => '用户列表','url'=>array('users/index')),
    array('name' => '用户优惠券')
);

$this->title = $model->phone.'<small>用户优惠券</small>';
$this->pageTitle = '用户优惠券';
?>

<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- Begin: life time stats -->
        <div class="portlet">
            <div class="portlet-title form-horizontal">
                <div class="caption">
                    <i class="fa fa-paper-plane"></i>用户优惠券
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-container">
                    <table class="table table-striped table-bordered table-hover" id="datatable_list">
                        <thead>
                        <tr role="row" class="heading">
                            <th width="10%">优惠券 </th>
                            <th width="15%">序列号</th>
                            <th width="10%">种类</th>
                            <th width="10%">优惠度</th>
                            <th width="10%">状态</th>
                            <th width="15%">使用时间</th>
                            <th width="15%">发放时间</th>
                            <th width="15%">操作</th>
                        </tr>
                        <tr>
                            <th>
                                <?php echo CHtml::textField('phone','',array('class'=>'form-control form-filter input-sm','placeholder'=>'用户手机')); ?>
                            </th>
                            <th>
                                <?php echo CHtml::textField('code','',array('class'=>'form-control form-filter input-sm','placeholder'=>'序列号')); ?>
                            </th>
                            <th></th>
                            <th></th>
                            <th>
                                <?php echo CHtml::dropDownList('status',"", UserCouponModel::$statusArray , array('prompt' => '选择状态', 'class'=>'form-control form-filter input-sm',)); ?>
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
        var url = '<?php echo $this->createUrl("coupon/users/id/$model->id",array('isAjax'=>1))?>';
        var token = $("input[name='csrf']").val();
        EcommerceList.init(url,token,"","",50);
    });
    $(document).on('click','.bootbox-use', function() {
        var button = $(this);
        bootbox.confirm("确认使用？", function(result) {
            if(result) {
                var url = button.attr('rel');
                $.getJSON(url,function(backdata){
                    if(backdata.success==1)
                    {
                        bootbox.alert("使用成功", function() {
                            window.location.href='';
                        });
                    }else{
                        var message = backdata.message?backdata.message:'';
                        bootbox.alert("使用失败"+message);
                    }
                });
            }
        });
    });

</script>