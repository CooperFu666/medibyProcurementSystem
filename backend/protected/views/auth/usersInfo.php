<?php
$this->breadcrumbs=array(
    array('name' => '首页', 'url' => array('site/index')),
    array('name' => '审核认证','url'=>array('auth/index')),
    array('name' => '认证详情')
);

$this->pageTitle = '认证详情';
$this->title = $model->user->phone.'<small>认证详情</small>';
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
                                    <i class="fa fa-cogs"></i>
                                    <?php
                                    switch($model->type){
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
                                        <?php echo $model->created_at ? date('Y-m-d H:i:s',$model->created_at):''?>
                                    </div>
                                </div>
                                <div class="row static-info">
                                    <div class="col-md-5 name">
                                        认证状态:
                                    </div>
                                    <div class="col-md-7 value">
                                        <?php
                                        switch($model->status){
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
                                        <?php echo $model->message; ?>
                                    </div>
                                </div>
                                <div class="row static-info">
                                    <div class="col-md-5 name">
                                        资料:
                                    </div>
                                    <div class="col-md-7 value">
                                        <?php
                                        $images = explode(',', $model->images);
                                        foreach ($images as $image){
                                            echo "<img width='600' src='$image'/>";
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="row static-info">
                                    <div class="col-md-5 value">
                                        <a class="btn red" href="<?php echo $this->createUrl("auth/usersRefuse/id/{$model->id}")?>"><i class="fa fa-reply"></i> 拒绝</a>
                                    </div>
                                    <div class="col-md-7 value">
                                        <?php
                                        echo CHtml::form($this->createUrl("auth/usersPass/id/{$model->id}"));
                                        echo CHtml::dropDownList('type',$model->user->type,UserModel::$authTypeArray,array('class'=>'form-control'));
                                        echo '<br><button class="btn blue"><i class="fa fa-thumbs-up"></i> 通过</button>';
                                        echo CHtml::endForm();
                                        ?>
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