<?php
$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name' => '运营推广'),
    array('name' => '优惠券管理','url'=>array('coupon/index')),
    array('name' => '发放'.$model->title)
);

$this->title = $model->title.'<small>发放优惠券</small>';
$this->pageTitle = '发放优惠券';
?>
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- Begin: life time stats -->
        <div class="portlet">

            <div class="portlet-title form-horizontal">
                <div class="caption">
                    <button class="btn default yellow-stripe" id="grantAll">
                        <i class="fa fa-plus"></i>
                        <span class="hidden-480">发给全部用户</span>
                    </button>
                </div>
            </div>
            <div class="portlet-body">


                <div class="form-horizontal form-row-seperated ">
                    <div class="form-group">
                        <?php echo CHtml::label('用户手机号搜索','usersSearch',array('class'=>'col-md-2 control-label',)); ?>
                        <div class="col-md-2">
                            <?php echo CHtml::textField('usersSearch','',array('class'=>'form-control','maxlength'=>11,'onkeyup'=>"value=value.replace(/[^\d]/g,'')",'onblur'=>"value=value.replace(/[^\d]/g,'')")); ?>
                        </div>
                        <div class="col-md-2">
                            <?php echo CHtml::button('搜索',array('class'=>'btn green','id'=>'search'))?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php echo CHtml::label('用户选择列表','userList',array('class'=>'col-md-2 control-label',)); ?>
                        <div class="col-md-2">
                            <?php echo CHtml::listBox("userList","",CHtml::listData(UserModel::model()->findAll('status=:status',array(':status'=>UserModel::STATUS_NORMAL)), 'id', 'phone'),array('multiple'=>'true','size'=>10,'class'=>'form-control',))?>
                        </div>
                        <div class="col-md-1">
                            <br><br><br>
                            <button class="btn blue" type="button" id="add_user"><i class="fa fa-forward"></i> 加入</button>
                            <br><br>
                            <button class="btn red" type="button" id="delete_user"><i class="fa fa-backward"></i> 删除</button>
                        </div>
                        <div class="col-md-2">
                            <?php echo CHtml::listBox("grantList","",array(),array('multiple'=>'true','size'=>10,'class'=>'form-control',))?>
                        </div>
                    </div>
                    <div class="actions btn-set" style="margin:20px 0px 0px 185px;">
                        <button class="btn green" type="button" id="grant"><i class="fa fa-check"></i> 发放</button>
                    </div>

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
    var searchUser = "<?php echo $this->createUrl("ajax/SearchUser/",array('isAjax'=>1))?>";
    var grantUser = "<?php echo Yii::app()->createUrl('coupon/grant/id/'.$model->id.'/',array('isAjax'=>1))?>";
    var infoUser = "<?php echo Yii::app()->createUrl('coupon/info/id/'.$model->id)?>";
</script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/js/coupon.js"></script>
