@extends('layout.main')

@section('contents')
<script>
	$(function(){
		check();
	});
</script>

<div id="lab-console" class="row" style="margin-top: 50px;" >
  <div class="col-xs-12 col-md-3"  style="padding-right: 1px;margin-top: 1px;">
    <div id="lab-container">
      <div class="lab-nav-container" style="border-top-left-radius: 2px;border-top-right-radius: 2px;">
        <div class="nav nav-tabs row" role="tablist" style="border-radius: inherit;padding-top: 2px;padding-bottom: 2px">
          <h1 class="text-left col-md-12">
            <span style="color: white">物理实验<small>数据报告中心</small></span>
            <hr style="border-color: #08c093;border-width: 2px;margin: 0px">
          </h1>
          <div class="btn-group labdoc-btns col-xs-12" id="lab-group-select">
            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" style="width: 100%;margin-bottom: 1px;">
              实验分组 <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
              <li id="lab-group-1011"><a href="#">1011 力学系列实验</a></li>
              <li id="lab-group-1021"><a href="#">1021 热学系列实验</a></li>
              <li id="lab-group-1031"><a href="#">1031 示波器的应用</a></li>
              <li id="lab-group-1061"><a href="#">1061 薄透镜和单球面镜焦距的测量</a></li>
              <li id="lab-group-1071"><a href="#">1071 分光仪的调整及其应用</a></li>
              <li id="lab-group-1081"><a href="#">1081 光的干涉实验I（分波阵面法）1</a></li>
              <li id="lab-group-1082"><a href="#">1082 光的干涉实验I（分波阵面法）2</a></li>
              <li id="lab-group-1091"><a href="#">1091 光的干涉实验II（分振幅法）</a></li>
            </ul>
            <div style="display:none" id="back_info">
              @foreach ($reportTemplates as $rept)
              <a index="{{$rept['experimentId']}}" prepareLink="{{$rept['prepareLink']}}" db-id="{{$rept['id']}}"></a>
              @endforeach
            </div>
          </div>
          <div class="btn-group labdoc-btns col-xs-12"  id="lab-select">
            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" style="width: 100%;margin-top: 1px">
              题目编号 <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
              <li id="default-lab-name"><a href="#">未选择实验分组</a></li>
              <div class="lab-name-list" id="lab-1011-list">
                <li id="lab-1010113"><a href="#">1010113 拉伸法测钢丝弹性模量</a></li>
                <li id="lab-1010212"><a href="#">1010212 扭摆法测量转动惯量</a></li>
              </div>
              <div class="lab-name-list" id="lab-1021-list">
                <li id="lab-1020113"><a href="#">1020113 测量冰的溶解热实验</a></li>
                <li id="lab-1020212"><a href="#">1020212 电热法测量焦耳热功当量实验</a></li>
              </div>
              <div class="lab-name-list" id="lab-1031-list">
                <li id="lab-1030113"><a href="#">1030113 模拟示波器的使用（必做）</a></li>
              </div>
              <div class="lab-name-list" id="lab-1061-list">
                <li id="lab-1060111"><a href="#">1060111 物距像距法测量透镜焦距</a></li>
                <li id="lab-1060213"><a href="#">1060213 自准直法测量透镜焦距</a></li>
                <li id="lab-1060312"><a href="#">1060312 共轭法测量凸透镜焦距</a></li>
                <li id="lab-1060412"><a href="#">1060412 单球面镜焦距的测量</a></li>
              </div>
              <div class="lab-name-list" id="lab-1071-list">
                <li id="lab-1070212"><a href="#">1070212 测量三棱镜的顶角（必选）</a></li>
                <li id="lab-1070312"><a href="#">1070312 最小偏向角法测量棱镜的折射率</a></li>
              </div>
              <div class="lab-name-list" id="lab-1081-list">
                <li id="lab-1080114"><a href="#">1080114 激光双棱镜干涉</a></li>
              </div>
              <div class="lab-name-list" id="lab-1082-list">
                <li id="lab-1080215"><a href="#">1080215 钠光双棱镜干涉</a></li>
                <li id="lab-1080225"><a href="#">1080225 钠光劳埃镜干涉</a></li>
              </div>
              <div class="lab-name-list" id="lab-1091-list">
                <li id="lab-1090114"><a href="#">1090114 钠光劳埃镜干涉</a></li>
                <li id="lab-1090212"><a href="#">1090212 牛顿环干涉</a></li>
                <li id="lab-1090312"><a href="#">1090312 劈尖干涉</a></li>
              </div>
            </ul>
          </div>
        </div>
      </div>
      <div class="container tab-content" style="border-bottom-left-radius: 2px;border-bottom-right-radius: 2px;margin-bottom: 2px;">
        <div class="tab-pane active " role="tabpanel" id="labdoc" style="overflow-x: auto;overflow-y: auto;max-height: 680px;text-align: center;">
          <h1 style="margin: 200px 0;">未选择子实验</h1>
        </div>
        <div class="labdoc-btns">
          <button id="button-view-preparation" class="btn btn-success" style="margin-bottom: 5px;" disabled>
            <i class="fa fa-file-pdf-o"></i>查看预习报告
          </button>
          <button id="button-generate-report" class="btn btn-success" style="margin-bottom: 5px;" type="submit" disabled>
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
              <span id="lab-status" class="badge">物理实验选择策略</span>
            </h4>
          </div>
        </div>
        <div class="panel-body" style="padding:5px;">
          <div id="firefox_pdf" style="width: 100%; height: 100%; display: block;">
            <object data="./prepare_pdf/phylab_test.pdf" type="application/pdf" id="pdf_object" style="width:100%;height:100%;min-height:800px;">
              <embed src="./prepare_pdf/phylab_test.pdf" type="application/pdf" id="pdf_embed">
            </object>
          </div>
          <div id="chrom_pdf" style="width:100%;height:92%;min-height:500px;display:none"></div>
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

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="./js/bootstrap.min.js"></script>
<script src="./js/global.js"></script>
<script src="./js/pdfobject.js"></script>
<script src="./js/xmlInteraction.js"></script>
<script src="./js/star.js"></script>
<script src="./js/test.js"></script>
<script src="./js/statistics.js"></script>
<script src="./js/reportCore.js"></script>
</body>
</html>
@stop