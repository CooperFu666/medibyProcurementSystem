<div class="row">
    <div class="col-md-12">
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
		                        <?php echo $form->labelEx($model,'名称',array('class'=>'col-md-2 control-label')); ?>
		                        <div class="col-md-8">
		                            <?php echo $form->textField($model,'name',array('class'=>'form-control')); ?>
		                        </div>
		                        <?php echo $form->error($model,'名称'); ?>
		                    </div>
		                    <div class="form-group">
		                        <?php echo $form->labelEx($model,'icon',array('class'=>'col-md-2 control-label')); ?>
		                        <div class="col-md-8">
		                            <?php echo $form->textField($model,'icon',array('class'=>'form-control')); ?>
		                        </div>
		                        <?php echo $form->error($model,'icon'); ?>
		                    </div>
		                    <div class="form-group">
		                        <?php echo $form->labelEx($model,'排序',array('class'=>'col-md-2 control-label')); ?>
		                        <div class="col-md-8">
		                            <?php echo $form->textField($model,'sort',array('class'=>'form-control')); ?>
		                        </div>
		                        <?php echo $form->error($model,'排序'); ?>
		                    </div>
		                    <div class="form-group">
		                        <?php echo $form->labelEx($model,'是否有效',array('class'=>'col-md-2 control-label')); ?>
		                        <div class="col-md-8">
		                            <?php echo $form->checkBox($model,'is_effect',array('class'=>'form-control')); ?>
		                        </div>
		                       <?php echo $form->error($model,'是否有效');?>
		                    </div>
		                    
		                    <input name="RoleNavGroupModel[nav_id]" id="RoleNavGroupModel_nav_id" type="hidden" value="<?php echo $id;?>">
		                    <input name="RoleNavGroupModel[is_delete]" id="RoleNavGroupModel_is_delete" type="hidden" value="0">
		                    
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
