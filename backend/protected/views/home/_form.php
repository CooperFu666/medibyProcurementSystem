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
                            <div class="form-group">
                                <?php echo $form->labelEx($model,'title',array('class'=>'col-md-2 control-label',)); ?>
                                <div class="col-md-8">
                                    <?php echo $form->textField($model,'title',array('class'=>'form-control')); ?>
                                </div>
                                <?php echo $form->error($model,'title'); ?>
                            </div>
                            <div class="form-group">
                                <?php echo $form->labelEx($model,'cid',array('class'=>'col-md-2 control-label',)); ?>
                                <div class="col-md-8">
                                    <?php echo $form->dropDownList($model,'cid',CHtml::listData(CategoryModel::model()->findAll(), 'id', 'title'),array('prompt' => '选择分类','class'=>'form-control')); ?>
                                </div>
                                <?php echo $form->error($model,'cid'); ?>
                            </div>
                            <div class="form-group">
                                <?php echo $form->labelEx($model,'src',array('class'=>'col-md-2 control-label',)); ?>
                                <div class="col-md-8">
                                    <?php
                                    $this->widget('application.extensions.baiduUeditor.UeditorWidget',
                                        array(
                                            'id'=>'HomeModel_src_box',//容器的id 唯一的[必须配置]
                                            'name'=>'HomeModel[src]',//post到后台接收的name [必须配置]
                                            'inputId'=>'HomeModel_src',//post到后台接收的input ID [file image 时必须配置]
                                            'idName'=>'HomeModel[src_id]',
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
                                    ?>(如不需要可不选着)
                                </div>
                                <?php echo $form->error($model,'src'); ?>
                            </div>
                            <!--<div class="form-group">
                                <?php /*echo CHtml::label("","",array('class'=>'col-md-2 control-label',)); */?>
                                <div class="col-md-8">
                                    <?php /*echo CHtml::image($model->src,array('width'=>'50%','id'=>'img')); */?>
                                </div>
                            </div>-->
                            <div class="form-group">
                                <?php echo $form->labelEx($model,'url',array('class'=>'col-md-2 control-label',)); ?>
                                <div class="col-md-8">
                                    <?php echo $form->textField($model,'url',array('class'=>'form-control','placeholder'=>"http://www.xxxx.com/help/scurity.html")); ?>
                                </div>
                                <?php echo $form->error($model,'url'); ?>
                            </div>

                            <div class="form-group">
                                <?php echo $form->labelEx($model,'sort',array('class'=>'col-md-2 control-label')); ?>
                                <div class="col-md-8">
                                    <?php echo $form->textField($model,'sort',array('class'=>'form-control')); ?>
                                </div>
                                <span style="color: red;"> *数值越小越靠前</span>
                                <?php echo $form->error($model,'sort'); ?>
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
