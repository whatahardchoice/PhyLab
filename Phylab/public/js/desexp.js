
// First we check if you support touch, otherwise it's click:
let touchEvent = 'ontouchstart' in window ? 'touchstart' : 'click';

// Then we bind via thát event. This way we only bind one event, instead of the two as below
document.getElementById('btn-clear-answer').addEventListener("click", clearAnswer);
document.getElementById('btn-clear-answer').addEventListener("touchstart", clearAnswer);


function switchContent() {
    let value = $("#desexp-select option:selected").val();

    $.ajax('./desexp/'+value, {
    }).done(function (data) {
        if (data.status === 'success') {
            // refresh iframe
            $("#desexp-iframe").attr('src', data.link);
        //} else if (data.status === 'fail' && data.message === "未登录，请登陆后查看其他内容") {
            //alert("请登录后查看其他内容！");
            //window.location.href = 'login';
        } else {
            alert("出错了，原因："+data.message+data.errorcode);
        }
    }).fail(function (xhr, status) {
        // if (xhr.status === 401) {
        //     alert("请登录后查看其他内容！");
        //     window.location.href = 'login';
        // }
        // else
        alert('AJAX POST失败: ' + xhr.status + ', 原因: ' + status);
    });

}

function clearAnswer (e) {
        // $("#desexp-iframe").contents().find(".desexp-text").each(function () {
        //     if (this.style.opacity)
        //         this.style.opacity = "";
        // })
    let inners = document.getElementById("desexp-iframe").contentWindow.document.getElementsByClassName("desexp-text");
    for (var i = 0; i < inners.length; i++) {
        inners[i].style.opacity = "";
    }
}