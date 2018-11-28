<?php
$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name'=>'订单列表','url'=>array('order/index')),
    array('name'=>'订单详情')
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
                    <i class="fa fa-paper-plane"></i>订单详情
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-container">
                    <table class="table table-striped table-bordered table-hover" id="datatable_list">
                        <tbody>
                            <tr>
                                <td width="10%">订单号：</td>
                                <td><?php echo $order_info['deal_number']; ?></td>
                            </tr>
                            <tr>
                                <td>用户名：</td>
                                <td><?php echo $user_info['phone']; ?></td>
                            </tr>
                            <tr>
                                <td>营业执照：</td>
                                <td>
                                    <?php if (!empty($user_auth_list)) { ?>
                                        <?php foreach ($user_auth_list as $key=>$value) { ?>
                                            <?php echo $value['type']==UserAuthModel::TYPE_BUS? "许可状态：" . UserAuthModel::$statusArray[$value['status']] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;许可图片：<img height='250' src='{$value['images']}'>" : ""; ?>
                                        <?php } ?>
                                    <?php }else { ?>
                                    许可状态：未提交                                                                                                                                                                                               </td>
                                <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td>医疗器械许可证：</td>
                                <td>
                                    <?php if (!empty($user_auth_list)) { ?>
                                        <?php foreach ($user_auth_list as $key=>$value) { ?>
                                            <?php echo $value['type']==UserAuthModel::TYPE_LIC? "许可状态：" . UserAuthModel::$statusArray[$value['status']] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;许可图片：<img height='250' src='{$value['images']}'>" : ""; ?>
                                        <?php } ?>
                                    <?php }else { ?>
                                        许可状态：未提交
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php foreach ($order_express_list as $key=>$value) { ?>
                                <tr>
                                    <td><?php echo $value->type == OrderExpressModel::TYPE_GOODS? "商品": "发票"; ?>收货信息：</td>
                                    <td>
                                        姓名：<?php echo $value->receiver; ?><br/>
                                        电话：<?php echo $value->phone; ?><br/>
                                        收货地址：<?php echo $value->shipping_address; ?><br/>
                                    </td>
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
                                <td>优惠券</td>
                                <td><?php
                                    if (!empty($order_info)) {
                                        if ($order_info) {
                                            echo "优惠前商品总价:" . sprintf('%.2f', ($order_info['price']+$order_info['coupon_price'])) . "元&nbsp;&nbsp;&nbsp;优惠后商品总价：" . sprintf("%.2f", $order_info['price']) . "元";
                                        }else {
                                            echo "未使用优惠";
                                        }
                                    }
                                    ?></td>
                            </tr>
                        </tbody>
                    </table>
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