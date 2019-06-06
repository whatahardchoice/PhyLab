/*
* 登录提交函数，由/view/auth/login.blade.php中的"#login-submit"按钮绑定
*/
function Post_login(){
    postData="email="+encodeURI($('#email').val())+"&password="+encodeURI($('#password').val());

    if($('#remember').prop('checked'))
        postData+="&remember="+$('#remember').val();
    PostAjax("/login",postData,function(){
        if (this.readyState==4 && this.status==200){
            var jsonText = eval("(" + this.responseText + ")");
            if(jsonText["status"]=='success'){
                window.location.href="/index";
            }
            else{
                $('#alert-message').text(jsonText["message"]+jsonText["errorcode"]);
                $('#login-alert').show();
            }
        }
        else if(this.readyState==4 && this.status!=200){
            var jsonText = eval("(" + this.responseText + ")");
            if(jsonText["code"]==904){
                $('#alert-message').text("账号或密码不能为空");
            }
            else if(jsonText["code"]==101){
                $('#alert-message').text("用户名或密码错误");
            }
            else{
                $('#alert-message').text("未知错误");
            }
            $('#login-alert').show();
        }
    });
}

/*
* 将 "enter"键按下事件绑定为"#login-submit"登录按钮的点击事件
*/
$('#login_form input').keydown(function (e) {
    if (e.keyCode == 13)
    {
        $('#login-submit').click();
    }
});