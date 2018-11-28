
<?php
/* @var $this CategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name' => '日志管理', ),
    array('name'=>'日志管理'),
);
$this->pageTitle = '日志管理';
$this->title = '日志管理<small>日志管理</small>';
?>
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- Begin: life time stats -->
        <div class="portlet">
            <div class="portlet-title form-horizontal">
                <div class="caption">
                    <i class="fa fa-paper-plane"></i>日志管理
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-container">
                    <table class="table table-striped table-bordered table-hover" id="datatable_list">
                        <thead id="search_thead">
                            <tr id="search_tr_first" role="row" class="heading">
                                <th width="20%">时间</th>
                                <th width="20%">操作者</th>
                                <th width="20%">操作类别</th>
                                <th width="20%">对象</th>
                                <th width="20%">操作</th>
                             </tr>
                            <tr id="search_tr_second">
                                <th>
                                    <?php echo CHtml::textField('date_range','',array('class'=>'form-control form-filter input-sm','placeholder'=>'请输入时间范围')); ?>
                                </th>
                                <th>
                                    <?php echo CHtml::DropDownList('action_id','',$actionUserList,array('class'=>'form-control form-filter input-sm','style' => 'width:auto;display:inline')); ?>
                                </th>
                                <th>
                                    <?php echo CHtml::DropDownList('action_type','',$actionTypeList,array('class'=>'form-control form-filter input-sm','style' => 'width:auto;display:inline')); ?>
                                </th>
                                <th>
                                    <?php echo CHtml::textField('obj_str','',array('class'=>'form-control form-filter input-sm','placeholder'=>'')); ?>
                                </th>
                                <th>
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
<script src="/static/plugins/laydate/laydate.js"></script> <!-- 改成你的路径 -->
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

    laydate.render({
        elem: '#date_range'
        ,type: 'datetime'
        ,range: '到'
        ,format: 'yyyy-M-d'
        ,theme: 'molv'
    });
</script>
