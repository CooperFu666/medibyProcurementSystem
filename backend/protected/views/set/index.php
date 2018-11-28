<?php
/* @var $this ProjectController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name' => '系统管理',),
    array('name' => '系统配置', 'url' => array('set/index')),
    array('name'=>'系统配置')
);
$this->pageTitle = '系统配置';
$this->title = '系统管理 <small>系统配置</small>';
?>
<div class="row" xmlns="http://www.w3.org/1999/html">
<div class="col-md-12">
<!-- Begin: life time stats -->
<div class="portlet">
<div class="portlet-title form-horizontal">
    <div class="caption">
        <i class="fa fa-sun-o"></i>系统配置
    </div>
</div>
<div class="portlet">
<div class="portlet-body">
<div class="tabbable">
<ul class="nav nav-tabs nav-tabs-lg">
    <li class="active">
        <a href="#tab_1" data-toggle="tab">
            基础设置</a>
    </li>
</ul>
<div class="tab-content">

    <div class="tab-pane active" id="tab_1">
        <?php echo CHtml::beginForm(Yii::app()->createUrl('set/update'),$method='post',$htmlOptions=array('class'=>"form-horizontal"));?>
        <?php echo CHtml::errorSummary($model); ?>
        <div class="form-body">
          <div class="form">

            <?php foreach($base as $v):?>
                <div class="form-group">
                    <?php echo CHtml::activeLabel($v,$v->china_name,array('class'=>'col-md-1 control-label')); ?>
                    <div class="col-md-9">
                        <input type="text" class="form-control input-lg"  name="ConfModel[<?php echo $v->name;?>]" value="<?php echo $v->value;?>"/>
                    </div>
                </div>
            <?php endforeach;?>

            <?php foreach($model as $v): ?>

                <div class="form-group">
                  <?php echo CHtml::activeLabel($v,$v->china_name,array('class'=>'col-md-1 control-label')); ?>
                  <div class="col-md-9">
                      <?php
                      $this->widget('application.extensions.baiduUeditor.UeditorWidget',
                          array(
                              'id'=>'ConfModel_'.$v->name.'_box',//容器的id 唯一的[必须配置]
                              'name'=>'ConfModel['.$v->name.']',//post到后台接收的name [必须配置]
                              'inputId'=>'ConfModel_'.$v->name,//post到后台接收的input ID [file image 时必须配置]
                              'idName'=>'ConfModels['.$v->name.']',
                              'content'=>$v->value,//初始化内容 [可选的]
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
                </div>
            <?php endforeach;?>




            </div>
        </div>
        <div class="row submit">
            <?php echo CHtml::submitButton('提交',array('class'=>'btn green','style'=>'margin-left:400px;margin-top:35px;')); ?>
        </div>
        <?php echo CHtml::endForm();?>
    </div>

</div>
</div>
</div>
<!-- End: life time stats -->
</div>
</div>
</div>
</div>
<input type="hidden" name="csrf" value="<?php echo Yii::app()->request->getCsrfToken()?>">
<!--style-->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/js/datatable.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/js/ecommerce-list.js"></script>
<link href="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css" rel="stylesheet" type="text/css"/>
<script>
    jQuery(document).ready(function() {
        /* var url = '< ?php echo $this->createUrl("index",array('isAjax'=>1))?>';
         var token = $("input[name='csrf']").val();
         EcommerceList.init(url,token);*/
    });
    $().ready(function(){
        $('#group').click(function(){
            if($(this).attr('checked')=='checked'){
                $('#select_group').show();
            }else{
                $('#selelct_group').hide();
            }
        })
    })
</script>

