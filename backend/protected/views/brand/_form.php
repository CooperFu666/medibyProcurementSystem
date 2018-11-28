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
                                    <?php echo $form->labelEx($model,'title',array('class'=>'col-md-2 control-label',)); ?>
                                    <div class="col-md-8">
                                        <?php echo $form->textField($model,'title',array('class'=>'form-control')); ?>
                                    </div>
                                    <?php echo $form->error($model,'title'); ?>
                                </div>
                                <div class="form-group">
                                    <?php echo $form->labelEx($model,'images',array('class'=>'col-md-2 control-label',)); ?>
                                    <div class="col-md-8">
                                    <?php
                                        $this->widget('application.extensions.baiduUeditor.UeditorWidget',
                                            array(
                                                'id'=>'BrandModel_images_box',//容器的id 唯一的[必须配置]
                                                'name'=>'BrandModel[images]',//post到后台接收的name [必须配置]
                                                'inputId'=>'BrandModel_images',//post到后台接收的input ID [file image 时必须配置]
                                                'idName'=>'BrandModel[images_id]',
                                                'content'=>$model->images,//初始化内容 [可选的]
                                                'type'=>'image',
                                                'class'=>'form-control',
                                                'btnClass'=>'btn green',
                                                //'uploadUrl'=>BackendImageService::uploadUrl(array(array('width'=>120,'height'=>80))),
                                                'config'=>array(
                                                    'lang'=>'zh-cn'
                                                )
                                            )
                                        );
                                        ?>
                                    </div>
                                    <?php echo $form->error($model,'images'); ?>
                                </div>

                                <div class="form-group">
                                    <?php echo $form->labelEx($model,'info',array('class'=>'col-md-2 control-label')); ?>
                                    <div class="col-md-8">
                                        <?php
                                        $this->widget('application.extensions.baiduUeditor.UeditorWidget',
                                            array(
                                                'id'=>'BrandModel_content',//容器的id 唯一的[必须配置]
                                                'name'=>'BrandModel[info]',//post到后台接收的name [必须配置]
                                                'content'=>$model->info,//初始化内容 [可选的]
                                                'type'=>'textarea',
                                                'config'=>array(
                                                    'lang'=>'zh-cn'
                                                )
                                            )
                                        );
                                        ?>
                                    </div>
                                    <?php echo $form->error($model,'info'); ?>
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
