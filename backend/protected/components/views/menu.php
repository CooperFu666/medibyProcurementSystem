<div class="page-sidebar-wrapper">
    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
    <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
    <div class="page-sidebar navbar-collapse collapse">
        <!-- BEGIN SIDEBAR MENU -->
        <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
        <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
        <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
        <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
        <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
        <ul class="page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
            <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
            <li class="sidebar-toggler-wrapper">
                <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                <div class="sidebar-toggler">
                </div>
                <!-- END SIDEBAR TOGGLER BUTTON -->
            </li>
            <!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->
            <li class="sidebar-search-wrapper">
                <!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
                <!-- DOC: Apply "sidebar-search-bordered" class the below search form to have bordered search box -->
                <!-- DOC: Apply "sidebar-search-bordered sidebar-search-solid" class the below search form to have bordered & solid search box -->

                <!-- END RESPONSIVE QUICK SEARCH FORM -->
            </li>
            <?php foreach($this->menus as $menu){?>
                <li class="<?php echo isset($menu['active'])&&$menu['active']==1 ? 'active open' : '' ?>">
                    <a href="javascript:;">
                        <i class="<?php echo isset($menu['icons'])?$menu['icons']:''?>"></i>
                        <span class="title"><?php echo isset($menu['name'])?$menu['name']:''?></span>
                        <span class="arrow <?php echo isset($menu['active'])&&$menu['active']==1?'open':''?>"></span>
                    </a>
                    <ul class="sub-menu" style="<?php echo isset($menu['active'])&&$menu['active']==1?'display: block;':'display: none;'?>">
                        <?php if($menu['group']){foreach($menu['group'] as $group){?>
                            <?php if (isset($group['active'])&&isset($group['nodes'])):unset($group['nodes']['active']);endif;if(isset($group['nodes'])&&count($group['nodes'])>1){?>
                                <li class="<?php echo isset($group['active'])&&$group['active']==1?'open':''?>">
                                    <a href="javascript:;">
                                        <i class="<?php echo $group['icon']?>"></i> <?php echo $group['name']?> <span class="arrow <?php echo isset($group['active'])&&$group['active']==1?'open':''?>"></span>
                                    </a>
                                    <?php if($group['nodes']){?>
                                        <ul class="sub-menu" style="<?php echo isset($group['active'])&&$group['active']==1?'display: block;':'display: none;'?>">
                                            <?php foreach($group['nodes'] as $a){?>
                                                <li class="<?php if(isset($a['active'])){?>active<?php }?>">
                                                    <a href="<?php echo Yii::app()->createUrl($a['m'].'/'.$a['a'])?>"><?php echo $a['name']?></a>
                                                </li>
                                            <?php }?>
                                        </ul>
                                    <?php }?>
                                </li>
                            <?php }elseif(!empty($group['nodes'])){$a = current($group['nodes']);?>
                                <li  class="<?php if(isset($a['active'])){?>active<?php }?>">
                                    <a href="<?php echo Yii::app()->createUrl($a['m'].'/'.$a['a'])?>" >
                                        <i class="<?php echo  $group['icon']?>"></i>
                                        <?php echo $group['name']?></a>
                                </li>
                            <?php }?>
                        <?php }?>
                        <?php }?>

                    </ul>
                </li>
            <?php }?>
            <!-- BEGIN ANGULARJS LINK -->
        </ul>
        <!-- END SIDEBAR MENU -->
    </div>
</div>