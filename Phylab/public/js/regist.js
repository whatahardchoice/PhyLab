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
		});

    $('#name').change(function () {
      var patterns = "^([a-zA-Z0-9_]|[\u4E00-\u9FA5]){1,20}$";
      if(!(new RegExp(patterns)).test(this.value)){
        $('#alert-name').text("用户名输入不符合要求。");
        $('#alert-name').show();
      }
      else{
        $('#alert-name').hide();
      }
    });

    $('#email').change(function () {
      var patterns = "^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$";
      if(!(new RegExp(patterns)).test(this.value)){
        $('#alert-email').text("邮件输入不符合要求。");
        $('#alert-email').show();
      }
      else{
        $('#alert-email').hide();
      }
    });

    $('#grade').change(function () {
      if($('#grade').val() === 'none') {
        $('#alert-grade').text("未选择年级。");
        $('#alert-grade').show();
      }
      else
        $('#alert-grade').hide();
    });

    $('#password1').change(function () {
      var patterns = "^[0-9a-zA-z]{6,12}$";
      if(!(new RegExp(patterns)).test(this.value)) {
        $('#alert-password1').text("密码应该为6至12位的字母与数字组合。");
        $('#alert-password1').show();
      }
      else
        $('#alert-password1').hide();
    });

    $('#password2').change(function () {
      if ($('#password1').val() !== $('#password2').val()) {
        $('#alert-password2').text("两次密码输入不一致。");
        $('#alert-password2').show();
      }
      else
        $('#alert-password2').hide();
    });

    $('#captcha').change(function () {
    });

		function submit_register() {
			$.ajax(G_BASE_URL + '/wecenter/?/account/ajax/register_process/', {
				method: 'POST',
				data: {
					user_name: $('#name').val(),
					password: $('#password1').val(),
					agreement_chk: true,
					seccode_verify: $('#captcha').val(),
					email: $('#email').val(),
				}
			}).done(function (data) {
				if (data['errno'] === 1)
					window.location.href = data['rsm']['url'];
				else
					alert(data['err']);
			}).fail(function (xhr, status) {
				alert('注册失败: ' + xhr.status + ', 原因: ' + status);
			})
		}
