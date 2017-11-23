 function Post_login(){
    postData="email="+encodeURI($('#email').val())+"&password="+encodeURI($('#password').val());
    if($('#remember').prop('checked'))
        postData+="&remember="+$('#remember').val();
    PostAjax("/login",postData,function(){
        if (this.readyState==4 && this.status==200){
            var jsonText = eval("(" + this.responseText + ")");
            //alert(this.responseText);
            //alert(jsonText["status"]);
            if(jsonText["status"]=='success'){
                window.location.href="/index";
            }
            else{
                $('#alert-message').text(jsonText["message"]);
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
$('#login_form input').keydown(function (e) {
    if (e.keyCode == 13)
        {
            $('#login-submit').click();
        }
    });