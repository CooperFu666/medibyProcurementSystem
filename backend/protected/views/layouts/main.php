<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8"/>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/static/fonts/Open_Sans_400_300_600_700_subset_all.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN THEME STYLES -->
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/static/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/static/css/plugins.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/static/css/layout.css" rel="stylesheet" type="text/css"/>
    <link id="style_color" href="<?php echo Yii::app()->request->baseUrl; ?>/static/css/themes/darkblue.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/static/css/custom.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/static/css/fullcalendar.css" rel="stylesheet" type="text/css"/>
    <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
    <!-- BEGIN CORE PLUGINS -->
    <!--[if lt IE 9]>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/respond.min.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/excanvas.min.js"></script>
    <![endif]-->
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/jquery-migrate.min.js" type="text/javascript"></script>
    <!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/jquery.blockui.min.js" type="text/javascript"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/jquery.cokie.min.js" type="text/javascript"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/jquery.storage.js" type="text/javascript"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/static/plugins/bootbox.min.js" type="text/javascript"></script>

    <!-- END CORE PLUGINS -->

    <script src="<?php echo Yii::app()->request->baseUrl; ?>/static/js/metronic.js" type="text/javascript"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/static/js/layout.js" type="text/javascript"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/static/js/quick-sidebar.js" type="text/javascript"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/static/js/demo.js" type="text/javascript"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/static/js/moment.min.js" type="text/javascript"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/static/js/fullcalendar.min.js" type="text/javascript"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/static/js/common.js" type="text/javascript"></script>
    <title><?php echo Yii::app()->name.CHtml::encode($this->pageTitle); ?></title>
    <link rel="shortcut icon" href="favicon.ico"/>
    <script>
        var ckConfig = {
            filebrowserBrowseUrl :'<?php echo Yii::app()->createUrl("attachment")?>',
            filebrowserImageBrowseUrl : '<?php echo Yii::app()->createUrl("attachment/index",array("type"=>"Images"))?>',
            filebrowserFlashBrowseUrl : '<?php echo Yii::app()->createUrl("attachment/index",array("type"=>"flash"))?>',
            filebrowserUploadUrl : '<?php echo Yii::app()->createUrl("attachment/upload",array("type"=>"files"))?>',
            filebrowserImageUploadUrl : '<?php echo Yii::app()->createUrl("attachment/upload",array("type"=>"Images"))?>',
            filebrowserFlashUploadUrl : '<?php echo Yii::app()->createUrl("attachment/upload",array("type"=>"flash"))?>'
        }
    </script>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-fixed-mobile" and "page-footer-fixed-mobile" class to body element to force fixed header or footer in mobile devices -->
<!-- DOC: Apply "page-sidebar-closed" class to the body and "page-sidebar-menu-closed" class to the sidebar menu element to hide the sidebar by default -->
<!-- DOC: Apply "page-sidebar-hide" class to the body to make the sidebar completely hidden on toggle -->
<!-- DOC: Apply "page-sidebar-closed-hide-logo" class to the body element to make the logo hidden on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-hide" class to body element to completely hide the sidebar on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-fixed" class to have fixed sidebar -->
<!-- DOC: Apply "page-footer-fixed" class to the body element to have fixed footer -->
<!-- DOC: Apply "page-sidebar-reversed" class to put the sidebar on the right side -->
<!-- DOC: Apply "page-full-width" class to the body element to have full width page without the sidebar menu -->
<body class="page-header-fixed page-quick-sidebar-over-content ">
<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="<?php echo $this->createUrl('site/index');?>">
                <?php //echo CHtml::encode(Yii::app()->name); ?>
                <img src="<?php echo Yii::app()->request->baseUrl; ?>/static/images/logo.png" alt="logo" class="logo"/>
            </a>
            <div class="menu-toggler sidebar-toggler hide">
                <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
            </div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
        </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                <!-- BEGIN USER LOGIN DROPDOWN -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <li class="dropdown dropdown-user">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <img alt="" class="img-circle" src="<?php echo Yii::app()->request->baseUrl; ?>/static/images/avatar3_small.jpg"/>
					<span class="username username-hide-on-mobile">
					<?= Yii::app()->user->name?> </span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                        <li>
                            <a href="<?=$this->createUrl('/site/logout')?>">
                                <i class="icon-key"></i> 退出 </a>
                        </li>
                    </ul>
                </li>
                <!-- END USER LOGIN DROPDOWN -->
                <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <li class="dropdown dropdown-quick-sidebar-toggler">
                    <a href="<?=$this->createUrl('/site/logout')?>" class="dropdown-toggle">
                        <i class="icon-logout"></i>
                    </a>
                </li>
                <!-- END QUICK SIDEBAR TOGGLER -->
            </ul>
        </div>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END HEADER INNER -->
</div>
<!-- END HEADER -->
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
<!-- BEGIN SIDEBAR -->

    <?php
    $this->widget('admin.components.Menu')
    ?>
<!-- END SIDEBAR -->
<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
    <div class="page-content">
        <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
        <div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Modal title</h4>
                    </div>
                    <div class="modal-body">
                        Widget settings form goes here
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn blue">Save changes</button>
                        <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
        <!-- BEGIN STYLE CUSTOMIZER -->
        <div class="theme-panel hidden-xs hidden-sm">
            <div class="toggler">
            </div>
            <div class="toggler-close">
            </div>
            <div class="theme-options">
                <div class="theme-option theme-colors clearfix">
						<span>
						THEME COLOR </span>
                    <ul>
                        <li class="color-default current tooltips" data-style="default" data-container="body" data-original-title="Default">
                        </li>
                        <li class="color-darkblue tooltips" data-style="darkblue" data-container="body" data-original-title="Dark Blue">
                        </li>
                        <li class="color-blue tooltips" data-style="blue" data-container="body" data-original-title="Blue">
                        </li>
                        <li class="color-grey tooltips" data-style="grey" data-container="body" data-original-title="Grey">
                        </li>
                        <li class="color-light tooltips" data-style="light" data-container="body" data-original-title="Light">
                        </li>
                        <li class="color-light2 tooltips" data-style="light2" data-container="body" data-html="true" data-original-title="Light 2">
                        </li>
                    </ul>
                </div>
                <div class="theme-option">
						<span>
						Layout </span>
                    <select class="layout-option form-control input-sm">
                        <option value="fluid" selected="selected">Fluid</option>
                        <option value="boxed">Boxed</option>
                    </select>
                </div>
                <div class="theme-option">
						<span>
						Header </span>
                    <select class="page-header-option form-control input-sm">
                        <option value="fixed" selected="selected">Fixed</option>
                        <option value="default">Default</option>
                    </select>
                </div>
                <div class="theme-option">
						<span>
						Top Menu Dropdown</span>
                    <select class="page-header-top-dropdown-style-option form-control input-sm">
                        <option value="light" selected="selected">Light</option>
                        <option value="dark">Dark</option>
                    </select>
                </div>
                <div class="theme-option">
						<span>
						Sidebar Mode</span>
                    <select class="sidebar-option form-control input-sm">
                        <option value="fixed">Fixed</option>
                        <option value="default" selected="selected">Default</option>
                    </select>
                </div>
                <div class="theme-option">
						<span>
						Sidebar Menu </span>
                    <select class="sidebar-menu-option form-control input-sm">
                        <option value="accordion" selected="selected">Accordion</option>
                        <option value="hover">Hover</option>
                    </select>
                </div>
                <div class="theme-option">
						<span>
						Sidebar Style </span>
                    <select class="sidebar-style-option form-control input-sm">
                        <option value="default" selected="selected">Default</option>
                        <option value="light">Light</option>
                    </select>
                </div>
                <div class="theme-option">
						<span>
						Sidebar Position </span>
                    <select class="sidebar-pos-option form-control input-sm">
                        <option value="left" selected="selected">Left</option>
                        <option value="right">Right</option>
                    </select>
                </div>
                <div class="theme-option">
						<span>
						Footer </span>
                    <select class="page-footer-option form-control input-sm">
                        <option value="fixed">Fixed</option>
                        <option value="default" selected="selected">Default</option>
                    </select>
                </div>
            </div>
        </div>
        <!-- END STYLE CUSTOMIZER -->
        <!-- BEGIN PAGE HEADER-->
        <h3 class="page-title">
            <?php echo $this->title?>
        </h3>
        <div class="page-bar">
            <?php if(isset($this->breadcrumbs)):?>
                <?php $this->widget('application.components.BreadCrumb', array(
                    'crumbs'=>$this->breadcrumbs
                )); ?><!-- breadcrumbs -->
            <?php endif?>
        </div>
        <!-- END PAGE HEADER-->
        <!-- BEGIN PAGE CONTENT-->
        <?php echo $content; ?>
        <!-- END PAGE CONTENT-->
    </div>
</div>
<!-- END CONTENT -->
</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
<div class="page-footer">
    <div class="page-footer-inner">
        2018 &copy; 采购系统
    </div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
    </div>
</div>
<!-- END FOOTER -->
<script>
    var switchUrl = '<?php echo $this->createUrl('status')?>';
    jQuery(document).ready(function () {
        Metronic.init(); // init metronic core components
        Layout.init(); // init current layout
        QuickSidebar.init(); // init quick sidebar
        Demo.init(); // init demo features
        $(document).on('click','.page-sidebar a,.page-breadcrumb a',function(){
            $.localStorage('filter','');
        });
        $(document).on('click', 'button[name="back"]', function () {
            window.history.go(-1);
        });
        $(document).on('switchChange.bootstrapSwitch','.make-switch', function(event, state) {
            var type = $(this).attr('data-type');
            var id = $(this).attr('data-id');
            $.ajax( {
                url:switchUrl,
                data:{
                    type : type,
                    status: state ? 1 : 0,
                    id:id
                },
                type:'post',
                dataType:'json',
                success:function(data) {
                }
            });
        });
    });
    $(document).on('click','.bootbox-confirm', function() {
        var button = $(this);
        bootbox.confirm("确认删除？", function(result) {
            if(result) {
                var url = button.attr('rel');
                $.getJSON(url,function(backdata){
                    if(backdata.success==1)
                    {
                        bootbox.alert("删除成功", function() {
                            window.location.href='';
                        });
                    }else{
                        var message = backdata.message?backdata.message:'';
                        bootbox.alert("删除失败"+message);
                    }
                });
            }
        });
    });
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>