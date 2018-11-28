<?php
/* @var $this RoleModelController */
/* @var $model RoleModel */
/* @var $form CActiveForm */
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/bootstrap-datepicker/css/datepicker.css"/>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12" style="background: white;">
        <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'product-model-form',
            'enableAjaxValidation'=>false,
            'enableClientValidation' => true,
            'htmlOptions'=>array('class'=>'form-horizontal form-row-seperated',"enctype" => "multipart/form-data"),
            'clientOptions' => array(
                'validateOnSubmit' => true,
                'afterValidate' => 'js:function(form, data, hasError) {
                  if(hasError) {
                      for(var i in data) $("#"+i).parents(".form-group").addClass("has-error");
                      return false;
                  }
                  else {
                      form.children().removeClass("has-error");
                      return true;
                  }
              }',
                'afterValidateAttribute' => 'js:function(form, attribute, data, hasError) {
                  if(hasError) $("#"+attribute.id).parents(".form-group").addClass("has-error");
                      else $("#"+attribute.id).parents(".form-group").removeClass("has-error");
              }'
            )
        )); ?>
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cubes"><?php echo $this->title?></i>
                </div>
                <div class="actions btn-set">
                    <button type="button" name="back" class="btn default"><i class="fa fa-angle-left"></i> 返回</button>
                    <button class="btn default" type="reset"><i class="fa fa-reply"></i> 重置</button>
                    <button class="btn green" type="submit" click="check()"><i class="fa fa-check"></i> 保存</button>
                </div>
            </div>
            <div class="portlet-body">
                <?php echo $form->errorSummary($model, '<button data-close="alert" class="close"></button>','', array('class' => 'alert alert-danger'));?>
                <div class="tabbable">
                    <div class="tab-content no-space">
                        <div class="tab-pane active" id="tab_general">
                            <div class="form-body">
                                <div class="form-group">
                                    <?php echo $form->labelEx($model,'name',array('class'=>'col-md-2 control-label')); ?>
                                    <div class="col-md-8">
                                        <?php echo $form->textField($model,'name',array('class'=>'form-control')); ?>
                                    </div>
                                    <?php echo $form->error($model,'name'); ?>
                                </div>
                                <div class="form-group">
                                    <?php echo $form->labelEx($model,'is_system',array('class'=>'col-md-2 control-label')); ?>
                                    <div class="col-md-8">
                                        <?php $disabled = $model->is_system?array('disabled'=>'disabled'):array();?>
                                        <?php echo $form->dropDownList($model,'is_system',array('否','是'),array_merge($disabled,array('class'=>'form-control'))); ?>
                                    </div>
                                    <?php echo $form->error($model,'is_system'); ?>
                                </div>
                                <div class="form-group">
                                    <?php echo CHtml::label('权限','',array('class'=>'col-md-2 control-label')); ?>
                                    <div class="col-md-8">
                                        <table class="access_list" cellpadding=0 cellspacing=0>
                                            <?php foreach($access_list as $a){?>
                                                <tr>
                                                    <td class="access_left" style="width:180px;">
                                                        <span title="<?php echo $a['name']?>"><?php echo $a['name']?></span>
                                                        &nbsp;&nbsp;全选<input type="checkbox" value="<?php echo $a['id']?>" <?php if(isset($role_access[$a['id']]) && count($role_access[$a['id']])==count($a['module_id'])){?>checked="checked" <?php }?> class="check_all" name="role_group[]" onclick="check_module(this);"  />
                                                    </td>
                                                    <td>
                                                        <?php foreach($a['module_id'] as $n){?>
                                                            <label style="padding:5px;">
                                                                <span title="<?php echo $n['action']?>"><?php echo $n['name']?></span>
                                                                <input type="checkbox"  <?php if(isset($role_access[$a['id']][$n['id']])){?>checked="checked" <?php }?> value="<?php echo $a['id'].'_'.$n['id']?>" <?php if(isset($a['is_oauth'])){?>checked="checked" <?php }?> name="role_access[]" class="node_item" onclick="check_is_all(this);" />
                                                            </label>
                                                        <?php }?>
                                                    </td>
                                                </tr>
                                            <?php }?>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>
<!-- END PAGE CONTENT-->
<script>
    $(document).ready(function(){

    })
    $('.green').click(function check(){
        var values=$('input[class="node_item"]'.checked).val();
        if(values==''){
            alert("请选择相关列表！");
        }
    })
</script>
