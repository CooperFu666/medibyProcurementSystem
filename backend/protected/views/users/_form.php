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
		                    for(var i in data){
		    					$("#"+i).parents(".form-group").addClass("has-error");
		    				}
		                    return false;
		                }
		                  else {
		                      form.children().removeClass("has-error");
		                      return true;
		                  }
		              }',
		        'afterValidateAttribute' => 'js:function(form, attribute, data, hasError) {
		                  if(hasError){$("#"+attribute.id).parents(".form-group").addClass("has-error");}
		                  else{$("#"+attribute.id).parents(".form-group").removeClass("has-error");}
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
		                  
		                    <div id="hid">
                                <div class="form-group">
                                    <?php $readonly=$model->scenario!="add"?array('class'=>'form-control','readonly'=>'readonly'):array('class'=>'form-control'); ?>
                                    <?php echo $form->labelEx($model,'phone',array('class'=>'col-md-2 control-label',)); ?>
                                    <div class="col-md-8">
                                        <?php echo $form->textField($model,'phone',$readonly); ?>
                                    </div>
                                    <?php echo $form->error($model,'phone'); ?>
                                </div>
                                <div class="form-group">
                                    <?php echo $form->labelEx($model,'password',array('class'=>'col-md-2 control-label',)); ?>
                                    <div class="col-md-8">
                                        <?php echo $form->passwordField($model,'password',array('class'=>'form-control')); ?>
                                    </div>
                                    <?php echo $form->error($model,'password'); ?>
                                </div>
                                <div class="form-group">
                                    <?php echo $form->labelEx($model,'corporate_name',array('class'=>'col-md-2 control-label',)); ?>
                                    <div class="col-md-8">
                                        <?php echo $form->textField($model,'corporate_name',array('class'=>'form-control')); ?>
                                    </div>
                                    <?php echo $form->error($model,'corporate_name'); ?>
                                </div>
                                <div class="form-group">
                                    <?php echo $form->labelEx($model,'corporate_type',array('class'=>'col-md-2 control-label')); ?>
                                    <div class="col-md-8">
                                        <?php echo $form->DropDownList($model,'corporate_type',UserDetailModel::$typeArray,array('class'=>'form-control')); ?>
                                    </div>
                                    <?php echo $form->error($model,'corporate_type'); ?>
                                </div>
                                <div class="actions btn-set" style="margin:20px 0px 0px 185px;">
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
    <!--<script>-->
    <!--    var uploadUrl = '--><?php //echo ImageServiceHandle::uploadUrl();?><!--'-->
    <!--</script>-->
