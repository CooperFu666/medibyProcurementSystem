<?php
/* @var $this RoleModelController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    array('name' => '系统管理',),
    array('name' => '管理组管理', 'url' => array('role/index')),
    array('name' => '角色管理')
);
$this->pageTitle = '角色管理';
$this->title = '管理员 <small>角色管理</small>';
?>
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- Begin: life time stats -->
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-paper-plane"></i>角色管理
                </div>
                <div class="actions">

                    <a class="btn default yellow-stripe" href="<?php echo Yii::app()->createUrl('role/create') ?>">
                        <i class="fa fa-plus"></i>
                        <span class="hidden-480">角色添加 </span>
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-container">
                    <table class="table table-striped table-bordered table-hover" id="datatable_list">
                        <thead>
                        <tr role="row" class="heading">
                            <th width="1%">
                                <input type="checkbox" class="role-checkable">
                            </th>
                            <th width="10%">
                                名称
                            </th>
                            <th width="10%">
                                是否系统
                            </th>
                            <th width="10%">
                                操作
                            </th>
                        </tr>
                        </thead
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- End: life time stats -->
    </div>
</div>
<input type="hidden" name="csrf" value="<?php echo Yii::app()->request->getCsrfToken() ?>">
<!-- END PAGE CONTENT-->
<script type="text/javascript"
        src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript"
        src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/js/datatable.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/js/ecommerce-list.js"></script>
<!--style-->
<link
    href="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"
    rel="stylesheet" type="text/css"/>
<script>
    jQuery(document).ready(function () {
        var url = '<?php echo $this->createUrl("index",array('isAjax'=>1))?>';
        var token = $("input[name='csrf']").val();
        EcommerceList.init(url, token);
    });
</script>