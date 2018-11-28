<?php
/* @var $this ProjectController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('/site/index')),
    array('name'=>'商品管理'),
    array('name' => '商品列表', 'url' => array('goods/index')),
    array('name'=>'型号/属性设置')
);
$this->pageTitle = '型号/属性设置';
$this->title = $model->title.' <small>型号/属性设置</small>';
?>
<div class="row" xmlns="http://www.w3.org/1999/html">
    <div class="col-md-12">
        <!-- Begin: life time stats -->
        <div class="portlet">
            <div class="portlet-title form-horizontal">
                <div class="caption">
                    <i class="fa fa-sun-o"></i>型号/属性设置
                </div>
            </div>
            <div class="portlet">
                <div class="portlet-body">
                    <div class="tabbable">
                        <ul class="nav nav-tabs nav-tabs-lg">
                            <li class="active">
                                <a href="#tab_1" data-toggle="tab">
                                    型号设置
                                </a>
                            </li>
                            <li>
                                <a href="#tab_2" data-toggle="tab">
                                    属性设置
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div class="portlet">
                                    <div class="portlet-title form-horizontal">
                                        <div class="caption">
                                            <i class="fa fa-paper-plane"></i>型号设置
                                        </div>
                                        <div class="actions">

                                            <a class="btn default yellow-stripe" href="<?php echo Yii::app()->createUrl('goodsVersion/add/id/'.$id)?>">
                                                <i class="fa fa-plus"></i>
                                                <span class="hidden-480">型号添加 </span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="table-container">
                                            <table class="table table-striped table-bordered table-hover" id="datatable_list">
                                                <thead>
                                                <tr role="row" class="heading">
                                                    <th width="50%"> 型号名称 </th>
                                                    <th width="50%"> 操作</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane " id="tab_2">
                                <div class="portlet">
                                    <div class="portlet-title form-horizontal">
                                        <div class="caption">
                                            <i class="fa fa-paper-plane"></i>属性设置（没有属性可不添加）
                                        </div>
                                        <div class="actions">

                                            <button class="btn default yellow-stripe" id="addattr">
                                                <i class="fa fa-plus"></i>
                                                <span class="hidden-480">属性添加 </span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="table-container">
                                            <table class="table table-striped table-bordered table-hover">
                                                <thead>
                                                <tr role="row" class="heading">
                                                    <th width="50%"> 属性名称 </th>
                                                    <th width="50%"> 操作</th>
                                                </tr>
                                                </thead>
                                                <tbody id="attrList">
                                                <?php
                                                    if(isset($model->attr)){
                                                        foreach ($model->attr as $attr){
                                                            echo '<tr role="row" class="odd" id="attrTr'.$attr->id.'">
                                                                    <td>'.$attr->title.'</td>
                                                                    <td><button class="btn btn-xs default btn-editable" onclick="update_attr(\''.$attr->id.'\')"><i class="fa fa-pencil">修改</i></button>
                                                                    <button class="btn btn-xs red default" onclick="delete_attr(\''.$attr->id.'\');"><i class="fa fa-times"></i>删除</button></td>
                                                                    </tr>';
                                                        }
                                                    }
                                                ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <form class="form-horizontal form-row-seperated" id="yw0" method="post">
                                            <div class="actions btn-set">
                                                <input type="hidden" name="publish" value="1" >
                                                <button class="btn green" type="submit"><i class="fa fa-check"></i> 发布</button>
                                                <button class="btn default" type="reset"><i class="fa fa-reply"></i> 重置</button>
                                            </div>
                                        </form>
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


<input type="hidden" name="csrf" value="<?php echo Yii::app()->request->getCsrfToken()?>">
<!--style-->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/js/datatable.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/js/ecommerce-list.js"></script>
<link href="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css" rel="stylesheet" type="text/css"/>
<script>
    jQuery(document).ready(function() {
        var url = '<?php echo $this->createUrl("goodsVersion/index/id/".$id,array('isAjax'=>1))?>';
        var token = $("input[name='csrf']").val();
        EcommerceList.init(url,token);
    });
    var attrAddUrl = "<?php echo $this->createUrl("goodsAttr/add/id/".$id,array('isAjax'=>1))?>";
    var attrUpdateUrl = "<?php echo $this->createUrl("goodsAttr/update/",array('isAjax'=>1))?>";
    var attrDeleteUrl = "<?php echo $this->createUrl("goodsAttr/delete/",array('isAjax'=>1))?>";
    var bindAddUrl = "<?php echo $this->createUrl("ajax/goodsbind/",array('isAjax'=>1))?>";
    var goodsId = <?php echo $id;?>;
</script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/js/goods.js"></script>



