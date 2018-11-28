<script type="text/plain" id="<?php echo $this->id; ?>"></script>
<input type="text" id="<?php echo $this->inputId; ?>"  value="<?php echo $this->content; ?>"  name="<?php echo $this->name; ?>" class="<?php echo $this->class?>"/><a href="javascript:void(0);" id="<?php echo $this->inputId; ?>Upload" class="<?php echo $this->btnClass?>">上传图片</a>
<input type="hidden" id="<?php echo $this->inputId; ?>_id"  value="<?php echo $this->idContent; ?>"  name="<?php echo $this->idName; ?>" class="<?php echo $this->class?>"/>
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
            <?php echo $this->id?>.addListener('afterinsertimage', function (t, arg) {
                $("#<?php echo $this->inputId; ?>").attr("value", arg[0].src);
                $("#<?php echo $this->inputId; ?>_id").attr("value", arg[0].id);
            })
        });
        $(document).on('click','#<?php echo $this->inputId; ?>Upload',function(){
            <?php if($this->uploadUrl){?>uploadUrl = '<?php echo $this->uploadUrl?>';<?php }?>
            var myFiles  = <?php echo $this->id?>.getDialog("insertimage");
            myFiles.open();
        })
    });

</script>
