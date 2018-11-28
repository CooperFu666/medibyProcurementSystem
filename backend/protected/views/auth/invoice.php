<?php
$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name' => '审核审核','url'=>array('auth/invoiceAuth')),
    array('name' => '发票审核详情')
);

$this->pageTitle = '发票审核详情';
$this->title = $model->user->phone.'<small>发票审核详情</small>';
?>
<div class="row" xmlns="http://www.w3.org/1999/html">
    <div class="col-md-12">
        <!-- Begin: life time stats -->
        <div class="portlet">
            <div class="portlet-title form-horizontal">
                <div class="caption">
                    <i class="fa fa-sun-o"></i>认证详情
                </div>
            </div>
            <div class="portlet">
                <div class="portlet-body">
                    <div class="portlet green-meadow box">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-cogs"></i><?php echo $model->detail->corporate_name ?>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="row static-info">
                                <div class="col-md-5 name">
                                    公司名称:
                                </div>
                                <div class="col-md-7 value">
                                    <?php echo $model->corporate_name; ?>
                                </div>
                            </div>
                            <div class="row static-info">
                                <div class="col-md-5 name">
                                    注册地址:
                                </div>
                                <div class="col-md-7 value">
                                    <?php echo $model->regaddress; ?>
                                </div>
                            </div>
                            <div class="row static-info">
                                <div class="col-md-5 name">
                                    注册电话:
                                </div>
                                <div class="col-md-7 value">
                                    <?php echo $model->regphone; ?>
                                </div>
                            </div>
                            <div class="row static-info">
                                <div class="col-md-5 name">
                                    开户银行:
                                </div>
                                <div class="col-md-7 value">
                                    <?php echo $model->bank; ?>
                                </div>
                            </div>
                            <div class="row static-info">
                                <div class="col-md-5 name">
                                    银行账户:
                                </div>
                                <div class="col-md-7 value">
                                    <?php echo $model->bank_number; ?>
                                </div>
                            </div>
                            <div class="row static-info">
                                <div class="col-md-5 name">
                                    纳税人识别号:
                                </div>
                                <div class="col-md-7 value">
                                    <?php echo $model->identifier; ?>
                                </div>
                            </div>
                            <div class="row static-info">
                                <div class="col-md-5 name">
                                    备注:
                                </div>
                                <div class="col-md-7 value">
                                    <?php echo $model->remark; ?>
                                </div>
                            </div>
                            <div class="row static-info">
                                <div class="col-md-5 value">
                                    <a class="btn red" href="<?php echo $this->createUrl("auth/invoiceRefuse/id/{$model->id}")?>"><i class="fa fa-reply"></i> 拒绝</a>
                                </div>
                                <div class="col-md-7 value">
                                    <a class="btn blue" href="<?php echo $this->createUrl("auth/invoicePass/id/{$model->id}")?>"><i class="fa fa-thumbs-up"></i> 通过</a>
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