<?php
/**
 * Created by PhpStorm.
 * User: druphliu@gmail.com
 * Date: 15-8-5
 * Time: 下午3:17
 */
$this->breadcrumbs=array(
    array('name'=>'demo','url'=>'#'),
    array('name'=>'富文本编辑框/上传按钮')
);
?>
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <?php
    $this->widget('application.extensions.baiduUeditor.UeditorWidget',
        array(
            'id'=>'fileId',//容器的id 唯一的[必须配置]
            'name'=>'file[id]',//post到后台接收的name [必须配置]
            'titleName'=>'file[title]',
            'inputId'=>'id',//post到后台接收的input ID [file image 时必须配置]
            'content'=>'',//初始化内容 [可选的]
            'type'=>'file',
            'class'=>'form-control',
            'btnClass'=>'btn blue',
            //配置选项，[可选的]
            //将ueditor的配置项以数组键值对的方式传入,具体查看ueditor.config.js
            //不要配置serverUrl(即使配置也会被覆盖)程序会自动处理后端url
            'config'=>array(
                // 'toolbars'=>array(array('fullscreen', 'source', '|')),//toolbars注意是嵌套两个数组
                'lang'=>'zh-cn'
            )
        )
    );
    ?>
</div>
<div class="row">
    <?php
    $this->widget('application.extensions.baiduUeditor.UeditorWidget',
        array(
            'id'=>'imageId',//容器的id 唯一的[必须配置]
            'name'=>'image[id]',//post到后台接收的name [必须配置]
            'inputId'=>'imageInputId',//post到后台接收的input ID [file image 时必须配置]
            'content'=>'',//初始化内容 [可选的]
            'class'=>'form-control',
            'btnClass'=>'btn blue',
            'type'=>'image',
            //配置选项，[可选的]
            //将ueditor的配置项以数组键值对的方式传入,具体查看ueditor.config.js
            //不要配置serverUrl(即使配置也会被覆盖)程序会自动处理后端url
            'config'=>array(
                // 'toolbars'=>array(array('fullscreen', 'source', '|')),//toolbars注意是嵌套两个数组
                'lang'=>'zh-cn'
            )
        )
    );
    ?>
</div>
<div class="row">
    <?php
    $this->widget('application.extensions.baiduUeditor.UeditorWidget',
        array(
            'id'=>'imageIds',//容器的id 唯一的[必须配置]
            'name'=>'images[id]',//post到后台接收的name [必须配置]
            'inputId'=>'imageInputIds',//post到后台接收的input ID [file image 时必须配置]
            'content'=>array(array('src'=>'http://8.8.8.12:8080/M00/00/0C/CAgIDFdFEA6ENyL4AAAAAEfSRu4909.jpg'),array('src'=>'http://8.8.8.12:8080/M00/00/0C/CAgIDFdFEA6ENyL4AAAAAEfSRu4909.jpg')),//初始化内容 [可选的]
            'class'=>'form-control',
            'btnClass'=>'btn blue',
            'type'=>'images',
            //配置选项，[可选的]
            //将ueditor的配置项以数组键值对的方式传入,具体查看ueditor.config.js
            //不要配置serverUrl(即使配置也会被覆盖)程序会自动处理后端url
            'config'=>array(
                // 'toolbars'=>array(array('fullscreen', 'source', '|')),//toolbars注意是嵌套两个数组
                'lang'=>'zh-cn'
            )
        )
    );
    ?>
</div>
<div class="row">
    <div class="col-md-12">
        <!-- Begin: life time stats -->
        <?php
        $this->widget('application.extensions.baiduUeditor.UeditorWidget',
            array(
                'id'=>'article_content',//容器的id 唯一的[必须配置]
                'name'=>'content',//post到后台接收的name [必须配置]
                'content'=>'',//初始化内容 [可选的]
                'type'=>'textarea',
                //配置选项，[可选的]
                //将ueditor的配置项以数组键值对的方式传入,具体查看ueditor.config.js
                //不要配置serverUrl(即使配置也会被覆盖)程序会自动处理后端url
                'config'=>array(
                    // 'toolbars'=>array(array('fullscreen', 'source', '|')),//toolbars注意是嵌套两个数组
                    'lang'=>'zh-cn'
                )
            )
        );
        ?>
        <!-- End: life time stats -->
    </div>
</div>

<!-- END PAGE CONTENT-->
