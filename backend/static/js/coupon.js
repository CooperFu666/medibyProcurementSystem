//搜索用户列表
$("#search").click(function () {
    phone = $("#usersSearch").val();
    $.ajax({
        url: searchUser,
        method: 'POST',
        data: {"phone": phone},
        success: function(data) {
            $('#userList').html(data);
        },
        error: function(xhr) {
            bootbox.alert("搜索失败");
        }
    })
});
//添加用户
$("#add_user").click(function () {
    $("#userList").find("option:selected").each(function(){
        $("#grantList").append('<option value="'+$(this).val()+'">'+$(this).text()+'</option>');
    })
});

$("#delete_user").click(function () {
    $("#grantList").find("option:selected").remove();
});

$("#grant").click(function () {
    var dialog = bootbox.dialog({
        title: '发放优惠券',
        message: '<p><i class="fa fa-spin fa-spinner"></i> 发放中...</p>'
    });
    dialog.init(function(){
        var data = new Array();
        $("#grantList").find("option").each(function(){
            data.push($(this).val())
        })
        $.ajax({
            url: grantUser,
            method: 'POST',
            dataType: 'json',
            data: {"user": data},
            success: function(data) {
                if(data.success==1)
                {
                    dialog.find('.bootbox-body').html('发放成功');
                    bootbox.alert("发放成功", function() {
                        window.location.href = infoUser;
                    });
                }else{
                    var message = data.message?data.message:'';
                    dialog.find('.bootbox-body').html("发放失败"+message);
                }
            },
            error: function(xhr) {
                dialog.find('.bootbox-body').html("发放失败"+message);
            }
        })
    });

});

$("#grantAll").click(function () {
    var dialog = bootbox.dialog({
        title: '发放优惠券',
        message: '<p><i class="fa fa-spin fa-spinner"></i> 发放中...</p>'
    });
    dialog.init(function(){
        $.ajax({
            url: grantUser,
            method: 'POST',
            dataType: 'json',
            data: {"user": 'all'},
            success: function(data) {
                if(data.success==1)
                {
                    dialog.find('.bootbox-body').html('发放成功');
                    bootbox.alert("发放成功", function() {
                        window.location.href = infoUser;
                    });
                }else{
                    var message = data.message?data.message:'';
                    dialog.find('.bootbox-body').html("发放失败"+message);
                }
            },
            error: function(xhr) {
                dialog.find('.bootbox-body').html("发放失败"+message);
            }
        })
    });
});


