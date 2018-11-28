
<?php
/* @var $this CategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name' => '账号管理', ),
    array('name'=>'账号管理'),
);
$this->pageTitle = '账号管理';
$this->title = '账号管理<small>账号管理</small>';
?>
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- Begin: life time stats -->
        <div class="portlet">
            <div class="portlet-title form-horizontal">
                <div class="caption">
                    <i class="fa fa-paper-plane"></i>账号管理
                </div>
                <div class="actions">

                    <a class="btn default yellow-stripe" href="<?php echo Yii::app()->createUrl('frontAccount/add')?>">
                        <i class="fa fa-plus"></i>
                        <span class="hidden-480">新增账号</span>
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-container">
                    <table class="table table-striped table-bordered table-hover" id="datatable_list">
                        <thead id="search_thead">
                            <tr id="search_tr_first" role="row" class="heading">
                                <th width="14%">序号</th>
                                <th width="14%">姓名</th>
                                <th width="14%">账号（登录名）</th>
                                <th width="14%">角色</th>
                                <th width="14%">联系电话</th>
                                <th width="14%">上次登录时间</th>
                                <th width="14%">操作</th>
                             </tr>
                            <tr id="search_tr_second">
                                <th width="14%">角色：<?php echo CHtml::DropDownList('roleId','',$roleList,array('class'=>'form-control form-filter input-sm','style' => 'width:auto;display:inline')); ?></th>
                                <th width="14%">
                                    <?php echo CHtml::textField('str','',array('class'=>'form-control form-filter input-sm','placeholder'=>'请输入姓名/账号/联系电话')); ?>
                                </th>
                                <th width="14%"></th>
                                <th width="14%"></th>
                                <th width="14%"></th>
                                <th width="14%"></th>
                                <th width="14%">
                                    <button class="btn btn-sm yellow filter-submit margin-bottom"><i class="fa fa-search">搜索</i></button>
<!--                                    <button class="btn btn-sm red filter-cancel"><i class="fa fa-times">重置</i></button>-->
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
<script src="<?php echo Yii::app()->request->baseUrl;?>/static/js/datatable.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/js/ecommerce-list.js"></script>
<!--style-->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css" rel="stylesheet" type="text/css"/>
<script>
    jQuery(document).ready(function() {
        var url = '<?php echo $this->createUrl("index",array('isAjax'=>1))?>';
        var token = $("input[name='csrf']").val();
        EcommerceList.init(url,token);
    });

    $(document).on('click','.reset', function() {
        var button = $(this);
        bootbox.confirm("确认重置密码？", function(result) {
            if(result) {
                var url = button.attr('rel');
                $.getJSON(url,function(backdata){
                    if(backdata.flag==1)
                    {
                        bootbox.alert("重置成功", function() {
                            window.location.href = "<?php echo Yii::app()->getBaseUrl() . '/frontAccount/index'; ?>";
                        });
                    }else{
                        var message = backdata.message?backdata.message:'';
                        bootbox.alert("重置失败"+message);
                    }
                });
            }
        });
    });
</script>