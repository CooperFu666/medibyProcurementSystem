<div class="row">
    <div class="col-md-12" style="background: white;">
        <?php $form=$this->beginWidget('CActiveForm', array(
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
        <div class="portlet-body">
            <?php echo $form->errorSummary($model, '<button data-close="alert" class="close"></button>','',
                array('class' => 'alert alert-danger'));?>
            <div class="tabbable">
                <div class="tab-content no-space">
                    <div class="tab-pane active" id="tab_general">
                        <div class="form-body">
                            <div class="form-group">
                                <?php echo $form->labelEx($model,'模块方法名',array('class'=>'col-md-2 control-label')); ?>
                                <div class="col-md-8">
                                    <?php echo $form->textField($model,'action',array('class'=>'form-control')); ?>
                                </div>
                                <?php echo $form->error($model,'模块方法名'); ?>
                            </div>
                            <div class="form-group">
                                <?php echo $form->labelEx($model,'模块名称',array('class'=>'col-md-2 control-label')); ?>
                                <div class="col-md-8">
                                    <?php echo $form->textField($model,'name',array('class'=>'form-control')); ?>
                                </div>
                                <?php echo $form->error($model,'名称'); ?>
                            </div>
                            <div class="form-group">
                                <?php echo $form->labelEx($model,'菜单分组',array('class'=>'col-md-2 control-label')); ?>
                                <div class="col-md-8">
                                    <?php if($model->group_id):?>
                                        <?php echo $form->checkBox($model,'group_id',array('class'=>'form-control','checked'=>'checked'));?>
                                    <?php else:?>
                                        <?php echo $form->checkBox($model,'group_id',array('class'=>'form-control'));?>
                                    <?php endif;?>
                                    <?php $display = $model->group_id ? 'block':'none'?>
                                    <?php echo CHtml::DropDownList('group_id',$model->group_id,CHtml::listData($group,'id','name'),array('class'=>'form-control',
                                        'id'=>'group_select','style'=>'display:'.$display))?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?php echo $form->labelEx($model,'是否有效',array('class'=>'col-md-2 control-label')); ?>
                                <div class="col-md-8">
                                    <?php echo $form->checkBox($model,'is_effect',array('class'=>'form-control')); ?>
                                </div>
                                <?php echo $form->error($model,'是否有效');?>
                            </div>
                            <div class="actions btn-set" style="margin:20px 0px 0px 200px;">
                                <button class="btn green" type="submit"><i class="fa fa-check"></i> 保存</button>
                                <button class="btn default" type="reset"><i class="fa fa-reply"></i> 重置</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>

</div>
<script>
    $().ready(function(){
        $("#RoleActionModel_group_id").click(function(){
            if($(this).attr("checked")=="checked"){
                $("#group_select").show();
            }else{
                $("#group_select").hide();
            }
        })

    })

</script>