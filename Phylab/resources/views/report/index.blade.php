<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PhyLabReportCore</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/seven-style.css">
	<link rel="stylesheet" href="./css/loading.css">
	<link href="../css/font-awesome.min.css" rel="stylesheet">
  <link href="../css/styles.css" rel="stylesheet">
  <link href="../css/phylab.css" rel="stylesheet">
</head>
<body>
<div class="wrapper wrapper_navbar_top">
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
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
			<ul class="nav navbar-nav navbar-left">
				<li>
           <a @if (!$auth) href="{{URL::route('login')}}" @else href="{{URL::route('report')}}"@endif>实验</a>
        </li>
				<li><a href="{{URL::route('wc_login')}}">社区</a></li>
                <li class="dropdown active">
					<a href="##" data-toggle="dropdown" class="dropdown-toggle">服务<span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li><a href="{{URL::route('report')}}"><span class="glyphicon glyphicon-flag"></span>&nbsp实验报告中心</a></li>
						<li><a href="{{URL::route('tools')}}"><span class="glyphicon glyphicon-wrench"></span>&nbsp 实用小工具</a></li>
						<li class="disabled"><a>其他功能</a></li>
					</ul>
				</li>
				<li><a href="##" data-toggle="modal" data-target="#mymodal-party">反馈</a></li>
            </ul>
			<ul class="nav navbar-nav navbar-right">
                <li><a data-toggle="modal" data-target="#mymodal-star" href="#">{{$username}}的收藏夹</a></li>
                <li><a href="{{URL::route('logout')}}">登出</a></li>
            </ul>
		</div>
	</nav>
</div>
<!--star modal-->
<div id="lab-console" class="row">
  <div class="col-xs-12 col-md-3"  style="padding-right: 1px;margin-top: 1px;">
    <div id="lab-container">
      <div class="lab-nav-container" style="border-top-left-radius: 2px;border-top-right-radius: 2px;">
        <div class="nav nav-tabs row" role="tablist" style="border-radius: inherit;padding-top: 2px;padding-bottom: 2px">
          <h1 class="text-left col-md-12">
						<span style="color: white">物理实验<small>数据报告中心</small></span>
            <hr style="border-color: #08c093;border-width: 2px;margin: 0px">
          </h1>
          <div class="btn-group labdoc-btns col-xs-6 col-md-6">
            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" style="width: 100%;margin-bottom: 1px;">
              实验分组 <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
              <li><a href="#">1011</a></li>
            </ul>
          </div>
          <div class="btn-group labdoc-btns col-xs-6 col-md-6">
            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" style="width: 100%;margin-top: 1px">
              题目编号 <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
              <li><a href="#">1010113</a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="container tab-content" style="border-bottom-left-radius: 2px;border-bottom-right-radius: 2px;margin-bottom: 2px;">
        <div class="tab-pane active " role="tabpanel" id="labdoc" style="overflow-x: auto;overflow-y: auto;max-height: 704px;text-align: center;">
          <h1>未选择子实验</h1>
        </div>
        <div class="labdoc-btns">
          <button class="btn btn-success" style="margin-bottom: 5px;">
            <i class="fa fa-file-pdf-o"></i>查看预习报告
          </button>
          <button class="btn btn-success" style="margin-bottom: 5px;">
            <i class="fa fa-area-chart"></i>生成数据报告
          </button>
        </div>
      </div>
    </div>
  </div>
  <div id="doc" class="col-xs-12 col-md-9 hidden-xs" style="padding-left: 1px;height: 100%;">
    <div class="panel-group" id="lab-report">
      <div class="panel panel-default pannel-autoscroll">
        <div class="panel-heading"  style="background-color: #2b2f36;border: solid;border-width: 2px;border-color: black;">
          <div class="panel-title">
            <h4 class="panel-title text-center">
              <span><span id="lab-status" class="badge">实验报告&nbsp; <span id="lab-text" title="false">1011</span>&nbsp; 数据</span></span>
            </h4>
          </div>
        </div>
        <div class="panel-body" style="padding:5px;">
          <div id="firefox_pdf" style="width: 100%; height: 100%; display: block;">
            <object data="pdf/Chapter-3.pdf" type="application/pdf" id="pdf_object" style="width:100%;height:100%;min-height:800px;">
              <embed src="pdf/Chapter-3.pdf" type="application/pdf" id="pdf_embed">
            </object>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<footer id="site-footer">
  <div class="text-center copyright">
    <span>Copyright @2016-2016 物理实验报告平台</span>
  </div>
</footer>

<script src="./js/jquery-2.1.4.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script src="./js/global.js"></script>
<script src="./js/reportCore.js"></script>
<script src="./js/pdfobject.js"></script>
<script src="./js/xmlInteraction.js"></script>
<script src="./js/star.js"></script>
<script src="./js/test.js"></script>
<script src="./js/statistics.js"></script>
</body>
</html>
