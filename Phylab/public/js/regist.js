		$('.user-input').bind("keydown",function(){
			if(event.keyCode == 13) return false;
			else return true;
		});

		$('#InputUser').bind('change',function(){
			var patterns = "^([a-zA-Z0-9_]|[\u4E00-\u9FA5]){1,20}$";
			if(!(new RegExp(patterns)).test(this.value)){
                $(this).addClass("wrong-input");
                _setShowHide(this.id+'Alert',this.id+'Success')();
            }
            else{
                $(this).removeClass("wrong-input");
                _setShowHide(this.id+'Success',this.id+'Alert')();
            }
		})

		$('#InputEmail').bind('change',function(){
			var patterns = "^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$";
			if(!(new RegExp(patterns)).test(this.value)){
                $(this).addClass("wrong-input");
                _setShowHide(this.id+'Alert',this.id+'Success')();
            }
            else{
                $(this).removeClass("wrong-input");
                _setShowHide(this.id+'Success',this.id+'Alert')();
			}
		})

		$('#InputStudent').bind('change',function(){
			var patterns = "^\\d{8}$";
			if(!(new RegExp(patterns)).test(this.value)){
                $(this).addClass("wrong-input");
                _setShowHide(this.id+'Alert',this.id+'Success')();
            }
            else{
                $(this).removeClass("wrong-input");
                _setShowHide(this.id+'Success',this.id+'Alert')();
			}
		})

		$('#InputPwd').bind('change',function(){
			var patterns = "^[0-9a-zA-z]{6,12}$";
			if(!(new RegExp(patterns)).test(this.value)){
                $(this).addClass("wrong-input");
                _setShowHide(this.id+'Alert',this.id+'Success')();
            }
            else{
                $(this).removeClass("wrong-input");
                _setShowHide(this.id+'Success',this.id+'Alert')();
			}
		})

		$('#CheckPwd').bind('change',function(){
			if(this.value!=$('#InputPwd')[0].value){
                $(this).addClass("wrong-input");
                _setShowHide(this.id+'Alert',this.id+'Success')();
            }
            else{
                $(this).removeClass("wrong-input");
                _setShowHide(this.id+'Success',this.id+'Alert')();
            }
		})

        function check(){
            SetDisable('btn-Signup',true);
        }
        function setSignUpStatus(){
            if(document.getElementById('CheckLicense').checked) SetDisable('btn-Signup',false);
            else SetDisable('btn-Signup',true);
        }
		function signUp(){
			var check = true;
			$('.user-input').each(function(){
				if($(this).hasClass("wrong-input")) check = false;
				else if(this.value==""){
					$(this).addClass("wrong-input");
					_setShowHide(this.id+'Alert',this.id+'Success')();
				}
			});
			$('#register-post').click();
		}

		$(function () {
			$('[data-toggle="popover"]').popover({
				html : true
			});
		});
  //PhyLab2.0注册界面
    $(document).ready(function () {
			$("#captcha-img").attr('src', G_BASE_URL + '/wecenter/?/account/captcha/' + Math.floor(Math.random() * 10000));
      $('#name').val(sessionStorage.getItem('name'));
      $('#email').val(sessionStorage.getItem('email'));
      $('#password1').val(sessionStorage.getItem('password'));
		});

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
					'email': $('#email').val(),
				}
			}).done(function (data) {
				data = JSON.parse(data);
				if (data.errno === 1)
					window.location.href = data.rsm.url;
				else
					alert(data.err);
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
