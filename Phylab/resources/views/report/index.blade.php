﻿@extends('layout.main')
@section('contents')

  <div id="lab-console" class="row" style="margin-top: 30px;background-color: rgb(228, 228, 228);" >
    <div id="report-data" class="col-xs-12 col-md-4"  style="padding-right: 1px;margin-top: 10px;background-color: transparent;">
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
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane panel-body active" role="tabpanel" id="labdoc" style="overflow-x: auto;overflow-y: auto;;text-align: center;">
            <h1 style="margin: 50% 0;">未选择子实验</h1>
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
    <div id="doc" class="col-xs-12 col-md-8" style="padding-left: 1px;;margin-top: 20px;margin-bottom: 30px">
      <div class="panel-group" id="lab-report" style="margin-right: 15px;">
        <div class="panel panel-default pannel-autoscroll">
          <div class="panel-heading"  style="border: solid;border-width: 0px;height: 40px;">
            <div class="panel-title row">
              <div class="btn-group col-xs-12 col-md-5">
                <button id="collection" class="btn btn-success" data-toggle="modal" data-target="#collection-folder" style="border-radius: 4px 0 0 4px;">
                  <i class="fa fa-folder"></i>
                  收藏夹
                  <span id="report-num" class="badge"></span>
                </button>
                <div class="modal fade" id="collection-folder" tabindex="-1" role="dialog" aria-labelledby="collection-folder-label" aria-hidden="true">
                  <div class="modal-dialog" style="width: 60%;">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="collection-folder-label">实验报告收藏夹</h4>
                      </div>
                      <div class="modal-body" style="width: auto;height: auto;">
                        <iframe id="collection-iframe" src="{{URL::route('star')}}" style="width:100%;height: 320px;" frameborder="0"></iframe>
                      </div>
                    </div>
                  </div>
                </div>
                <button id="collect-report" class="btn btn-success" style="border-radius: 0 4px 4px 0;">
                  <span class="sr-only">y</span>
                  <i class="fa fa-bookmark-o"></i>
                  <span id="collect-report-text">收藏此报告</span>
                </button>
              </div>
              <div class="col-md-1 hidden-md">
                <h4 class="panel-title text-center" style="position: absolute; left: 45%;">
                  <span id="lab-status" class="badge">物理实验选择策略</span>
                </h4>
              </div>
            </div>
          </div>
          <div class="panel-body" style="padding:5px;">
            <div id="wait-report">
              <i id="wait-report-spinner" class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
            </div>
            <div id="firefox_pdf" style="width: 100%; height: 100%;display: none;">
              <object data="./prepare_pdf/phylab_test.pdf" type="application/pdf" id="pdf_object" style="width:100%;height:100%;min-height:780px;">
                <embed src="./prepare_pdf/phylab_test.pdf" type="application/pdf" id="pdf_embed">
              </object>
            </div>
            <div id="chrom_pdf" style="width: 100%; height: 100%;display: none;">
              <object data="./prepare_pdf/phylab_test.pdf" type="application/pdf"></object>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="comment" style="border-width: 2px;border-color: black; background-color: rgb(228, 228, 228);padding-bottom: 10px;">
    <div style="margin: 0 15px;">
      <h1 style="margin-top: 0;">
        <span id="comment-area-title">评论区</span>
        <hr style="border-color: #08c093;border-width: 2px;margin: 0px">
      </h1>
    </div>
    <div class="row well" style="margin: 0 15px;padding-bottom: 5px;">
      <div class="col-xs-12 col-md-7 list-group" style="padding-left: 0;margin-bottom: 0px;">
        <div id="comment-area" style="height: 205px;">
          <h1 style="text-align: center">未选择子实验（无法显示评论区）</h1>
        </div>
        <div id="btn-group-comment-group" class="btn-group pagination pull-right" style="margin: 0;display: none;">
          <button id="button-prev-comment-group" type="button" class="btn btn-default" disabled>&laquo;</button>
          <button id="button-next-comment-group" type="button" class="btn btn-default" disabled>&raquo;</button>
        </div>
      </div>
      <div class="col-xs-12 col-md-5" style="padding-right: 0;">
        <div id="reply-area">
          <div id="reply-notice">
            <div id="reply-notice-check-group">
              <i id="reply-notice-check" class="fa fa-tag"></i>
              <span id="reply-notice-text">评论</span>
            </div>
          </div>
          <textarea rows="3" name="message" id="comment-editor" class="form-control autosize" placeholder="写下你的评论..."></textarea>
        </div>
        <button id="button-comment-reply" class="btn btn-success pull-right" disabled>
          回复
        </button>
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
  <script src="./js/jquery.cookie.js"></script>
  <script>
      var G_BASE_URL = '<?php echo url('/');?>';
      initReportPage();
  </script>
</body>
</html>
@stop