$('body').on(function () {
  $("#captcha-img").src=G_BASE_URL + '/account/captcha/' + Math.floor(Math.random() * 10000);
})

$('#submit').click(function () {
  $.ajax(G_BASIC_URL + '/account/ajax/register_process/', {
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
});
