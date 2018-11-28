<script type="text/plain" id="<?php echo $this->id; ?>"></script>
<input type="text" style="<?php if($this->style):echo$this->style;endif;?>" id="<?php echo $this->inputId; ?>" value="<?php echo $this->content; ?>" name="<?php echo $this->name; ?>" <?php echo $this->attr?> class="<?php echo $this->class?>"/>
<?php if($this->titleName):?><input type="hidden" name="<?php echo $this->titleName; ?>" id="<?php echo $this->inputId; ?>_title"><?php endif;?>
<a href="javascript:void(0);" id="<?php echo $this->inputId; ?>Upload" class="<?php echo $this->btnClass?>" >上传文件</a>
<script>

    $(function () {
        <?php if($this->uploadUrl){?>uploadUrl = '<?php echo $this->uploadUrl?>';<?php }?>
        var <?php echo $this->id?> =
        UE.getEditor('<?php echo $this->id?>', <?php echo json_encode($this->config);?>
        );
        <?php echo $this->id?>.ready(function () {
            <?php echo $this->id?>.execCommand('serverparam', {
                '<?php echo  session_name();?>': '<?php echo session_id(); ?>'
            });
            <?php echo $this->id?>.setDisabled();
            <?php echo $this->id?>.hide();
            <?php echo $this->id?>.addListener('afterUpfile', function (t, arg) {
                $("#<?php echo $this->inputId; ?>").attr("value", arg[0].url);
                $("#<?php echo $this->inputId; ?>_title").attr("value", arg[0].title);
            })
        });
        $(document).on('click','#<?php echo $this->inputId; ?>Upload',function(){
            var myFiles  = <?php echo $this->id?>.getDialog("attachment");
            myFiles.open();
        })
    });

</script>
