<?php
$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name' => '优惠券管理','url'=>array('coupon/index')),
    array('name' => '更新优惠券')
);

$this->title = $model->title.'<small>限制设置</small>';
$this->pageTitle = '限制设置';
?>
<div class="page-bar">
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
                                    <?php echo $form->labelEx($model,'brandid',array('class'=>'col-md-2 control-label',)); ?>
                                    <div class="col-md-8">
                                        <?php echo $form->dropDownList($model,'brandid',CHtml::listData(BrandModel::model()->findAll(), 'id', 'title'),array('prompt' => '选择品牌','class'=>'form-control')); ?>
                                    </div>
                                    <?php echo $form->error($model,'brandid'); ?>
                                </div>

                                <div class="form-group">
                                    <?php echo CHtml::label("分类",'c1',array('class'=>'col-md-2 control-label')); ?>
                                    <div class="col-xs-2">
                                        <?php echo CHtml::dropDownList('c1',$categoryInfo?$categoryInfo['categoryType1']['active']:"", CHtml::listData(CategoryModel::model()->findAll('type=:type',array(':type'=>1)), 'id', 'title'),
                                            array(
                                                'prompt' => '选择分类',
                                                'class'=>'form-control',
                                                'ajax' => array(
                                                    'type' => 'POST',
                                                    'url' => $this->createUrl('ajax/category'),
                                                    'data' => array('id'=>'js:this.value','csrf'=>'js:$("input[name=\'csrf\']").val()'),
                                                    'success' => 'function(data) {
                                                                    if(data!=1){
                                                                        $("#c2_box").fadeIn();
                                                                        $("#c2").html(data);
                                                                        $("#c3_box").fadeOut();
                                                                        $("#c3").html("");
                                                                    }else{
                                                                        $("#c2_box").fadeOut();
                                                                        $("#c2").html("");
                                                                        $("#c3_box").fadeOut();
                                                                        $("#c3").html("");
                                                                    }
                                                                }',
                                                )));
                                        ?>
                                    </div>
                                    <div class="col-xs-2" id="c2_box" style="<?php echo !$categoryInfo['categoryType2']?'display: none':"" ?>">
                                        <?php echo CHtml::dropDownList('c2',$categoryInfo['categoryType2']['active']?$categoryInfo['categoryType2']['active']:"", $categoryInfo['categoryType2']['list']?$categoryInfo['categoryType2']['list']:array(),
                                            array(
                                                'prompt' => '选择分类',
                                                'class'=>'form-control',
                                                'ajax' => array(
                                                    'type' => 'POST',
                                                    'url' => $this->createUrl('ajax/category'),
                                                    'data' => array('id'=>'js:this.value','csrf'=>'js:$("input[name=\'csrf\']").val()'),
                                                    'success' => 'function(data) {
                                                                    if(data!=1){
                                                                        $("#c3_box").fadeIn();
                                                                        $("#c3").html(data);
                                                                    }else{
                                                                        $("#c3_box").fadeOut();
                                                                        $("#c3").html("");
                                                                    }
                                                                }',
                                                ))
                                        ) ; ?>
                                    </div>
                                    <div class="col-xs-2" id="c3_box" style="<?php echo !$categoryInfo['categoryType3']?'display: none':"" ?>">
                                        <?php echo CHtml::dropDownList('c3',$categoryInfo['categoryType3']['active']?$categoryInfo['categoryType3']['active']:"", $categoryInfo['categoryType3']['list']?$categoryInfo['categoryType3']['list']:array() ,
                                            array(
                                                    'prompt' => '选择分类',
                                                    'class'=>'form-control',
                                                )
                                        ); ?>
                                    </div>
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
        <!--<script>-->
        <!--    var uploadUrl = '--><?php //echo ImageServiceHandle::uploadUrl();?><!--'-->
        <!--</script>-->
    </div>
</div>
