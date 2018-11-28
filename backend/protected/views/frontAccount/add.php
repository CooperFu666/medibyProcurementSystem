
<?php
/* @var $this CategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name' => '账号管理', 'url' => array('frontAccount/index')),
    array('name'=>'账号管理', 'url' => array('frontAccount/index')),
    array('name'=>'新增角色'),
);
$this->pageTitle = '账号管理';
$this->title = '账号管理<small>账号管理</small>';
?>
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <?php $form=$this->beginWidget('CActiveForm', array(
            'enableAjaxValidation'=>false,
            'enableClientValidation' => true,
            'htmlOptions'=>array('class'=>'form-horizontal form-row-seperated',"enctype" => "multipart/form-data"),
        )); ?>
        <style>
            .table tbody tr td{border-top: 0px;}
            .table{width: 350px;margin: 0 auto 0 auto;}
        </style>
            <table class="table">
                <tr>
                    <td>账号（登录名）：</td>
                    <td><?php echo $form->TextField($model,'username', ['disabled' => !empty($userId) ? 'disabled' : '']); ?></td>
                </tr>
                <tr>
                    <td>真实姓名：</td>
                    <td><?php echo $form->TextField($model, 'nickname',['disabled' => !empty($userId) ? 'disabled' : '']); ?></td>
                </tr>
                <tr>
                    <td>角色：</td>
                    <td>
<!--                        --><?php //echo $form->DropDownList($model,'role_name', $roleList, ['value' => !empty($userId) && isset($model->front_role->role_name) ? $model->front_role->role_name : '']); ?>
                        <select name="UserModel[role_name]" id="UserModel_role_name">
                            <?php foreach ($roleList as $key => $value){ ?>
                                <?php if(!empty($model->id) && isset($model->front_role->role_name) && $model->front_role->role_name == $value){ ?>
                                    <option selected value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                <?php } else{ ?>
                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>联系电话：</td>
                    <td><?php echo $form->TextField($model,'phone'); ?></td>
                </tr>
            </table>
        <br/>
        <br/>
            <div><center><button class="btn green" type="submit"><i class="fa fa-check"></i> 保存</button></center></div>
        <?php $this->endWidget(); ?>
    </div>
</div>
<input type="hidden" name="csrf" value="<?php echo Yii::app()->request->getCsrfToken()?>">
<!-- END PAGE CONTENT-->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl;?>/static/js/datatable.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/js/ecommerce-list.js"></script>
<!--style-->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css" rel="stylesheet" type="text/css"/>
<script>
    jQuery(document).ready(function() {
        var url = '<?php echo $this->createUrl("index",array('isAjax'=>1))?>';
        var token = $("input[name='csrf']").val();
        EcommerceList.init(url,token);
    });

</script>