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
</head>
<body>
<header id="site-header">
  <nav class="navbar navbar-default navbar-fixed-top header" role="navigation">
    <div class="container">
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
      <div class="collapse navbar-collapse navbar-responsive-collapse">
        <ul class="nav navbar-nav">
          <li>
            <a  href="report/data.html">实验</a>
          </li>
          <li>
            <a href="index">社区</a>
          </li>
          <li>
            <a href="index">反馈</a>
          </li>
        </ul>
        <div class="navbar-right btns">
          @if (!$auth)
          <a class="btn btn-default navbar-btn sign-in" href="{{URL::route('login')}}">登录</a>
          <a class="btn btn-default navbar-btn sign-up" href="{{URL::route('register')}}">注册</a>
          @else
          <a class="btn btn-default navbar-btn sign-out" href="{{URL::route('logout')}}">登出</a>
          @endif
        </div>
      </div>
    </div>
  </nav>
</header>

<div class="navbar-banner layout-no-margin-top">
  <div class="banner-container">
    <div class="container">
      <div class="row">
        <div class="hidden-xs col-md-7">
          <div class="container-title">
            &nbsp;PhyLab<br/>物理数据中心
          </div>
        </div>
        <div class="col-xs-12 col-md-5" @if (!$auth) style="display: block" @else style="display: none" @endif>
          <form method="post">
            <div class="form-group">
              <input class="form-control" name="name" id="name" type="text" placeholder="昵称">
            </div>
            <div class="form-group">
              <input class="form-control" name="email" id="email" type="email" placeholder="邮箱">
            </div>
            <div class="form-group">
              <input class="form-control" name="password" id="password " type="password" placeholder="密码">
            </div>
            <button class="btn register" type="submit">注册</button>
            <p class="tip">点击“注册”，表示您已经同意我们的隐私条款</P>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container layout layout-margin-top">
  <div class="row">
    <div class="col-md-12 layout-body">
      <div class="login-container">
        <div class="col-md-8 col-md-offset-2">
          <div class="login-title">
            登录
          </div>
        </div>
        <div class="col-md-6 col-md-offset-3">
          <div class="login-body">
            <div class="alert alert-danger text-center" role="alert" id="login-alert">
              <i class="fa fa-remove"></i><span id="alert-message">用户名或密码错误</span>
            </div>
            <form id="login_form" class="form-horizontal" role="form" method="post" action="{{URL::route('login')}}">
                {!! csrf_field() !!}
			          <div class="alert alert-danger" role="alert" id="loginAlert" style="display:none;height:30px;padding:5px;">
                    <span class="glyphicon glyphicon-remove-sign"></span><span id="errorMessage">&nbsp 用户名或密码错误!</span>
                </div>
                <div class="form-group login">
                	<div class="input-group">
                  	<div class="input-group-addon">
                    	<i class="fa fa-envelope"></i>
                  	</div>
                  	<input class="form-control" id="login" name="login" placeholder="请输入您的登录邮箱" type="email" value="">
                </div>
              	</div>
                <div class="form-group password">
                	<div class="input-group">
                  	<div class="input-group-addon">
                    	<i class="fa fa-lock"></i>
                  	</div>
                  	<input class="form-control" id="password" name="password" placeholder="请输入密码" type="password" value="">
                	</div>
              	</div>
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-4">
                        <div class="checkbox">
                            <label><input type="checkbox" id="remember" name="remember">记住密码</input></label>
                        </div>
                    </div>
                    <div class="col-md-offset-3 col-md-3" style="float:right"><a href="/password/email">忘记密码?</a></div>
                </div>
                <div class="submit">
                <a class="btn btn-primary" style="width: 100%" onclick="Post_login()" id="login-submit" name="submit" type="submit" value="登录">
              	</div>
            </form>
            <div class="login-footer">
              <span><a href="/password/email">忘记密码？</a><a href="{{URL::route('register')}}">注册新账号</a></span>
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
                <img src="http://www.buaaphylab.com/img/1447574686560.jpg">
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
        <div class="col-xs-6 col-sm-3 col-md-2 footer-col ">
          <div class="col-title ">团队</div>
          <a href="#" target="_blank">关于我们</a><br>
          <a href="#" target="_blank">联系我们</a><br>
          <a href="http://www.cnblogs.com/Default1406/" target="_blank ">加入我们</a><br>
          <a href="http://www.cnblogs.com/Default1406/" target="_blank ">技术博客</a><br>
        </div>
        <div class="col-xs-6 col-sm-3 col-md-2 footer-col ">
          <div class="col-title ">合作</div>
          <a href="#" target="_blank ">上传资源</a><br>
          <a href="#" target="_blank ">教师合作</a><br>
          <a href="#" target="_blank ">友情链接</a>
        </div>
        <div class="col-xs-6 col-sm-3 col-md-2 footer-col ">
          <div class="col-title ">模块</div>
          <a href="#" target="_blank ">实验</a><br>
          <a href="#" target="_blank ">社区</a><br>
          <a href="#" target="_blank ">反馈</a><br>
        </div>
        <div class="col-xs-6 col-sm-3 col-md-2 footer-col ">
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
<script src="./js/global.js"></script>
<script src="./js/login.js"></script>
<script type="text/javascript">$('.carousel').carousel()</script>
<script src="./js/statistics.js"></script>
</body>
</html>
