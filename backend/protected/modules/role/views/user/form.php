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
                        <?php echo $form->labelEx($model,'username',array('class'=>'col-md-2 control-label')); ?>
                        <div class="col-md-8">
                            <?php echo $form->textField($model,'username',array('class'=>'form-control')); ?>
                        </div>
                        <?php echo $form->error($model,'username'); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model,'password',array('class'=>'col-md-2 control-label')); ?>
                        <div class="col-md-8">
                            <?php echo $form->textField($model,'password',array('class'=>'form-control')); ?>
                        </div>
                        <?php echo $form->error($model,'password'); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model,'phone',array('class'=>'col-md-2 control-label')); ?>
                        <div class="col-md-8">
                            <?php echo $form->telField($model,'phone',array('class'=>'form-control')); ?>
                        </div>
                       <?php echo $form->error($model,'phone');?>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model,'is_system',array('class'=>'col-md-2 control-label')); ?>
                        <div class="col-md-8">
                            <?php echo $form->DropDownList($model,'is_system',array('0'=>'否','1'=>'是'),array('class'=>'form-control')); ?>
                        </div>
                        <?php echo $form->error($model,'is_system'); ?>
                    </div>

                    <div class="form-group">
                        <?php echo $form->labelEx($model,'role_id',array('class'=>'col-md-2 control-label')); ?>
                        <div class="col-md-8">
                            <?php echo $form->DropDownList($model,'role_id',CHtml::listData($role,'id','name'),array('class'=>'form-control')); ?>
                        </div>
                        <?php echo $form->error($model,'role_id'); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model,'email',array('class'=>'col-md-2 control-label')); ?>
                        <div class="col-md-8">
                            <?php echo $form->emailField($model,'email',array('class'=>'form-control')); ?>
                        </div>
                        <?php echo $form->error($model,'email'); ?>
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
