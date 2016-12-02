@extends('layout.main')
@section('contents')

  <div id="lab-console" class="row" style="margin-top: 50px;background-color: rgb(228, 228, 228);" >
    <div id="report-data" class="col-xs-12 col-md-3"  style="padding-right: 1px;margin-top: 10px;background-color: transparent;">
      <div id="lab-container" class="container" style="background-color: transparent;">
        <div class="lab-nav-container" style="border-top-left-radius: 2px;border-top-right-radius: 2px;padding: 0;background-color: transparent;">
          <div class="nav nav-tabs row" role="tablist" style="border-radius: inherit;padding-top: 2px;padding-bottom: 2px;background-color: transparent;">
            <h1 class="text-left col-md-12">
              <span>物理实验<small>数据报告中心</small></span>
              <hr style="border-color: #08c093;border-width: 2px;margin: 0px">
            </h1>
          </div>
        </div>
        <div class="container tab-content panel" style="border-bottom-left-radius: 2px;border-bottom-right-radius: 2px;margin: 5px 0 5px 0;height: 720px">
          <div class="panel-heading btn btn-success dropdown-toggle" id="lab-name" data-toggle="modal" data-target="#lab-select-modal" style="width: 100%;border-top-left-radius: 4px;border-top-right-radius: 4px;background-color: #08c093;">
            点击选择实验
          </div>
          <div class="modal fade" id="lab-select-modal" tabindex="-1" role="dialog" aria-labelledby="labSelectModal" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                  <h4 class="modal-title" id="lab-select-tittle">实验选择</h4>
                </div>
                <div class="modal-body">
                  <div class="panel-group" id="lab-list" role="tablist" aria-multiselectable="true">

                    <div class="panel panel-default" id="lab-group-1011">
                      <div class="panel-heading btn" id="lab-1011-heading" role="tab">
                        <h4 class="panel-title">
                          <div data-toggle="collapse" data-parent="#accordion" href="#lab-1011-collapse" aria-expanded="true" aria-controls="lab-1011-collapse">
                            1011 力学系列实验
                          </div>
                        </h4>
                      </div>
                      <div class="panel-collapse collapse list-group in" id="lab-1011-collapse" role="tabpanel" aria-labelledby="lab-1011-heading">
                        <li class="list-group-item btn" id="lab-1010113">1010113 拉伸法测钢丝弹性模量</li>
                        <li class="list-group-item btn" id="lab-1010212">1010212 扭摆法测量转动惯量</li>
                      </div>
                    </div>

                    <div class="panel panel-default" id="lab-group-1021">
                      <div class="panel-heading btn" id="lab-1021-heading" role="tab">
                        <h4 class="panel-title">
                          <div data-toggle="collapse" data-parent="#accordion" href="#lab-1021-collapse" aria-expanded="false" aria-controls="lab-1021-collapse">
                            1021 热学系列实验
                          </div>
                        </h4>
                      </div>
                      <div class="panel-collapse collapse list-group" id="lab-1021-collapse" role="tabpanel" aria-labelledby="lab-1021-heading">
                        <li class="list-group-item btn" id="lab-1020113">1020113 测量冰的溶解热实验</li>
                        <li class="list-group-item btn" id="lab-1020212">1020212 电热法测量焦耳热功当量实验</li>
                      </div>
                    </div>

                    <div class="panel panel-default" id="lab-group-1031">
                      <div class="panel-heading btn" id="lab-1031-heading" role="tab">
                        <h4 class="panel-title">
                          <div data-toggle="collapse" data-parent="#accordion" href="#lab-1031-collapse" aria-expanded="false" aria-controls="lab-1031-collapse">
                            1031 示波器的应用
                          </div>
                        </h4>
                      </div>
                      <div class="panel-collapse collapse list-group" id="lab-1031-collapse" role="tabpanel" aria-labelledby="lab-1031-heading">
                        <li class="list-group-item btn" id="lab-1030113">1030113 模拟示波器的使用（必做）</li>
                      </div>
                    </div>

                    <div class="panel panel-default" id="lab-group-1061">
                      <div class="panel-heading btn" id="lab-1061-heading" role="tab">
                        <h4 class="panel-title">
                          <div data-toggle="collapse" data-parent="#accordion" href="#lab-1061-collapse" aria-expanded="false" aria-controls="lab-1061-collapse">
                            1061 薄透镜和单球面镜焦距的测量
                          </div>
                        </h4>
                      </div>
                      <div class="panel-collapse collapse list-group" id="lab-1061-collapse" role="tabpanel" aria-labelledby="lab-1061-heading">
                        <li class="list-group-item btn" id="lab-1060111">1060111 物距像距法测量透镜焦距</li>
                        <li class="list-group-item btn" id="lab-1060213">1060213 自准直法测量透镜焦距</li>
                        <li class="list-group-item btn" id="lab-1060312">1060312 共轭法测量凸透镜焦距</li>
                        <li class="list-group-item btn" id="lab-1060412">1060412 单球面镜焦距的测量</li>
                      </div>
                    </div>

                    <div class="panel panel-default" id="lab-group-1071">
                      <div class="panel-heading btn" id="lab-1071-heading" role="tab">
                        <h4 class="panel-title">
                          <div data-toggle="collapse" data-parent="#accordion" href="#lab-1071-collapse" aria-expanded="false" aria-controls="lab-1071-collapse">
                            1071 分光仪的调整及其应
                          </div>
                        </h4>
                      </div>
                      <div class="panel-collapse collapse list-group" id="lab-1071-collapse" role="tabpanel" aria-labelledby="lab-1071-heading">
                        <li class="list-group-item btn" id="lab-1070212">1070212 测量三棱镜的顶角（必选）</li>
                        <li class="list-group-item btn" id="lab-1070312">1070312 最小偏向角法测量棱镜的折射率</li>
                      </div>
                    </div>

                    <div class="panel panel-default" id="lab-group-1081">
                      <div class="panel-heading btn" id="lab-1081-heading" role="tab">
                        <h4 class="panel-title">
                          <div data-toggle="collapse" data-parent="#accordion" href="#lab-1081-collapse" aria-expanded="false" aria-controls="lab-1081-collapse">
                            1081 光的干涉实验I（分波阵面法）1
                          </div>
                        </h4>
                      </div>
                      <div class="panel-collapse collapse list-group" id="lab-1081-collapse" role="tabpanel" aria-labelledby="lab-1081-heading">
                        <li class="list-group-item btn" id="lab-1080114">1080114 激光双棱镜干涉</li>
                      </div>
                    </div>

                    <div class="panel panel-default" id="lab-group-1082">
                      <div class="panel-heading btn" id="lab-1082-heading" role="tab">
                        <h4 class="panel-title">
                          <div data-toggle="collapse" data-parent="#accordion" href="#lab-1082-collapse" aria-expanded="false" aria-controls="lab-1082-collapse">
                            1082 光的干涉实验I（分波阵面法）2
                          </div>
                        </h4>
                      </div>
                      <div class="panel-collapse collapse list-group" id="lab-1082-collapse" role="tabpanel" aria-labelledby="lab-1082-heading">
                        <li class="list-group-item btn" id="lab-1080215">1080215 钠光双棱镜干涉</li>
                        <li class="list-group-item btn" id="lab-1080225">1080225 钠光劳埃镜干涉</li>
                      </div>
                    </div>

                    <div class="panel panel-default" id="lab-group-1091">
                      <div class="panel-heading btn" id="lab-1091-heading" role="tab">
                        <h4 class="panel-title">
                          <div data-toggle="collapse" data-parent="#accordion" href="#lab-1091-collapse" aria-expanded="false" aria-controls="lab-1091-collapse">
                            1091 光的干涉实验II（分振幅法）
                          </div>
                        </h4>
                      </div>
                      <div class="panel-collapse collapse list-group" id="lab-1091-collapse" role="tabpanel" aria-labelledby="lab-1091-heading">
                        <li class="list-group-item btn" id="lab-1090114">1090114 钠光劳埃镜干涉</li>
                        <li class="list-group-item btn" id="lab-1090212">1090212 牛顿环干涉</li>
                        <li class="list-group-item btn" id="lab-1090312">1090312 劈尖干涉</li>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane panel-body active" role="tabpanel" id="labdoc" style="overflow-x: auto;overflow-y: auto;;text-align: center;">
            <h1 style="margin: 65% 0;">未选择子实验</h1>
          </div>
        </div>
        <div class="labdoc-btns" >
          <button id="button-view-preparation" class="btn btn-success" style="margin-bottom: 5px;" disabled>
            <i class="fa fa-file-pdf-o"></i>查看预习报告
          </button>
          <button id="button-generate-report" class="btn btn-success" style="margin-bottom: 5px;" type="submit" disabled>
            <i class="fa fa-area-chart"></i>生成数据报告
          </button>
        </div>
      </div>
    </div>
    <div id="doc" class="col-xs-12 col-md-9" style="padding-left: 1px;;margin-top: 20px;margin-bottom: 30px">
      <div class="panel-group" id="lab-report" style="margin-right: 15px;">
        <div class="panel panel-default pannel-autoscroll">
          <div class="panel-heading"  style="border: solid;border-width: 0px;">
            <div class="panel-title">
              <h4 class="panel-title text-center">
                <span id="lab-status" class="badge">物理实验选择策略</span>
              </h4>
            </div>
          </div>
          <div class="panel-body" style="padding:5px;">
            <div id="firefox_pdf" style="width: 100%; height: 100%;display: none;">
              <object data="./prepare_pdf/phylab_test.pdf" type="application/pdf" id="pdf_object" style="width:100%;height:100%;min-height:780px;">
                <embed src="./prepare_pdf/phylab_test.pdf" type="application/pdf" id="pdf_embed">
              </object>
            </div>
            <div id="chrom_pdf" style="width:100%;height:100%;min-height:780px;display: none;">
              <object width="100%" height="100%" data="./prepare_pdf/phylab_test.pdf" type="application/pdf"></object>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer id="site-footer">
    <div class="text-center copyright" style="margin-top: 0">
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
  <script>
        check();
  </script>
</body>
</html>
@stop