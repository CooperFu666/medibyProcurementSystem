<?php
$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name' => '系统管理'),
    array('name'=>'管理员管理','url'=>array('user/index')),
    array('name'=>'管理员管理列表')
);
$this->title = '管理员管理<small>管理员列表</small>';
$this->pageTitle = "-管理员管理";
?>
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- Begin: life time stats -->
        <div class="portlet">
            <div class="portlet-title form-horizontal">
                <div class="caption">
                    <i class="fa fa-paper-plane"></i>管理员管理
                </div>
                <div class="actions">

                    <a class="btn default yellow-stripe" href="<?php echo Yii::app()->createUrl('user/add')?>">
                        <i class="fa fa-plus"></i>
                        <span class="hidden-480">管理员添加 </span>
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-container">
                    <table class="table table-striped table-bordered table-hover" id="datatable_list">
                        <thead>
                        <tr role="row" class="heading">
                            <th width="10%"> 名字 </th>
                            <th width="17%">电话</th>
                            <th width="10%"> 是否系统 </th>
                            <th width="15%" > 登录时间</th>
                            <th width="13%">登录IP</th>
                            <th width="13%">管理员</th>
                            <th width="14%"> 操作</th>
                        </tr>
                        <tr>
                            <th width="10%"> <input class="form-control form-filter input-sm" type="text" name="username" placeholder="名字"/> </th>
                            <th width="17%"> <input class="form-control form-filter input-sm" type="text" name="phone" placeholder="电话号码"/> </th>
                            <th width="10%">  </th>
                            <th width="15%"> <input class="form-control form-filter input-sm" type="text" name="login_at" placeholder="登录时间"/> </th>
                            <th width="13%">  </th>
                            <th width="13%"> <input class="form-control form-filter input-sm" type="text" name="name" placeholder="管理员"/> </th>
                            <th width="14%">
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
        var url = '<?php echo $this->createUrl("index",array('isAjax'=>1))?>';
        var token = $("input[name='csrf']").val();
        EcommerceList.init(url,token);
    });

</script>