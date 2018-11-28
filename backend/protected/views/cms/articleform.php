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
		                        <?php echo $form->labelEx($model,'title',array('class'=>'col-md-2 control-label')); ?>
		                        <div class="col-md-8">
		                            <?php echo $form->textField($model,'title',array('class'=>'form-control')); ?>
		                        </div>
		                        <?php echo $form->error($model,'title'); ?>
		                    </div>
                            <div class="form-group">
                                <?php echo $form->labelEx($model,'cate_id',array('class'=>'col-md-2 control-label',)); ?>
                                <div class="col-md-8">
                                    <?php echo $form->dropDownList($model,'cate_id',CHtml::listData(ArticleCategoryModel::model()->findAll(), 'id', 'title'),array('prompt' => '选择分类','class'=>'form-control')); ?>
                                </div>
                                <?php echo $form->error($model,'cate_id'); ?>
                            </div>
                            <div class="form-group">
                                <?php echo $form->labelEx($model,'content',array('class'=>'col-md-2 control-label')); ?>
                                <div class="col-md-8">
                                    <?php
                                    $this->widget('application.extensions.baiduUeditor.UeditorWidget',
                                        array(
                                            'id'=>'ArticleModel_content',//容器的id 唯一的[必须配置]
                                            'name'=>'ArticleModel[content]',//post到后台接收的name [必须配置]
                                            'content'=>$model->content,//初始化内容 [可选的]
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
