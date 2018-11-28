<?php
$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name'=>'订单列表','url'=>array('order/index')),
    array('name'=>'开始发货')
);
$this->title = '订单列表<small>订单列表</small>';
$this->pageTitle = "-订单列表";
?>
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- Begin: life time stats -->
        <div class="portlet">
            <div class="portlet-title form-horizontal">
                <div class="caption">
                    <i class="fa fa-paper-plane"></i>开始发货
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-container">
                    <form action="./ship" method="post">
                        <input name="orderId" value="<?php echo $_GET['order_id']; ?>" hidden>
                        <table class="table table-striped table-bordered table-hover" id="datatable_list">
                            <tbody>
                                <?php foreach ($order_express_list as $value) { ?>
                                    <tr>
                                        <td><?php echo $value['type'] == OrderExpressModel::TYPE_GOODS? "商品": "发票"; ?>收货地址：</td>
                                        <td><?php echo $value['shipping_address']; ?></td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td>发票信息</td>
                                    <td>
                                        用户名：<?php echo $invoice['user']; ?><br/>
                                        公司名称：<?php echo $invoice['corporate_name']; ?><br/>
                                        注册地址：<?php echo $invoice['regaddress']; ?><br/>
                                        注册电话：<?php echo $invoice['regphone']; ?><br/>
                                        开户银行：<?php echo $invoice['bank']; ?><br/>
                                        银行账户：<?php echo $invoice['bank_number']; ?><br/>
                                        认证状态：<?php echo $invoice['auth_status']; ?><br/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>产品列表：</td>
                                    <td><?php
                                        if (!empty($goods_info_list)) {
                                            foreach ($goods_info_list as $key => $value) {
                                                echo
                                                    "<div>商品图片：<img width=150 src='" . $value['goods_image'] . "'>" . "&nbsp;&nbsp;&nbsp;" .
                                                    "商品名称：" . $value['goods_title'] . "&nbsp;&nbsp;&nbsp;" .
                                                    "商品型号：" . $value['goods_version_title'] . "&nbsp;&nbsp;&nbsp;" .
                                                    "商品属性：" . $value['goods_attr_title'] . "&nbsp;&nbsp;&nbsp;" .
                                                    "商品单价：" . sprintf("%.2f", $value['goods_price']) . "元&nbsp;&nbsp;&nbsp;" .
                                                    "购买数量：" . $value['count'] . "&nbsp;&nbsp;&nbsp;" .
                                                    "</div>"
                                                ;
                                                echo "<br/>";
                                            }
                                        }
                                        ?></td>
                                </tr>
                                <tr>
                                    <td width="10%">快递公司：</td>
                                    <td><select name="express">
                                            <?php foreach ($express_list as $key => $value){ ?>
                                            <option value="<?php echo $value['id']; ?>"><?php echo $value['title']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>商品快递单号：</td>
                                    <td><input name="express_number_goods" value=""></td>
                                </tr>
                                <tr>
                                    <td>发票快递单号：</td>
                                    <td><input name="express_number_invoice" value=""></td>
                                </tr>
                            </tbody>
                        </table>
                        <button class="btn btn-sm green margin-bottom"><i class="fa">提交</i></button>
                    </form>
                </div>
            </div>
        </div>
        <!-- End: life time stats -->
    </div>
</div>
<input type="hidden" name="csrf" value="<?php echo Yii::app()->request->getCsrfToken()?>">
<!-- END PAGE CONTENT-->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/js/datatable.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/js/ecommerce-list.js"></script>
<!--style-->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css" rel="stylesheet" type="text/css"/>