<!-- blueimp Gallery script -->
<?php  $i = 0;?>
<script type="text/plain" id="<?php echo $this->id; ?>"></script>
<a href="javascript:void(0);" id="<?php echo $this->inputId; ?>Upload" class="<?php echo $this->btnClass ?>">添加图片</a>
<table role="presentation" class="table table-striped clearfix js_<?php echo $this->inputId; ?>">
    <tbody class="files">
    <?php if ($this->content && is_array($this->content)) {

        foreach ($this->content as $c) {
            $i++;
            echo '<tr class="template-download fade in" id="upload' . $this->inputId.$i . '">' .
                '<td>' .
                '<span class="preview">' .
                '<img  src="' . $c['src'] . '" width="30px">' .
                '<input type="hidden" id="'.$this->inputId.'" value="' . $c['src'] . '"  name="' . $this->name . '[]"/>' .
                '</span>' .
                '</td>' .
                '<td>' .
                '<button class="btn red delete btn-sm uploadDelete"  data-id=' . $this->inputId.$i . '>' .
                '<i class="fa fa-trash-o"></i>' .
                '<span>Delete</span>' .
                '</button>' .
                '</td>' .
                '</tr>';
        }
    } ?>
    </tbody>
</table>
<script>

    $(function () {
        var i=<?php echo $i?>;
        <?php if($this->uploadUrl){?>uploadUrl = '<?php echo $this->uploadUrl?>';
        <?php }?>

        var <?php echo $this->id?> =
        UE.getEditor('<?php echo $this->id?>', <?php echo json_encode($this->config);?>
        );

        <?php echo $this->id?>.
        ready(function () {
            <?php echo $this->id?>.
            execCommand('serverparam', {
                '<?php echo  session_name();?>': '<?php echo session_id(); ?>'
            });
            <?php echo $this->id?>.
            setDisabled();
            <?php echo $this->id?>.
            hide();
            <?php echo $this->id?>.
            addListener('afterinsertimage', function (t, arg) {
                var html;
                for(a in arg){
                    i++;
                    html+='<tr class="template-download fade in" id="upload'+i+'">'+
                        '<td>'+
                        '<span class="preview">'+
                        '<img  src="'+arg[a].src+'" width="30px">'+
                        '<input type="hidden" id="<?php echo $this->inputId; ?>" value="'+arg[a].src+'"  name="<?php echo $this->name; ?>[]"/>'+
                        '</span>'+
                        '</td>'+
                        '<td>'+
                        '<button class="btn red delete btn-sm uploadDelete"  data-id='+i+'>'+
                        '<i class="fa fa-trash-o"></i>'+
                        '<span>Delete</span>'+
                        '</button>'+
                        '</td>'+
                        '</tr>';
                }
                $('.js_<?php echo $this->inputId; ?> .files').append(html);
            })
        });
        $(document).on('click', '#<?php echo $this->inputId; ?>Upload', function () {
            <?php if($this->uploadUrl){?>uploadUrl = '<?php echo $this->uploadUrl?>';
            <?php }?>
            var myFiles = <?php echo $this->id?>.
            getDialog("insertimage");
            myFiles.open();
        });
        $(document).on('click','.uploadDelete',function(){
            var id= $(this).attr('data-id');
            $("#upload"+id).remove();
        })
    });

</script>
