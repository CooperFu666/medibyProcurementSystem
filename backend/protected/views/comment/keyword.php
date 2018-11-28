<?php
/* @var $this ServiceController */
$this->breadcrumbs=array(
    array('name' => '首页','url'=>array('site/index') ),
    array('name'=>'评论管理'),
    array('name'=>'关键之管理')
);
?>
<div class="row">
    <div class="col-md-12">
        <!-- Begin: life time stats -->
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-paper-plane"></i>资产评论管理
                </div>
            </div>
<!--            <input type="text" id='key' placeholder="请输入关键字用逗号隔开（中文）" style="width:250px;">-->
<!--            <input type="button" value="确定" id="but" onclick="save()">-->
            <div class="actions">
                <a class="btn default yellow-stripe" href="<?php echo Yii::app()->createUrl('comment/addkey') ?>">
                    <i class="fa fa-plus"></i>
                    <span class="hidden-480">添加关键字 </span>
                </a>
            </div>
            <div class="portlet-body">
                <div class="table-container">
                    <table class="table table-striped table-bordered table-hover" id="datatable_list">
                        <thead>
                        <tr role="row" class="heading">
                            <th width="10%">
                                ID
                            </th>
                            <th width="10%">
                                关键字
                            </th>
                            <th width="10%">
                                替换内容
                            </th>
                            <th width="30%">
                                操作
                            </th>
                        </tr>
                        <th width="10%">
                        </th>
                        <th width="10%">
                            <input class="form-control form-filter input-sm" type="text" name="find" placeholder="关键字"/>
                        </th>
                        <th width="10%">
                        </th>
                        <th width="25%">
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
<input type="hidden" name="csrf" value="<?php echo Yii::app()->request->getCsrfToken() ?>">
<!-- END PAGE CONTENT-->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/js/datatable.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/js/ecommerce-list.js"></script>
<!--style-->
<link
    href="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"
    rel="stylesheet" type="text/css"/>
<script>
    jQuery(document).ready(function () {
        var url = '<?php echo $this->createUrl("Keyword",array('isAjax'=>1))?>';
        var token = $("input[name='csrf']").val();
        EcommerceList.init(url, token);
    });
    function save(){
        var key=$('#key').val();
        var url="<?php echo $this->createUrl('comment/addkey')?>"
        $.ajax({
            type:'POST',
            url:url,
            data:{k:key},
            success:function(e){
                console.debug(e);
            }
        });
    }
</script>