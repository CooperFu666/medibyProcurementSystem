
<?php
/* @var $this CategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name' => '角色管理', 'url' => array('frontRole/index')),
    array('name'=>'角色列表', 'url' => array('frontRole/index')),
    array('name'=>'新增角色'),
);
$this->pageTitle = '角色列表';
$this->title = '角色列表<small>角色列表</small>';
?>
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <?php $form=$this->beginWidget('CActiveForm', array(
            'enableAjaxValidation'=>false,
            'enableClientValidation' => true,
            'htmlOptions'=>array('class'=>'form-horizontal form-row-seperated',"enctype" => "multipart/form-data"),
//            'clientOptions' => array(
//                'validateOnSubmit' => true,
//                'afterValidate' => 'js:function(form, data, hasError) {
//		                  if(hasError) {
//		                      for(var i in data) $("#"+i).parents(".form-group").addClass("has-error");
//		                      return false;
//		                  }
//		                  else {
//		                      form.children().removeClass("has-error");
//		                      return true;
//		                  }
//		              }',
//                'afterValidateAttribute' => 'js:function(form, attribute, data, hasError) {
//		                  if(hasError) $("#"+attribute.id).parents(".form-group").addClass("has-error");
//		                      else $("#"+attribute.id).parents(".form-group").removeClass("has-error");
//		              }'
//            )
        )); ?>
        <style>
            .table tbody tr td{border-top: 0px;}
        </style>
            <table class="table">
                <tr>
                    <td>角色名：</td>
                    <td><?php echo $form->TextField($model,'role_name'); ?></td>
                </tr>
                <tr><td>权限：</td></tr>
                <tr><td><strong>审批/提交权限</strong</td></tr>
                <tr>
                    <td><label><?php echo $form->checkBox($model,'access[]',['class'=>'col-md-2 control-label','value' => FrontRoleAccessModel::ACCESS_SITE_SUBMIT_PURCHASE,'checked' => in_array(FrontRoleAccessModel::ACCESS_SITE_SUBMIT_PURCHASE, $accessArr)?'checked':'']); ?>提交采购申请</label></td>
                    <td><label><?php echo $form->checkBox($model,'access[]',['class'=>'col-md-2 control-label','value' => FrontRoleAccessModel::ACCESS_BUTTON_NOT_PURCHASE,'checked' => in_array(FrontRoleAccessModel::ACCESS_BUTTON_NOT_PURCHASE, $accessArr)?'checked':'']); ?>不执行项目</label></td>
                    <td><label><?php echo $form->checkBox($model,'access[]',['class'=>'col-md-2 control-label','value' => FrontRoleAccessModel::ACCESS_ROLE_PRINCIPAL,'checked' => in_array(FrontRoleAccessModel::ACCESS_ROLE_PRINCIPAL, $accessArr)?'checked':'']); ?>是否可作为项目负责人</label></td>
                    <td><label><?php echo $form->checkBox($model,'access[]',['class'=>'col-md-2 control-label','value' => FrontRoleAccessModel::ACCESS_ROLE_PRICING,'checked' => in_array(FrontRoleAccessModel::ACCESS_ROLE_PRICING, $accessArr)?'checked':'']); ?>定价执行者</label></td>
                    <td><label><?php echo $form->checkBox($model,'access[]',['class'=>'col-md-2 control-label','value' => FrontRoleAccessModel::ACCESS_ROLE_SUGGEST,'checked' => in_array(FrontRoleAccessModel::ACCESS_ROLE_SUGGEST, $accessArr)?'checked':'']); ?>采购量建议者</label></td>
                    <td><label><?php echo $form->checkBox($model,'access[]',['class'=>'col-md-2 control-label','value' => FrontRoleAccessModel::ACCESS_ROLE_APPROVAL,'checked' => in_array(FrontRoleAccessModel::ACCESS_ROLE_APPROVAL, $accessArr)?'checked':'']); ?>是否继续审批</label></td>
                    <td><label><?php echo $form->checkBox($model,'access[]',['class'=>'col-md-2 control-label','value' => FrontRoleAccessModel::ACCESS_ROLE_PURCHASE,'checked' => in_array(FrontRoleAccessModel::ACCESS_ROLE_PURCHASE, $accessArr)?'checked':'']); ?>采购执行者</label></td>
                </tr>
                <tr><td><strong>管理者权限</strong</td></tr>
                <tr>
                    <td><label><?php echo $form->checkBox($model,'access[]',['class'=>'col-md-2 control-label','value' => FrontRoleAccessModel::ACCESS_SITE_DOC,'checked' => in_array(FrontRoleAccessModel::ACCESS_SITE_DOC, $accessArr)?'checked':'']); ?>采购档案</label></td>
                    <td><label><?php echo $form->checkBox($model,'access[]',['class'=>'col-md-2 control-label','value' => FrontRoleAccessModel::ACCESS_BUTTON_RELOAD,'checked' => in_array(FrontRoleAccessModel::ACCESS_BUTTON_RELOAD, $accessArr)?'checked':'']); ?>重启采购项目</label></td>
                    <td><label><?php echo $form->checkBox($model,'access[]',['class'=>'col-md-2 control-label','value' => FrontRoleAccessModel::ACCESS_GLOBAL_NEWS,'checked' => in_array(FrontRoleAccessModel::ACCESS_GLOBAL_NEWS, $accessArr)?'checked':'']); ?>全局动态</label></td>
                </tr>
                <tr><td><strong>填写调研报告权限</strong</td></tr>
                <tr>
                    <td><label><?php echo $form->checkBox($model,'access[]',['class'=>'col-md-2 control-label','value' => FrontRoleAccessModel::ACCESS_ROLE_BASE_INFO,'checked' => in_array(FrontRoleAccessModel::ACCESS_ROLE_BASE_INFO, $accessArr)?'checked':'']); ?>基础信息</label></td>
                    <td><label><?php echo $form->checkBox($model,'access[]',['class'=>'col-md-2 control-label','value' => FrontRoleAccessModel::ACCESS_ROLE_FOREIGN_PRICE,'checked' => in_array(FrontRoleAccessModel::ACCESS_ROLE_FOREIGN_PRICE, $accessArr)?'checked':'']); ?>国外价格</label></td>
                    <td><label><?php echo $form->checkBox($model,'access[]',['class'=>'col-md-2 control-label','value' => FrontRoleAccessModel::ACCESS_ROLE_INTERVAL_PRICE,'checked' => in_array(FrontRoleAccessModel::ACCESS_ROLE_INTERVAL_PRICE, $accessArr)?'checked':'']); ?>国内价格</label></td>
                    <td><label><?php echo $form->checkBox($model,'access[]',['class'=>'col-md-2 control-label','value' => FrontRoleAccessModel::ACCESS_BUTTON_RESEARCH_SELECT,'checked' => in_array(FrontRoleAccessModel::ACCESS_BUTTON_RESEARCH_SELECT, $accessArr)?'checked':'']); ?>调研/不调研</label></td>
                </tr>
                <tr><td><strong>项目内容查看权限</strong</td></tr>
                <tr>
                    <td><label><?php echo $form->checkBox($model,'access[]',['class'=>'col-md-2 control-label','value' => FrontRoleAccessModel::ACCESS_COLUMN_INTERVAL_PRICE,'checked' => in_array(FrontRoleAccessModel::ACCESS_COLUMN_INTERVAL_PRICE, $accessArr)?'checked':'']); ?>国内价格</label></td>
                    <td><label><?php echo $form->checkBox($model,'access[]',['class'=>'col-md-2 control-label','value' => FrontRoleAccessModel::ACCESS_COLUMN_FOREIGN_PRICE,'checked' => in_array(FrontRoleAccessModel::ACCESS_COLUMN_FOREIGN_PRICE, $accessArr)?'checked':'']); ?>国外价格</label></td>
                    <td><label><?php echo $form->checkBox($model,'access[]',['class'=>'col-md-2 control-label','value' => FrontRoleAccessModel::ACCESS_COLUMN_TARGET_PRICING,'checked' => in_array(FrontRoleAccessModel::ACCESS_COLUMN_TARGET_PRICING, $accessArr)?'checked':'']); ?>目标采购价</label></td>
                    <td><label><?php echo $form->checkBox($model,'access[]',['class'=>'col-md-2 control-label','value' => FrontRoleAccessModel::ACCESS_COLUMN_APPLY_NUMBER,'checked' => in_array(FrontRoleAccessModel::ACCESS_COLUMN_APPLY_NUMBER, $accessArr)?'checked':'']); ?>申请采购量</label></td>
                    <td><label><?php echo $form->checkBox($model,'access[]',['class'=>'col-md-2 control-label','value' => FrontRoleAccessModel::ACCESS_COLUMN_SUGGEST_NUMBER,'checked' => in_array(FrontRoleAccessModel::ACCESS_COLUMN_SUGGEST_NUMBER, $accessArr)?'checked':'']); ?>建议采购量</label></td>
                    <td><label><?php echo $form->checkBox($model,'access[]',['class'=>'col-md-2 control-label','value' => FrontRoleAccessModel::ACCESS_COLUMN_REAL_NUMBER,'checked' => in_array(FrontRoleAccessModel::ACCESS_COLUMN_REAL_NUMBER, $accessArr)?'checked':'']); ?>实际采购量</label></td>
                    <td><label><?php echo $form->checkBox($model,'access[]',['class'=>'col-md-2 control-label','value' => FrontRoleAccessModel::ACCESS_COLUMN_PURCHASE_UNIT,'checked' => in_array(FrontRoleAccessModel::ACCESS_COLUMN_PURCHASE_UNIT, $accessArr)?'checked':'']); ?>采购单位</label></td>
                    <td><label><?php echo $form->checkBox($model,'access[]',['class'=>'col-md-2 control-label','value' => FrontRoleAccessModel::ACCESS_COLUMN_REAL_PRICE,'checked' => in_array(FrontRoleAccessModel::ACCESS_COLUMN_REAL_PRICE, $accessArr)?'checked':'']); ?>实际采购价</label></td>
                </tr>
            </table>
        <br/>
        <br/>
            <div><center><button class="btn green" type="submit"><i class="fa fa-check"></i> 保存</button></center></div>
        <?php $this->endWidget(); ?>
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

</script>