$(function() {
    $('#user-form').submit(function(event) {

        event.preventDefault(); // Prevent the form from submitting via the browser
        let form = $(this);
        //var form = new FormData(document.getElementById("user-form"));
        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize(),
            //data: form,
            //cache: false,
            //contentType: false,
            //processData: false,
        }).done(function(data) {
            // Optionally alert the user of success here...
            alert('更新成功');
        }).fail(function(data) {
            alert("更新失败"+'8004');
        });
    });
});


$("#btn-upload-avatar").click(function (){
    if ($("#input-upload-avatar").get(0).files.length == 0)
    {
        alert("请先选择一个文件！");
        return false;
    }
    //e.preventDefault();
    let formData = new FormData();
    let file = $("#input-upload-avatar").get(0).files[0];

    if (file.size > 5242800)
    {
        alert("文件过大了！");
        return false;
    }

    formData.append("avatar", file);
    $.ajax({
            type:"POST",
            url:"./user/avatar",
            data:formData,
            contentType:false,
            processData:false
        }
    )
        .done(function (data) {
            if (data.status === "fail") {
                alert("上传失败"+data["errorcode"]);
            } else {
                $("#user_avatar").attr('src',data['avatarPath']);
            }

        })
        .fail(function (xhr, status) {
            alert('失败: ' + xhr.status + ', 原因: ' + status+'8004');
        });

    return false;
});