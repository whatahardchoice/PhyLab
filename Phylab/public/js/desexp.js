function switchContent() {
    let value = $("#desexp-select option:selected").val();

    $.ajax('./desexp/'+value, {
    }).done(function (data) {
        if (data.status === 'success') {
            // refresh iframe
            $("#desexp-iframe").attr('src', data.link);
        } else if (data.status === 'fail' && data.message === "未登录，请登陆后查看其他内容") {
            alert("请登录后查看其他内容！");
            window.location.href = 'login';
        } else {
            alert("出错了，原因："+data.message);
        }
    }).fail(function (xhr, status) {
        if (xhr.status === 401) {
            alert("请登录后查看其他内容！");
            window.location.href = 'login';
        }
        else
            alert('AJAX POST失败: ' + xhr.status + ', 原因: ' + status);
    });

}