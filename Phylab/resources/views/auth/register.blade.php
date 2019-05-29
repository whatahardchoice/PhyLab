<!--信息注册页面，用于注册账号 -->

<!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PhyLab</title>

  <!-- Bootstrap -->
  <link href="./css/bootstrap.min.css" rel="stylesheet">
  <link href="./css/bootstrap-tour.min.css" rel="stylesheet">
  <link href="./css/font-awesome.min.css" rel="stylesheet">
  <link href="./css/styles.css" rel="stylesheet">
  <link href="./css/phylab.css" rel="stylesheet">

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>

  <![endif]-->
  <script>
  	var G_BASE_URL='<?php echo url('/');?>';
  </script>
</head>
<body>
<header id="site-header">
  <nav class="navbar navbar-default navbar-fixed-top header" role="navigation">
    <div class="navbar-header">
        <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-responsive-collapse">
          <span class="sr-only">Toggle Navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="{{URL::route('index')}}">
          <img id="header-logo" src="./img/phylab_logo_single.svg"/>
          <span>PhyLab</span>
        </a>
      </div>
	<div class="container">
      
      <div class="collapse navbar-collapse navbar-responsive-collapse">
        <ul class="nav navbar-nav">
          <li>
            <a  href="{{URL::route('report')}}">实验</a>
          </li>
          <li>
            <a href="{{URL::route('wc_login')}}">社区</a>
          </li>
          <li>
            <a href="#">反馈</a>
          </li>
        </ul>
        <div class="navbar-right btns">
          <a class="btn btn-default navbar-btn sign-in" href="{{URL::route('login')}}">登录</a>
          <a class="btn btn-default navbar-btn sign-up" href="{{URL::route('register')}}">注册</a>
        </div>
      </div>
    </div>
  </nav>
</header>

<div class="container layout layout-margin-top">
  <div class="row">
    <div class="col-md-12 layout-body">
      <div class="signup-container">
        <div class="col-md-8 col-md-offset-2">
          <div class="reg-title">
            注册
          </div>
        </div>
        <div class="col-md-6 col-md-offset-3">
          <div class="reg-body">
            <form>
              <div class="control-group">
                <div class="form-group name">
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-male"></i>
                    </div>
                    <input class="form-control" id="name" name="name" placeholder="请输入您的用户名" type="text" value=""/>
                  </div>
                  <div id="alert-name" class="alert alert-warning" role="alert"></div>
                </div>
              </div>
              <div class="control-group">
                <div class="form-group email">
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-envelope"></i>
                    </div>
                    <input class="form-control" id="email" name="email" placeholder="请输入您的登录邮箱" type="email" value=""/>
                  </div>
                  <div id="alert-email" class="alert alert-warning" role="alert"></div>
                </div>
              </div>
              <div class="control-group">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-graduation-cap"></i>
                    </div>
                    <select class="form-control" id="grade" name="grade">
                      <option disabled selected value="none">请选择您的年级</option>
                      <option value="2014">2014</option>
                      <option value="2015">2015</option>
                      <option value="2016">2016</option>
                      <option value="2017">2017</option>
                      <option value="2018">2018</option>
                    </select>
                  </div>
                  <div id="alert-grade" class="alert alert-warning" role="alert"></div>
                </div>
              </div>
              <div class="control-group">
                <div class="form-group password">
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-lock"></i>
                    </div>
                    <input class="form-control" id="password1" name="password" placeholder="请输入密码" type="password" value=""/>
                  </div>
                  <div id="alert-password1" class="alert alert-warning" role="alert"></div>
                </div>
              </div>
              <div class="control-group">
                <div class="form-group password">
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-check-circle"></i>
                    </div>
                    <input class="form-control" id="password2" name="password" placeholder="请再次输入密码" type="password" value="">
                  </div>
                  <div id="alert-password2" class="alert alert-warning" role="alert"></div>
                </div>
              </div>
              <div class="control-group">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-ticket"></i>
                    </div>
                    <a style="display: inline-flex;">
                      <input class="form-control" id="captcha" name="captcha" placeholder="请输入图片中的验证码" type="text">
                    </a>
                    <img id="captcha-img" style="height:33px;margin-left:35px;" onclick="this.src = G_BASE_URL + '/wecenter/?/account/captcha/' + Math.floor(Math.random() * 10000);" src="#">
                  </div>
                  <div id="alert-captcha" class="alert alert-warning" role="alert"></div>
                </div>
              </div>
              <div class="submit">
                <input class="btn btn-primary" id="submit" type="button" value="注册" onclick="submit_register()">
                <img id="loading-gif"  src="http://static.oschina.net/uploads/space/2015/0724/100832_XWND_2392582.gif" style="position: absolute; left:0;right:0;margin:0 auto;">
              </div>
            </form>
            <div class="have-user">
              <p class="tip">
                点击“注册”，表示您已经同意我们的<a data-toggle="modal" data-target="#provisions" style="color:#6d6e6e;"> 用户条款</a>
              </p>
              <span>已有账号，<a style="color:#6d6e6e;" href="/login">点击登录</a></span>
              <div class="modal fade" id="provisions" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"/>
							  <div class="modal-dialog">
							    <div class="modal-content">
							      <div class="modal-header">
							        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
							        <h4 class="modal-title" id="myModalLabel">用户条款</h4>
							      </div>
							      <div class="modal-body">
							        <pre style="text-align: left">
当您申请用户时，表示您已经同意遵守本规章。
欢迎您加入本站点参与交流和讨论，本站点为社区，为维护网上公共秩序和社会稳定，请您自觉遵守以下条款：

一、不得利用本站危害国家安全、泄露国家秘密，不得侵犯国家社会集体的和公民的合法权益，不得利用本站制作、复制和传播下列信息：
　（一）煽动抗拒、破坏宪法和法律、行政法规实施的；
　（二）煽动颠覆国家政权，推翻社会主义制度的；
　（三）煽动分裂国家、破坏国家统一的；
　（四）煽动民族仇恨、民族歧视，破坏民族团结的；
　（五）捏造或者歪曲事实，散布谣言，扰乱社会秩序的；
　（六）宣扬封建迷信、淫秽、色情、赌博、暴力、凶杀、恐怖、教唆犯罪的；
　（七）公然侮辱他人或者捏造事实诽谤他人的，或者进行其他恶意攻击的；
　（八）损害国家机关信誉的；
　（九）其他违反宪法和法律行政法规的；
　（十）进行商业广告行为的。

二、互相尊重，对自己的言论和行为负责。
三、禁止在申请用户时使用相关本站的词汇，或是带有侮辱、毁谤、造谣类的或是有其含义的各种语言进行注册用户，否则我们会将其删除。
四、禁止以任何方式对本站进行各种破坏行为。
五、如果您有违反国家相关法律法规的行为，本站概不负责，您的登录信息均被记录无疑，必要时，我们会向相关的国家管理部门提供此类信息。
							        </pre>
							      </div>
							    </div>
							  </div>
							</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<footer id="site-footer">
  <div class="footer">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-md-4 clearfix footer-col">
          <div class="col-lx-12 text-center">
            <img id="footer-logo" src="./img/phylab_logo_single.svg"/>
            <div class="footer-slogan">PhyLab</div>
          </div>
          <div class="col-xs-4">
            <div class="social-item footer-weixin-item">
              <i class="fa fa-weixin" aria-hidden="true"></i>
              <div class="footer-weixin">
                <img src="./img/1447574686560.jpg">
              </div>
            </div>
          </div>
          <div class="col-xs-4">
            <div class="social-item footer-qq-item">
              <i class="fa fa-qq"></i>
              <div class="footer-qq">
                QQ: 229407702 (实验网站交流群)
              </div>
            </div>
          </div>
          <div class="col-xs-4">
            <div class="social-item footer-blog-item">
              <a href="http://www.cnblogs.com/Default1406/" target="_blank ">
                <i class="fa fa-rss"></i>
              </a>
              <div class="footer-blog">
                Default2014
              </div>
            </div>
          </div>
        </div>
        <div class="col-xs-6 col-sm-3 col-md-2 footer-col text-center">
          <div class="col-title ">团队</div>
          <a href="#" target="_blank">关于我们</a><br>
          <a href="#" target="_blank">联系我们</a><br>
          <a href="http://www.cnblogs.com/Default1406/" target="_blank ">加入我们</a><br>
          <a href="http://www.cnblogs.com/Default1406/" target="_blank ">技术博客</a><br>
        </div>
        <div class="col-xs-6 col-sm-3 col-md-2 footer-col text-center">
          <div class="col-title ">合作</div>
          <a href="#" target="_blank ">上传资源</a><br>
          <a href="#" target="_blank ">教师合作</a><br>
          <a href="#" target="_blank ">友情链接</a>
        </div>
        <div class="col-xs-6 col-sm-3 col-md-2 footer-col text-center">
          <div class="col-title ">模块</div>
          <a href="#" target="_blank ">实验</a><br>
          <a href="#" target="_blank ">社区</a><br>
          <a href="#" target="_blank ">反馈</a><br>
        </div>
        <div class="col-xs-6 col-sm-3 col-md-2 footer-col text-center">
          <div class="col-title ">支持实验</div>
          <a href="#" target="_blank ">1011</a><br>
          <a href="#" target="_blank ">1011</a><br>
          <a href="#" target="_blank ">1011</a><br>
          <a href="#" target="_blank ">1011</a><br>
          <a href="#" target="_blank ">1011</a><br>
          <a href="#" , target="_blank ">全部</a>
        </div>
      </div>
    </div>
    <div class="text-center copyright">
      <span>Copyright @2016-2016 物理实验报告平台</span>
    </div>
  </div>
</footer>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="./js/bootstrap.min.js"></script>
<!--自定义js脚本-->
<script src="./js/regist.js"></script>
</body>
</html>
