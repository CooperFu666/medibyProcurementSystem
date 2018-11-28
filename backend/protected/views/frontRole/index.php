
<?php
/* @var $this CategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name' => '角色管理', ),
    array('name'=>'角色列表'),
);
$this->pageTitle = '角色列表';
$this->title = '角色列表<small>角色列表</small>';
?>
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- Begin: life time stats -->
        <div class="portlet">
            <div class="portlet-title form-horizontal">
                <div class="caption">
                    <i class="fa fa-paper-plane"></i>角色列表
                </div>
                <div class="actions">

                    <a class="btn default yellow-stripe" href="<?php echo Yii::app()->createUrl('frontRole/add')?>">
                        <i class="fa fa-plus"></i>
                        <span class="hidden-480">新增角色 </span>
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-container">
                    <table class="table table-striped table-bordered table-hover" id="datatable_list">
                        <thead>
                        <tr role="row" class="heading">
                            <th width="18%">角色</th>
                            <th width="18%">账号</th>
                            <th width="46%">操作</th>
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

    $(document).on('click','.delete', function() {
        var button = $(this);
        bootbox.confirm("确认删除？", function(result) {
            if(result) {
                var url = button.attr('rel');
                $.getJSON(url,function(backdata){
                    if(backdata.flag==1)
                    {
                        bootbox.alert("删除成功", function() {
                            window.location.href = "<?php echo Yii::app()->getBaseUrl() . '/frontRole/index'; ?>";
                        });
                    }else{
                        var message = backdata.message?backdata.message:'';
                        bootbox.alert("删除失败"+message);
                    }
                });
            }
        });
    });
</script>