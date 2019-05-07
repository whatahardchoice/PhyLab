<!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="save" content="history"> 
  <title>PhyLab</title>

  <!-- Bootstrap jquery-->
  <script src="./js/bootstrap.min.js"></script>
  <link href="./css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="./css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <link href="./css/styles.css" rel="stylesheet" type="text/css">
  <link href="./css/phylab.css" rel="stylesheet" type="text/css">
  <script src="./js/jquery-2.1.4.js"></script>

  <!-- include summernote css/js -->
  <link href="./css/summernote.css" rel="stylesheet">
  <script src="./js/summernote.js"></script>
  <script src="./js/summernote-zh-CN.js" ></script>


  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries-->
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
    <div class="navbar navbar-inverse navbar-fixed-top header" role="navigation">

        <div class="navbar-header" >
            <img  id="header-logo" src="./img/phylab_logo_single.svg"/>
			<a class="navbar-brand" href="{{URL::route('index')}}"><span>PhyLab</span></a>


          <button class="navbar-toggle" type="button" data-toggle="collapse"  data-target="#navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>

        <nav class="collapse navbar-collapse navbar-responsive-collapse" id="navbar-collapse">
		<div class="container">
          <ul class="nav navbar-nav navbar-left">
            <li>
              <a style="font-size:16px" data-toggle="modal" @if (!$auth) href="{{URL::route('login')}}" @else href="{{URL::route('report')}}"@endif>实验</a>
            </li>
            <li>
              <a style="font-size:16px" data-toggle="modal" @if (!$auth) href="{{URL::route('login')}}" @else href="{{URL::route('wc_login')}}"@endif>社区</a>
            </li>
            <li>
              <a style="font-size:16px" href="{{URL::route('tools')}}">工具</a>
            </li>
            <li>
              <a style="font-size:16px" href="#">反馈</a>
            </li>
            @if ($admin)
            <li>
              <a style="font-size:16px" href="{{URL::route('console')}}">控制台</a>
            </li>
            @endif

		
          </ul>
          <div class="navbar-right btns">
            @if (!$auth)
            <a class="btn btn-default navbar-btn sign-in " href="{{URL::route('login')}}" >登录</a>
            <a class="btn btn-default navbar-btn sign-up" href="{{URL::route('register')}}">注册</a>
            @else
            <a id="username" class="btn btn-default navbar-btn username" href="{{URL::route('user')}}">{{$username}}</a>
            <a class="btn btn-default navbar-btn sign-out" href="{{URL::route('logout')}}">登出</a>
            @endif
          </div>
        </div>
		</nav>


    </div>
  </header>


@yield('contents')