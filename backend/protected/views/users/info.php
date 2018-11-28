<?php
$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name' => '用户管理','url'=>array('users/index')),
    array('name' => '用户详情')
);

$this->pageTitle = '用户详情';
$this->title = $model->phone.'<small>用户详情</small>';
?>
<div class="row" xmlns="http://www.w3.org/1999/html">
    <div class="col-md-12">
        <!-- Begin: life time stats -->
        <div class="portlet">
            <div class="portlet-title form-horizontal">
                <div class="caption">
                    <i class="fa fa-sun-o"></i>用户详情
                </div>
            </div>
            <div class="portlet">
                <div class="portlet-body">
                    <div class="tabbable">
                        <ul class="nav nav-tabs nav-tabs-lg">
                            <li class="active">
                                <a href="#tab_1" data-toggle="tab">
                                    用户信息
                                </a>
                            </li>
                            <li>
                                <a href="#tab_2" data-toggle="tab">
                                    收货地址
                                </a>
                            </li>
                            <li>
                                <a href="#tab_3" data-toggle="tab">
                                    发票信息
                                </a>
                            </li>
                            <li>
                                <a href="#tab_4" data-toggle="tab">
                                    认证信息
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="portlet blue-hoki box">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="fa fa-cogs"></i>用户信息
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="row static-info">
                                                    <div class="col-md-5 name">
                                                        手机号:
                                                    </div>
                                                    <div class="col-md-7 value">
                                                        <?php echo $model->phone?>
                                                    </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name">
                                                        昵称:
                                                    </div>
                                                    <div class="col-md-7 value">
                                                        <?php echo $model->nickname?>
                                                    </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name">
                                                        邮箱:
                                                    </div>
                                                    <div class="col-md-7 value">
                                                        <?php echo $model->email?>
                                                        <span class="label label-info label-sm">
                                                            <?php
                                                            switch($model->emailstatus){
                                                                case UserModel::EMAIL_STATUS_NONE:
                                                                    $emailstatus="未认证";
                                                                    break;
                                                                case  UserModel::EMAIL_STATUS_SUCCESS:
                                                                    $emailstatus="已认证";
                                                                    break;
                                                            }
                                                            echo $emailstatus;
                                                            ?>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name">
                                                        状态:
                                                    </div>
                                                    <div class="col-md-7 value">
                                                        <?php
                                                        switch($model->status){
                                                            case UserModel::STATUS_NORMAL:
                                                                $status="<span class='label label-success'>正常</span>";
                                                                break;
                                                            case  UserModel::STATUS_DISABLE:
                                                                $status="<span class='label label-warning'>禁用</span>";
                                                                break;
                                                        }
                                                        echo $status;
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name">
                                                        类型:
                                                    </div>
                                                    <div class="col-md-7 value">
                                                        <?php
                                                        switch($model->type){
                                                            case UserModel::TYPE_ONE:
                                                                $type="一类";
                                                                break;
                                                            case  UserModel::TYPE_TWO:
                                                                $type="二类";
                                                                break;
                                                            case  UserModel::TYPE_THREE:
                                                                $type="三类";
                                                                break;
                                                            default:
                                                                $type="未认证";
                                                        }
                                                        echo $type;
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name">
                                                        注册时间:
                                                    </div>
                                                    <div class="col-md-7 value">
                                                        <?php echo $model->regtime ? date('Y-m-d H:i:s',$model->regtime):''?>
                                                    </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name">
                                                        最后登陆时间:
                                                    </div>
                                                    <div class="col-md-7 value">
                                                        <?php echo $model->lasttime ? date('Y-m-d H:i:s',$model->lasttime):''?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="portlet blue-hoki box">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="fa fa-cogs"></i>公司信息
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="row static-info">
                                                    <div class="col-md-5 name">
                                                        公司名称:
                                                    </div>
                                                    <div class="col-md-7 value">
                                                        <?php echo $model->detail->corporate_name; ?>
                                                    </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name">
                                                        公司代码:
                                                    </div>
                                                    <div class="col-md-7 value">
                                                        <?php echo $model->detail->corporate_code; ?>
                                                    </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name">
                                                        地址:
                                                    </div>
                                                    <div class="col-md-7 value">
                                                        <?php echo $model->detail->province.$model->detail->city.$model->detail->area.$model->detail->address; ?>
                                                    </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name">
                                                        公司性质:
                                                    </div>
                                                    <div class="col-md-7 value">
                                                        <?php
                                                        switch($model->detail->corporate_type){
                                                            case UserDetailModel::TYPE_DISTRIBUTOR:
                                                                $corporate_type="医疗器械分销商";
                                                                break;
                                                            case UserDetailModel::TYPE_PUBHOSPITAL:
                                                                $corporate_type="公立医院";
                                                                break;
                                                            case UserDetailModel::TYPE_PRIVHOSPITAL:
                                                                $corporate_type="民营医院";
                                                                break;
                                                            case UserDetailModel::TYPE_UNIVERSITIE:
                                                                $corporate_type="高校";
                                                                break;
                                                            case UserDetailModel::TYPE_MEDICAL:
                                                                $corporate_type="体检机构";
                                                                break;
                                                            case UserDetailModel::TYPE_CLINIC:
                                                                $corporate_type="诊所";
                                                                break;
                                                            case UserDetailModel::TYPE_OTHER:
                                                                $corporate_type="其他";
                                                                break;
                                                        }
                                                        echo $corporate_type;
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name">
                                                        联系人:
                                                    </div>
                                                    <div class="col-md-7 value">
                                                        <?php echo $model->detail->contact_name; ?>
                                                    </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name">
                                                        联系电话:
                                                    </div>
                                                    <div class="col-md-7 value">
                                                        <?php echo $model->detail->contact_phone; ?>
                                                    </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name">
                                                        联系邮箱:
                                                    </div>
                                                    <div class="col-md-7 value">
                                                        <?php echo $model->detail->contact_email; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane " id="tab_2">
                                <div class="portlet">
                                    <div class="portlet-title form-horizontal">
                                        <div class="caption">
                                            <i class="fa fa-paper-plane"></i>收货地址
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="row">
                                            <?php foreach ($model->address as $obj):?>
                                            <div class="col-md-6 col-sm-12">
                                                <div class="portlet green-meadow box">
                                                    <div class="portlet-title">
                                                        <div class="caption">
                                                            <i class="fa fa-cogs"></i><?php echo $obj->name ?>
                                                        </div>
                                                    </div>
                                                    <div class="portlet-body">
                                                        <div class="row static-info">
                                                            <div class="col-md-5 name">
                                                                收货人姓名:
                                                            </div>
                                                            <div class="col-md-7 value">
                                                                <?php echo $obj->name; ?>
                                                            </div>
                                                        </div>
                                                        <div class="row static-info">
                                                            <div class="col-md-5 name">
                                                                收货电话:
                                                            </div>
                                                            <div class="col-md-7 value">
                                                                <?php echo $obj->phone; ?>
                                                            </div>
                                                        </div>
                                                        <div class="row static-info">
                                                            <div class="col-md-5 name">
                                                                地址:
                                                            </div>
                                                            <div class="col-md-7 value">
                                                                <?php echo $obj->province.$obj->city.$obj->area.$obj->address; ?>
                                                            </div>
                                                        </div>
                                                        <!--<div class="row static-info">
                                                            <div class="col-md-5 name">
                                                                固定电话:
                                                            </div>
                                                            <div class="col-md-7 value">
                                                                <?php /*echo $obj->telnumber; */?>
                                                            </div>
                                                        </div>-->
                                                        <div class="row static-info">
                                                            <div class="col-md-5 name">
                                                                邮政编码:
                                                            </div>
                                                            <div class="col-md-7 value">
                                                                <?php echo $obj->zip_code; ?>
                                                            </div>
                                                        </div>
                                                        <div class="row static-info">
                                                            <div class="col-md-5 name">
                                                                备注:
                                                            </div>
                                                            <div class="col-md-7 value">
                                                                <?php echo $obj->remark; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endforeach;?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane " id="tab_3">
                                <div class="portlet">
                                    <div class="portlet-title form-horizontal">
                                        <div class="caption">
                                            <i class="fa fa-paper-plane"></i>发票信息
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="row">
                                            <?php foreach ($model->invoice as $obj):?>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="portlet green-meadow box">
                                                        <div class="portlet-title">
                                                            <div class="caption">
                                                                <i class="fa fa-cogs"></i><?php echo $obj->corporate_name ?>
                                                            </div>
                                                        </div>
                                                        <div class="portlet-body">
                                                            <div class="row static-info">
                                                                <div class="col-md-5 name">
                                                                    公司名称:
                                                                </div>
                                                                <div class="col-md-7 value">
                                                                    <?php echo $obj->corporate_name; ?>
                                                                </div>
                                                            </div>
                                                            <div class="row static-info">
                                                                <div class="col-md-5 name">
                                                                    注册地址:
                                                                </div>
                                                                <div class="col-md-7 value">
                                                                    <?php echo $obj->regaddress; ?>
                                                                </div>
                                                            </div>
                                                            <div class="row static-info">
                                                                <div class="col-md-5 name">
                                                                    注册电话:
                                                                </div>
                                                                <div class="col-md-7 value">
                                                                    <?php echo $obj->regphone; ?>
                                                                </div>
                                                            </div>
                                                            <div class="row static-info">
                                                                <div class="col-md-5 name">
                                                                    开户银行:
                                                                </div>
                                                                <div class="col-md-7 value">
                                                                    <?php echo $obj->bank; ?>
                                                                </div>
                                                            </div>
                                                            <div class="row static-info">
                                                                <div class="col-md-5 name">
                                                                    银行账户:
                                                                </div>
                                                                <div class="col-md-7 value">
                                                                    <?php echo $obj->bank_number; ?>
                                                                </div>
                                                            </div>
                                                            <div class="row static-info">
                                                                <div class="col-md-5 name">
                                                                    纳税人识别号:
                                                                </div>
                                                                <div class="col-md-7 value">
                                                                    <?php echo $obj->identifier; ?>
                                                                </div>
                                                            </div>
                                                            <div class="row static-info">
                                                                <div class="col-md-5 name">
                                                                    备注:
                                                                </div>
                                                                <div class="col-md-7 value">
                                                                    <?php echo $obj->remark; ?>
                                                                </div>
                                                            </div>
                                                            <div class="row static-info">
                                                                <div class="col-md-5 name">
                                                                    状态:
                                                                </div>
                                                                <div class="col-md-7 value">
                                                                    <?php
                                                                    switch($obj->auth_status){
                                                                        case UserInvoiceModel::AUTH_STATUS_NONE:
                                                                            $status="<span class='label label-info'>未认证</span>";
                                                                            break;
                                                                        case  UserInvoiceModel::AUTH_STATUS_SUCCESS:
                                                                            $status="<span class='label label-success'>已认证</span>";
                                                                            break;
                                                                        case  UserInvoiceModel::AUTH_STATUS_FAIL:
                                                                            $status="<span class='label label-danger'>认证失败</span>";
                                                                            break;
                                                                    }
                                                                    echo $status;
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach;?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane " id="tab_4">
                                <div class="portlet">
                                    <div class="portlet-title form-horizontal">
                                        <div class="caption">
                                            <i class="fa fa-paper-plane"></i>认证资料
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <?php foreach ($model->auth as $obj):?>
                                            <div class="portlet green-meadow box">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="fa fa-cogs"></i>
                                                        <?php
                                                        switch($obj->type){
                                                            case UserAuthModel::TYPE_BUS:
                                                                $auth_type="营业执照";
                                                                break;
                                                            case UserAuthModel::TYPE_LIC:
                                                                $auth_type="医疗器械许可证";
                                                                break;
                                                        }
                                                        echo $auth_type;
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="row static-info">
                                                        <div class="col-md-5 name">
                                                            操作时间:
                                                        </div>
                                                        <div class="col-md-7 value">
                                                            <?php echo $obj->updated_at ? date('Y-m-d H:i:s',$obj->updated_at):''?>
                                                        </div>
                                                    </div>
                                                    <div class="row static-info">
                                                        <div class="col-md-5 name">
                                                            认证状态:
                                                        </div>
                                                        <div class="col-md-7 value">
                                                            <?php
                                                            switch($obj->status){
                                                                case UserAuthModel::STATUS_ING:
                                                                    $auth_status="认证中";
                                                                    break;
                                                                case UserAuthModel::STATUS_FAIL:
                                                                    $auth_status="认证失败";
                                                                    break;
                                                                case UserAuthModel::STATUS_SUCCESS:
                                                                    $auth_status="认证完成";
                                                                    break;
                                                            }
                                                            echo $auth_status;
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="row static-info">
                                                        <div class="col-md-5 name">
                                                            信息:
                                                        </div>
                                                        <div class="col-md-7 value">
                                                            <?php echo $obj->message; ?>
                                                        </div>
                                                    </div>
                                                    <div class="row static-info">
                                                        <div class="col-md-5 name">
                                                            资料:
                                                        </div>
                                                        <div class="col-md-7 value">
                                                            <?php
                                                            $images = explode(',', $obj->images);
                                                            foreach ($images as $image){
                                                                echo "<img src='$image'/>";
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        <?php endforeach;?>
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