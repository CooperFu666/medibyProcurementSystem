<?php
$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name'=>'商品管理','url'=>array('user/index')),
    array('name'=>'商品列表')
);
$this->title = '商品列表<small>商品管理</small>';
$this->pageTitle = "-商品列表";
?>
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- Begin: life time stats -->
        <div class="portlet">
            <div class="portlet-title form-horizontal">
                <div class="caption">
                    <i class="fa fa-paper-plane"></i>商品列表
                </div>
                <div class="actions">

                    <a class="btn default yellow-stripe" href="<?php echo Yii::app()->createUrl('goods/add')?>">
                        <i class="fa fa-plus"></i>
                        <span class="hidden-480">商品添加 </span>
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-container">
                    <table class="table table-striped table-bordered table-hover" id="datatable_list">
                        <thead>
                        <tr role="row" class="heading">
                            <th width="10%">商品名称</th>
                            <th width="9%">品牌</th>
                            <th width="9%">状态</th>
                            <th width="9%">类型</th>
                            <th width="5%">最新</th>
                            <th width="5%">热门</th>
                            <th width="5%">点击量</th>
                            <th width="5%">销售量</th>
                            <th width="5%">收藏量</th>
                            <th width="38%">操作</th>
                        </tr>
                        <tr>
                            <th>
                                <?php echo CHtml::textField('title','',array('class'=>'form-control form-filter input-sm','placeholder'=>'商品名称')); ?>
                            </th>
                            <th></th>
                            <th>
                                <?php echo CHtml::dropDownList('status',"", GoodsModel::$statusArray , array('prompt' => '选择状态', 'class'=>'form-control form-filter',)); ?>
                            </th>
                            <th>
                                <?php echo CHtml::dropDownList('type',"", GoodsModel::$typeArray , array('prompt' => '选择类型', 'class'=>'form-control form-filter',)); ?>
                            </th>
                            <th></th>
                            <th></th>
                            <th></th>
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


    $(document).on('click','.bootbox-down', function() {
        var button = $(this);
        bootbox.confirm("确认下架？", function(result) {
            if(result) {
                var url = button.attr('rel');
                $.getJSON(url,function(backdata){
                    if(backdata.success==1)
                    {
                        bootbox.alert("下架成功", function() {
                            window.location.href='';
                        });
                    }else{
                        var message = backdata.message?backdata.message:'';
                        bootbox.alert("下架失败"+message);
                    }
                });
            }
        });
    });

    $(document).on('click','.bootbox-up', function() {
        var button = $(this);
        bootbox.confirm("确认重新上架？", function(result) {
            if(result) {
                var url = button.attr('rel');
                $.getJSON(url,function(backdata){
                    if(backdata.success==1)
                    {
                        bootbox.alert("上架成功", function() {
                            window.location.href='';
                        });
                    }else{
                        var message = backdata.message?backdata.message:'';
                        bootbox.alert("上架失败"+message);
                    }
                });
            }
        });
    });
</script>