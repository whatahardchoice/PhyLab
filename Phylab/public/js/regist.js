﻿/*
*该js由/views/auth/register.blade.php注册页面加载，负责注册信息的合法性检查以及信息提交
* */


/*
*以下为PhyLab2.0注册界面的js部分
*/

/*
*加载渲染
*/
$(document).ready(function () {
    $("#loading-gif").css("display","none");
	$("#captcha-img").attr('src', G_BASE_URL + '/wecenter/?/account/captcha/' + Math.floor(Math.random() * 10000));
    $('#name').val(sessionStorage.getItem('name'));
    $('#email').val(sessionStorage.getItem('email'));
    $('#password1').val(sessionStorage.getItem('password'));
});

/*
*注册名字合法性检查
*/
function check_name() {
    var patterns = "^([a-zA-Z0-9_]|[\u4E00-\u9FA5]){1,20}$";
    if(!(new RegExp(patterns)).test($('#name').val())){
        $('#alert-name').text("请您输入1至20位的中文、字母或数字组合作为用户名。");
        $('#alert-name').fadeIn();
        return -1;
    }
    else{
        $('#alert-name').fadeOut();
        return 0;
    }
}
$('#name').change(check_name).blur(check_name);

/*
*注册邮箱合法性检查
*/
function check_email() {
    var patterns = "^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$";
    if(!(new RegExp(patterns)).test($('#email').val())){
        $('#alert-email').text("请您输入正确的邮箱。");
        $('#alert-email').fadeIn();
        return -1;
    }
    else{
        $('#alert-email').fadeOut();
        return 0;
    }
}
$('#email').change(check_email).blur(check_email);

/*
*注册年级合法性检查
*/
function check_grade() {
    if($('#grade option:selected').val() === 'none') {
        $('#alert-grade').text("请您选择您的年级。");
        $('#alert-grade').fadeIn();
        return -1;
    }
    else {
        $('#alert-grade').fadeOut();
        return 0;
    }
}
$('#grade').change(check_grade).blur(check_grade);

/*
*注册密码合法性检查
*/
function check_password1() {
    var patterns = "^[0-9a-zA-z]{6,12}$";
    if(!(new RegExp(patterns)).test($('#password1').val())) {
        $('#alert-password1').text("您的密码应该为6至12位的字母与数字组合。");
        $('#alert-password1').fadeIn();
        return -1;
    }
    else {
        $('#alert-password1').fadeOut();
        return 0;
    }
}
$('#password1').change(check_password1).blur(check_password1);

/*
*两次输入密码一致性检查
*/
function check_password2() {
    if ($('#password1').val() !== $('#password2').val()) {
        $('#alert-password2').text("您两次输入的密码不一致。");
        $('#alert-password2').fadeIn();
        return -1;
    }
    else {
        $('#alert-password2').fadeOut();
        return 0;
    }
}
$('#password2').change(check_password2).blur(check_password2);

/*
*所有注册信息合法性检查
*/
function check_register() {
    var error_flag = false;
    if (check_name() !== 0 )
        error_flag = true;
    if (check_email() !== 0 )
        error_flag = true;
    if (check_grade() !== 0 )
        error_flag = true;
    if (check_password1() !== 0 )
        error_flag = true;
    if (check_password2() !== 0 )
        error_flag = true;
    if (error_flag === true)
        return false;
    else
        return true;
}

/*
*点击注册函数，提交注册表单，由/view/auth/register.blade.php中的"#submit"绑定
*/
function submit_register() {
    if (check_register() === false)
        return;
    $.ajax(G_BASE_URL + '/wecenter/?/account/ajax/register_process/', {
        method: 'POST',
        data: {
            'user_name': $('#name').val(),
            'password': $('#password1').val(),
            'agreement_chk': true,
            'seccode_verify': $('#captcha').val(),
            'email': $('#email').val()
            },
        beforeSend: function () {
            $("#loading-gif").css("display","block");
        }
        }).done(function (data) {
            $("#loading-gif").css("display","none");
            data = JSON.parse(data);
            if (data.errno === 1)
                window.location.href = data.rsm.url;
            else {
                alert(data.err+data["errorcode"]);
            }
        }).fail(function (xhr, status) {
            alert('注册失败: ' + xhr.status + ', 原因: ' + status);
        })
}

$('#quick-regist').click(function () {
    sessionStorage.setItem('name', $('#name').val());
    sessionStorage.setItem('email', $('#email').val());
    sessionStorage.setItem('password', $('#password').val());
    window.location.href = "/register";
});
