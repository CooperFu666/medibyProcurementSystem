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
		                        <?php echo $form->labelEx($model,'src',array('class'=>'col-md-2 control-label',)); ?>
		                        <div class="col-md-8">
		                        <?php
								    $this->widget('application.extensions.baiduUeditor.UeditorWidget',
								        array(
								            'id'=>'BannerModel_src_box',//容器的id 唯一的[必须配置]
								            'name'=>'BannerModel[src]',//post到后台接收的name [必须配置]
                                            'inputId'=>'BannerModel_src',//post到后台接收的input ID [file image 时必须配置]
                                            'idName'=>'BannerModel[src_id]',
								            'content'=>$model->src,//初始化内容 [可选的]
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
		                        <?php echo $form->error($model,'src'); ?>
		                    </div>
                            <div class="form-group">
                                <?php echo CHtml::label("","",array('class'=>'col-md-2 control-label',)); ?>
                                <div class="col-md-8">
                                    <?php echo CHtml::image($model->src,$model->alt,array('width'=>'50%','id'=>'img')); ?>
                                </div>
                            </div>
                             <div class="form-group">
                                <?php echo $form->labelEx($model,'url',array('class'=>'col-md-2 control-label',)); ?>
                                 <div class="col-md-8">
                                   <?php echo $form->textField($model,'url',array('class'=>'form-control','placeholder'=>"http://www.xxxx.com/help/scurity.html")); ?>
                                </div>
                                <?php echo $form->error($model,'url'); ?>
                            </div>
                            <div class="form-group">
                                <?php echo $form->labelEx($model,'alt',array('class'=>'col-md-2 control-label')); ?>
                                <div class="col-md-8">
                                    <?php echo $form->textField($model,'alt',array('class'=>'form-control')); ?>
                                </div>
                                <?php echo $form->error($model,'alt'); ?>
                            </div>
                            <div class="form-group">
                                <?php echo $form->labelEx($model,'sort',array('class'=>'col-md-2 control-label')); ?>
                                <div class="col-md-8">
                                    <?php echo $form->textField($model,'sort',array('class'=>'form-control')); ?>
                                </div>
                                         <span style="color: red;"> *数值越小越靠前</span>
                                <?php echo $form->error($model,'sort'); ?>
                            </div>

                            <div class="form-group">
                                <?php echo $form->labelEx($model,'是否显示',array('class'=>'col-md-2 control-label')); ?>
                                 <div class="col-md-8" style="margin-top:7px;">
                                    <?php echo $form->radioButtonList($model,'status',array(BannerModel::STATUS_NONE=>'不显示',BannerModel::STATUS_WAP=>'wap显示',BannerModel::STATUS_PC=>'pc显示',BannerModel::STATUS_APP=>'app显示')) ?>
                                </div>
                                <?php echo $form->error($model,'status'); ?>
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
        </div>
        <?php $this->endWidget(); ?>
	</div>
</div>
