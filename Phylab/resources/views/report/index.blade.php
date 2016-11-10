<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PhyLabReportCore</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/seven-style.css">
	<link rel="stylesheet" href="./css/mystyle.css">
	<link rel="stylesheet" href="./css/loading.css">
</head>
<body onload="check()">
<div class="modal fade" id="mymodal-star">
    <div class="modal-dialog modal-lab">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title lead">报告收藏夹</h4>
            </div>
            <div class="well" style="padding:0px;">
                <iframe id="starIframe" src="{{URL::route('star')}}" style="width:100%;height:75%" frameborder="0"></iframe>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" id="mymodal-party">
	<div class="modal-dialog modal-lab" >
		<div class="modal-content">
			<div class="modal-head">
				<button type="button" class="close" data-dismiss="modal" style="height:50px;width:auto;"><span aria-hidden="true" style="font-size:30px;">&times;&nbsp;</span><span class="sr-only">Close</span></button>
				<h4 class="lead ch">&nbsp &nbsp 联系我们</h4>
			</div>
			<div class="modal-body well">
				<div class="container">
				<div class="row">
					<div class="col-md-4">
						<img class="img-responsive" src="./img/1447574686560.jpg"></img>
					</div>
					<div class="col-md-8">
						<br/><a href="##" class="Title-black" style="font-size:15px;" onclick="window.open('http://www.cnblogs.com/buaase','_blank');"><b>博客: http://www.cnblogs.com/buaase</b></a><hr/>
						<a href="mailto:buaase@126.com" class="Title-black" style="font-size:15px;"><b>邮箱: buaase@126.com</b></a><hr/>
						<a href="##" class="Title-black" style="font-size:15px;" onclick="window.open('http://qm.qq.com/cgi-bin/qm/qr?k=_8RvE1OthrVD1heTREc3i0HN8kXBx92_','_blank');"><b>QQ: 229407702(物理实验网站交流群)</b></a><br/><br/>
						<strong style="float:right">我们需要您的反馈！</strong>
					</div>
				</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="wrapper wrapper_navbar_top">
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="navbar-header">
		 　	<button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-responsive-collapse">
				<span class="sr-only">Toggle Navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<img src="./img/phylab_logo_single.svg" href="{{URL::route('index')}}" style="float:left;margin:0 0 0 20px;height:50px;"></img>
			<a class="navbar-brand" href="{{URL::route('index')}}" style="margin:0 40px 0 0px;">PhyLab</a>
		</div>
		<div class="collapse navbar-collapse navbar-responsive-collapse">
			<ul class="nav navbar-nav navbar-left">
				<li><a href="{{URL::route('index')}}"><span class="glyphicon glyphicon-home"></span>&nbsp主页</a></li>
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
<div class="wrapper wrapper_contents" style="position:relative;top:60px;">
	<div class="container-fluid" style="margin-left:50px;margin-right:50px;">
		<div class="row">
			<div class="col-md-3" style="padding-top:0px;padding-bottom:0px;">
				<div class="row">
					<h2 class="text-left">
						<span>物理实验
							<small>数据报告中心</small>
						</span>
						<hr/>
					</h2>
				</div>
				<div class="row" style="padding-top:0px;">
					<div class="panel-group">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h4 class="panel-title">
									<span class="glyphicon glyphicon-th-list">&nbsp </span>
									<a data-toggle="collapse" data-parent="#accordion" href="#lab_collapse">
										<span>实验选择<span class="caret" style="float:right;"></span></span>
									</a>
								</h4>
							</div>
							<div id="lab_collapse" class="panel-collapse collapse in">
								<div class="panel-body">
									<form class="form-inline container-fluid">
										<div class="row">
											<div class="form-group  col-md-7">
												<label for="InputLabIndex" class="sr-only">LabIndex</label>
												<input type="text" class="form-control lab_input" id="InputLabIndex" placeholder="请输入实验编号">
											 </div>
											<input type="button" class="btn btn-default-outline col-md-4" id="selectBtn" style="margin-left:10px;" onclick="selectBtnClick()"  value="Select"></input>
										</div>
									</form>
                                    <div style="display:none" id="back_info">
                                        @foreach ($reportTemplates as $rept)
                                        <a index="{{$rept['experimentId']}}" prepareLink="{{$rept['prepareLink']}}" db-id="{{$rept['id']}}"></a>
                                        @endforeach
                                    </div>
									<div class="alert alert-danger" role="alert" style="display:none;height:30px;padding:5px;">
										<span class="glyphicon glyphicon-remove-sign"></span><strong>&nbsp Error:</strong><span>&nbsp 请输入正确的实验序号!</span>
									</div>
									<div class="table-autoscroll" style="width:100%;height:29%;">
									   <table class="table table-condensed table-hover table-striped" style="width:100%;height:92%;">
									   <tbody class="table-small no-decoration">
										 @foreach ($reportTemplates as $rept)
                                         <tr>
                                           <td><a href="#" class="lab lab_index">{{$rept['experimentId']}}</a></td>
                                           <td><a href="#" class="lab lab_title" title="{{$rept['experimentId']}}">{{$rept['experimentName']}}</a></td>
                                         </tr>
                                         @endforeach
									   </tbody>
									 </table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row" style="padding-top:20px;">
					<button class="btn btn-lg btn-danger btn-block" id="importBtn" disabled="disabled" onclick="importBtnClick()"><span class="glyphicon glyphicon-edit"></span>&nbsp录入实验数据</button>
				</div>
				<div class="row">
					<div class="col-md-5" style="padding:0;padding-top:10px;">
						<button class="btn btn-lg btn-warning btn-block"  type="submit" id="collectBtn" link="" dbid="" disabled="disabled" onclick="collectBtnClick()"><span id="collectIco" class="glyphicon glyphicon-star-empty"></span>&nbsp <span id="collectText">收藏</span></button>
					</div>
					<div class="col-md-offset-1 col-md-6" style="padding:0;padding-top:10px;">
						<button class="btn btn-lg btn-info btn-block" type="submit" id="exportBtn" disabled="disabled" onclick="exportBtnClick()"><span class="glyphicon glyphicon-download-alt"></span>&nbsp生成报告</button>
					</div>
				</div>
				<div class="row" id="loading-container" style="padding-top:30px;display:none;">
					<ul class="loading-spinner">
						<li></li>
						<li></li>
						<li></li>
						<li></li>
					</ul>
					<h3 style="text-align:center;font-color:#233">LOADING</h3>
				</div>
				<br/><br/>
			</div>
			<div class="col-md-9" style="padding-left:30px;height:82%;">
				<div class="row"><br/></div>
				<div class="panel-group row" id="labReport" >
					<div class="panel panel-default pannel-autoscroll">
						<div class="panel-heading">
							<div class="panel-title">
								<h4 class="panel-title text-center">
									<span>实验报告&nbsp <span id="LabText" title="false"></span>&nbsp <span id="LabStatus" class="badge">帮助</span></span>
								</h4>
							</div>
						</div>
						<div class="panel-body" style="padding:5px;">
							<!--<iframe id="lab_window" src="lab_report.html" style="width:100%;height:92%;">
							</iframe>-->
                            <div id="firefox_pdf" style="width:100%;height:92%;min-height:500px;display:none;">
								<object data="./prepare_pdf/phylab_test.pdf" type="application/pdf" id="pdf_object"  style="width:100%;height:92%;min-height:480px;">
									<embed src="./prepare_pdf/phylab_test.pdf" type="application/pdf" id="pdf_embed"/>
								</object>
							</div>
							<div id="chrom_pdf" style="width:100%;height:92%;min-height:500px;display:none">
							</div>
						</div>
					</div><br/>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="wrapper wrapper_navbar_foot">
	<nav class="navbar navbar-inverse navbar-fixed-bottom" style="min-height:20px;">
		<div class="container-fluid">
			<ul class="nav navbar-nav navbar-right text-ch"  style="padding-top:0.25%;">
				<li class="text-white ch" style="float:right;opacity:0.7;font-size:13px">Developed By BUAA-SCSE 软件攻城队&nbsp &nbsp &nbsp &nbsp &nbsp </li>
			</ul>
		</div>
	</nav>
</div>

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
