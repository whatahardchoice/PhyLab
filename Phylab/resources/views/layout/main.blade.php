<!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="save" content="history"> 
  <title>PhyLab</title>

  <!-- Bootstrap -->
  <link href="./css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="./css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <link href="./css/styles.css" rel="stylesheet" type="text/css">
  <link href="./css/phylab.css" rel="stylesheet" type="text/css">

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
  <script src="./ckeditor/ckeditor.js"></script>
  <style>
    @media only screen and (max-width: 992px) {
      .navbar-banner {
        background-image: url("../img/phylab_navbar_banner5.png");
        background-size: cover;
      }
    }
  </style>
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
              <a data-toggle="modal" @if (!$auth) href="{{URL::route('login')}}" @else href="{{URL::route('report')}}"@endif>实验</a>
            </li>
            <li>
              <a data-toggle="modal" @if (!$auth) href="{{URL::route('login')}}" @else href="{{URL::route('wc_login')}}"@endif>社区</a>
            </li>
            <li>
              <a href="{{URL::route('tools')}}">工具</a>
            </li>
			<li>
              <a href="{{URL::route('tiku')}}">题库</a>
            </li>
            <li>
              <a href="#">反馈</a>
            </li>
          </ul>
          <div class="navbar-right btns">
            @if (!$auth)
            <a class="btn btn-default navbar-btn sign-in" href="{{URL::route('login')}}">登录</a>
            <a class="btn btn-default navbar-btn sign-up" href="{{URL::route('register')}}">注册</a>
            @else
            <a id="username" class="btn btn-default navbar-btn username" href="#">{{$username}}</a>
            <a class="btn btn-default navbar-btn sign-out" href="{{URL::route('logout')}}">登出</a>
            @endif
          </div>
        </div>
      </div>
    </nav>
  </header>

@yield('contents')