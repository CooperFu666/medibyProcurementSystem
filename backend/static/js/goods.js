/**
 * Created by Administrator on 15-7-29.
 */

//分类
$("#add_category").click(function () {
    c1 = $("#c1").val();
    c2 = $("#c2").val();
    c3 = $("#c3").val();
    c1_table = "";
    c2_table = "";
    c3_table = "";
    if(c1==""){
        bootbox.alert("请选择分类")
        return false;
    }else{
        c_id = "_"+c1;
        c1_title = $("#c1").find("option:selected").text();
    }
    if(c2!=""){
        c_id += "_"+c2;
        c2_title = $("#c2").find("option:selected").text();
    }
    if(c3!=""){
        c_id += "_"+c3;
        c3_title = $("#c3").find("option:selected").text();
    }

    if(c1!=""){
        c1_table =c1_title+'<input id="c1'+c_id+'" type="hidden" value="'+c1+'" name="category_bind[]">';
    }
    if(c2!=""){
        c2_table =c2_title+'<input id="c2'+c_id+'" type="hidden" value="'+c2+'" name="category_bind[]">';
    }
    if(c3!=""){
        c3_table =c3_title+'<input id="c3'+c_id+'" type="hidden" value="'+c3+'" name="category_bind[]">';
    }

    is_c = $("#c1"+c_id).val();
    if(is_c!=undefined){
        bootbox.alert("该分类已添加")
        return false;
    }
    html = '<tr role="row" class="odd">' +
        '<td>'+c1_table+'</td>' +
        '<td>'+c2_table+'</td>' +
        '<td>'+c3_table+'</td>' +
        '<td><button class="btn btn-xs red default" onclick="delete_c(\'c1'+c_id+'\');"><i class="fa fa-times"></i>删除</button></td>' +
        '</tr>';


    $("#c1").val("");
    $("#c2").html("");
    $("#c3").html("");
    $("#c2_box").fadeOut();
    $("#c3_box").fadeOut();


    $("#category_tbody").append(html);

})

function delete_c(objId){
    $("#"+objId).parent().parent().remove();
}

//属性
$("#addattr").click(function () {
    bootbox.prompt({
        title: "添加属性",
        inputType: 'text',
        callback:function(result) {
            if(result!=null){
                $.ajax({
                    url: attrAddUrl,
                    dataType: 'json',
                    method: 'POST',
                    data: {"title": result},
                    success: function(data) {
                        if (data.result == 1) {
                            html = '<tr role="row" class="odd" id="attrTr'+data.data.id+'">' +
                                '<td>'+data.data.title+'</td>' +
                                '<td><button class="btn btn-xs default btn-editable" onclick="update_attr(\''+data.data.id+'\');"><i class="fa fa-pencil">修改</i></button><button class="btn btn-xs red default" onclick="delete_attr(\''+data.data.id+'\');"><i class="fa fa-times"></i>删除</button></td>' +
                                '</tr>';
                            $("#attrList").append(html);
                        }else if(data.result == 2){
                            bootbox.alert("添加失败");
                        }else if(data.result == 3){
                            bootbox.alert("请先添加型号");
                        }
                    },
                    error: function(xhr) {
                        bootbox.alert("添加失败");
                    }
                })
            }else{
                bootbox.alert("属性名称不能空");
            }
        }
    });
});

function delete_attr(objId){
    bootbox.confirm("确认删除？", function(result) {
        if(result) {
            $.ajax({
                url: attrDeleteUrl,
                dataType: 'json',
                method: 'POST',
                data: {"id":objId},
                success: function(data) {
                    if(data.success==1)
                    {
                        bootbox.alert("删除成功", function() {
                            $("#attrTr"+objId).remove();
                        });
                    }else{
                        var message = data.message?data.message:'';
                        bootbox.alert("删除失败"+message);
                    }
                },
                error: function(xhr) {
                    bootbox.alert("删除失败");
                }
            })
        }
    });
}

function update_attr(objId){
    title = $("#attrTr"+objId).find("td:first-child").html();
    bootbox.prompt({
        title: "修改属性",
        inputType: 'text',
        value: title,
        callback:function(result) {
            if(result!=null){
                console.log(result);
                $.ajax({
                    url: attrUpdateUrl,
                    dataType: 'json',
                    method: 'POST',
                    data: {"title": result,"id":objId},
                    success: function(data) {
                        if (data.result == 1) {
                            $("#attrTr"+objId).remove();
                            html = '<tr role="row" class="odd" id="attrTr'+data.data.id+'">' +
                                '<td>'+data.data.title+'</td>' +
                                '<td><button class="btn btn-xs default btn-editable" onclick="update_attr(\''+data.data.id+'\');"><i class="fa fa-pencil">修改</i></button><button class="btn btn-xs red default" onclick="delete_attr(\''+data.data.id+'\');"><i class="fa fa-times"></i>删除</button></td>' +
                                '</tr>';
                            $("#attrList").append(html);
                        }
                    },
                    error: function(xhr) {
                        bootbox.alert("修改失败");
                    }
                })
            }else{
                bootbox.alert("属性名称不能空");
            }
        }
    });
}
