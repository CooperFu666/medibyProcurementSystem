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
    <div class="row" xmlns="http://www.w3.org/1999/html">
        <div class="col-md-12">
            <!-- Begin: life time stats -->
            <div class="portlet">
                <div class="portlet-title form-horizontal">
                    <div class="caption">
                        <i class="fa fa-sun-o"></i>商品配置
                    </div>
                </div>
                <div class="portlet">
                    <div class="portlet-body">
                        <div class="tabbable">
                            <ul class="nav nav-tabs nav-tabs-lg">
                                <li class="active">
                                    <a href="#tab_1" data-toggle="tab">
                                        基础设置
                                    </a>
                                </li>
                                <li>
                                    <a href="#tab_2" data-toggle="tab">
                                        分类设置
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1">
                                    <div class="form-body">
                                        <div class="form">
                                            <div class="form-group">
                                                <?php echo $form->labelEx($model,'title',array('class'=>'col-md-2 control-label',)); ?>
                                                <div class="col-md-8">
                                                    <?php echo $form->textField($model,'title',array('class'=>'form-control')); ?>
                                                </div>
                                                <?php echo $form->error($model,'title'); ?>
                                            </div>
                                            <div class="form-group">
                                                <?php echo $form->labelEx($model,'brandid',array('class'=>'col-md-2 control-label',)); ?>
                                                <div class="col-md-8">
                                                    <?php echo $form->dropDownList($model,'brandid',CHtml::listData(BrandModel::model()->findAll(), 'id', 'title'),array('prompt' => '选择品牌','class'=>'form-control')); ?>
                                                </div>
                                                <?php echo $form->error($model,'brandid'); ?>
                                            </div>
                                            <div class="form-group">
                                                <?php echo $form->labelEx($model,'origin',array('class'=>'col-md-2 control-label',)); ?>
                                                <div class="col-md-8">
                                                    <?php echo $form->textField($model,'origin',array('class'=>'form-control')); ?>
                                                </div>
                                                <?php echo $form->error($model,'origin'); ?>
                                            </div>
                                            <div class="form-group">
                                                <?php echo $form->labelEx($model,'tag',array('class'=>'col-md-2 control-label',)); ?>
                                                <div class="col-md-8">
                                                    <?php echo $form->textField($model,'tag',array('class'=>'form-control','placeholder'=>"(请使用英文“,”隔开)")); ?>
                                                </div>
                                                <?php echo $form->error($model,'tag'); ?>
                                            </div>
                                            <div class="form-group">
                                                <?php echo $form->labelEx($model,'type',array('class'=>'col-md-2 control-label',)); ?>
                                                <div class="col-md-8">
                                                    <?php echo $form->dropDownList($model,'type',GoodsModel::$typeArray,array('class'=>'form-control')); ?>
                                                </div>
                                                <?php echo $form->error($model,'type'); ?>
                                            </div>
                                            <div class="form-group">
                                                <?php echo $form->labelEx($model,'is_new',array('class'=>'col-md-2 control-label',)); ?>
                                                <div class="col-md-8">
                                                    <?php echo $form->checkBox($model,'is_new',array('class'=>'form-control')); ?>
                                                </div>
                                                <?php echo $form->error($model,'is_new'); ?>
                                            </div>
                                            <div class="form-group">
                                                <?php echo $form->labelEx($model,'is_hot',array('class'=>'col-md-2 control-label',)); ?>
                                                <div class="col-md-8">
                                                    <?php echo $form->checkBox($model,'is_hot',array('class'=>'form-control')); ?>
                                                </div>
                                                <?php echo $form->error($model,'is_hot'); ?>
                                            </div>
                                            <div class="form-group">
                                                <?php echo $form->labelEx($model,'is_search',array('class'=>'col-md-2 control-label',)); ?>
                                                <div class="col-md-8">
                                                    <?php echo $form->checkBox($model,'is_search',array('class'=>'form-control')); ?>
                                                </div>
                                                <?php echo $form->error($model,'is_search'); ?>
                                            </div>
                                            <div class="form-group">
                                                <?php echo $form->labelEx($model,'info',array('class'=>'col-md-2 control-label')); ?>
                                                <div class="col-md-8">
                                                    <?php
                                                    $this->widget('application.extensions.baiduUeditor.UeditorWidget',
                                                        array(
                                                            'id'=>'GoodsModel_content',//容器的id 唯一的[必须配置]
                                                            'name'=>'GoodsModel[info]',//post到后台接收的name [必须配置]
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

                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane " id="tab_2">
                                    <div class="form-body">
                                        <div class="form">
                                            <div class="form-group">
                                                <?php echo CHtml::label("选择分类",'c1',array('class'=>'col-md-2 control-label')); ?>
                                                <div class="col-xs-2">
                                                    <?php echo CHtml::dropDownList('c1',"", CHtml::listData(CategoryModel::model()->findAll('type=:type',array(':type'=>1)), 'id', 'title'),
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
                                                <div class="col-xs-2" id="c2_box" style="display: none">
                                                    <?php echo CHtml::dropDownList('c2',"", array(),
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
                                                <div class="col-xs-2" id="c3_box" style="display: none">
                                                    <?php echo CHtml::dropDownList('c3',"", array() , array('prompt' => '选择分类','class'=>'form-control',)); ?>
                                                </div>
                                            </div>

                                            <div class="actions btn-set" style="margin:20px 0px 0px 185px;">
                                                <button class="btn blue" type="button" id="add_category"><i class="fa fa-arrow-down"></i> 加入分类</button>
                                            </div>

                                            <div class="form-group" style="margin:20px 0px 0px 185px;">
                                                <table class="table table-striped table-bordered table-hover" id="category_list">
                                                    <thead>
                                                        <tr role="row" class="heading">
                                                            <th width="25%"> 一级分类 </th>
                                                            <th width="25%"> 二级分类 </th>
                                                            <th width="25%"> 三级分类 </th>
                                                            <th width="25%"> 操作 </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="category_tbody">
                                                        <?php
                                                        if(isset($categoryTree)):
                                                            foreach ($categoryTree as $categoryType1):?>
                                                               <tr role="row" class="odd">
                                                                    <td><?php echo $categoryType1['title']?><input id="c1_<?php echo $categoryType1['id'] ?>" type="hidden" value="<?php echo $categoryType1['id'] ?>" name="category_bind[]"></td>
                                                                    <td>
                                                                        <table class="table table-striped table-bordered table-hover">
                                                                        <?php foreach ($categoryType1['child'] as $categoryType2):?>
                                                                            <tr role="row" class="odd">
                                                                                <td><?php echo $categoryType2['title']?><input id="c2_<?php echo $categoryType1['id'] ?>_<?php echo $categoryType2['id'] ?>" type="hidden" value="<?php echo $categoryType2['id'] ?>" name="category_bind[]"></td>
                                                                                <td>
                                                                                    <table class="table table-striped table-bordered table-hover">
                                                                                        <?php foreach ($categoryType2['child'] as $categoryType3):?>
                                                                                            <tr role="row" class="odd">
                                                                                                <td><?php echo $categoryType3['title']?><input id="c2_<?php echo $categoryType1['id'] ?>_<?php echo $categoryType2['id'] ?>_<?php echo $categoryType3['id'] ?>" type="hidden" value="<?php echo $categoryType3['id'] ?>" name="category_bind[]"></td>
                                                                                            </tr>
                                                                                        <?php endforeach;?>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        <?php endforeach;?>
                                                                        </table>
                                                                    </td>
                                                                    <td>（原分类）</td>
                                                                    <td><button class="btn btn-xs red default" onclick="delete_c('c1_<?php echo $categoryType1['id'] ?>');"><i class="fa fa-times"></i>删除</button></td>
                                                               </tr>
                                                            <?php
                                                            endforeach;
                                                        endif;
                                                        ?>
                                                    </tbody>
                                                </table>
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
                    <!-- End: life time stats -->
                </div>


            </div>
        </div>
    </div>
<?php $this->endWidget(); ?>



<input type="hidden" name="csrf" value="<?php echo Yii::app()->request->getCsrfToken()?>">
<!--style-->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/js/datatable.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/js/ecommerce-list.js"></script>
<link href="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/js/goods.js"></script>

