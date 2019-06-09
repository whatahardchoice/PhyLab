$('#btn-bulletin-edit').click(function () {
    $('#bulletin-board-edit').toggle();
});

$('#btn-bulletin-save').click(function () {
    $.ajax({
        type:"POST",
        url:"./modifyBulletin",
        data:{'contents':$("#textarea-bulletin-edit").val()},
    }).done(function (data) {
        if (data['status'] == 'fail') {
            alert("错误："+data['message']+" "+data['errorcode']);
        }
        else {
            alert("修改成功");
            window.location.reload();
        }

    }).fail(function (xhr, status) {
        alert('AJAX POST失败: ' + xhr.status + ', 原因: ' + status+'8001');
    });
});

$('#btn-bulletin-cancel').click(function () {
    $('#bulletin-board-edit').hide();
});