@extends('layout.main')
@section('contents')
	<link rel="stylesheet" href="./codemirror/lib/codemirror.css"/>
	<link rel="stylesheet" href="./codemirror/addon/fold/foldgutter.css"/>
	<link rel="stylesheet" href="./codemirror/theme/monokai.css"/>

	<script src="./codemirror/lib/codemirror.js"></script>
	<script src="./codemirror/addon/fold/foldcode.js"></script>
	<script src="./codemirror/addon/fold/foldgutter.js"></script>
	<script src="./codemirror/addon/fold/brace-fold.js"></script>
	<script src="./codemirror/addon/fold/xml-fold.js"></script>
	<script src="./codemirror/addon/fold/markdown-fold.js"></script>
	<script src="./codemirror/addon/fold/comment-fold.js"></script>
	<script src="./codemirror/addon/edit/closebrackets.js"></script>
	<script src="./codemirror/addon/edit/matchbrackets.js"></script>
	<script src="./codemirror/mode/javascript/javascript.js"></script>
	<script src="./codemirror/mode/xml/xml.js"></script>
	<script src="./codemirror/mode/python/python.js"></script>
	<script src="./codemirror/mode/markdown/markdown.js"></script>
	<script src="./codemirror/mode/css/css.js"></script>
	<script src="./codemirror/mode/htmlmixed/htmlmixed.js"></script>
	
<script src="./codemirror/addon/search/searchcursor.js"></script>
<script src="./codemirror/addon/search/search.js"></script>
<script src="./codemirror/addon/dialog/dialog.js"></script>
<script src="./codemirror/addon/comment/comment.js"></script>
<script src="./codemirror/addon/wrap/hardwrap.js"></script>
<script src="./codemirror/keymap/sublime.js"></script>

  <div id="lab-console" class="row" style="margin-top: 30px;background-color: rgb(228, 228, 228);" >
    <div id="report-data" class="col-xs-12 col-md-4"  style="padding-right: 1px;margin-top: 10px;background-color: transparent;">
      <div id="lab-container" class="container" style="background-color: transparent;">
        <div class="lab-nav-container" style="border-top-left-radius: 2px;border-top-right-radius: 2px;padding: 0;background-color: transparent;">
          <div class="nav nav-tabs row" role="tablist" style="border-radius: inherit;padding-top: 2px;padding-bottom: 2px;background-color: transparent;">
            <h1 class="text-left col-md-12">
              <span>物理实验<small>报告管理中心</small></span>
              <hr style="border-color: #08c093;border-width: 2px;margin: 0px">
            </h1>
          </div>
        </div>
        <div class="container tab-content panel" style="border-bottom-left-radius: 2px;border-bottom-right-radius: 2px;margin: 5px 0 5px 0;height: 800px">
          <div class="panel-heading btn btn-success dropdown-toggle" id="lab-name" data-toggle="modal" data-target="#lab-select-modal" style="width: 100%;border-top-left-radius: 4px;border-top-right-radius: 4px;background-color: #08c093;">
            点击选择已存在实验
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
      </div>
    </div>
    <div id="doc" class="col-xs-12 col-md-8" style="padding-left: 1px;;margin-top: 20px;">
      <div class="panel-group" id="lab-report" style="margin-right: 15px;">
        <div class="panel panel-default pannel-autoscroll">
          <div class="panel-heading"  style="border: solid;border-width: 0px;height: 40px;">
            <div class="panel-title row">
              <div class="btn-group col-xs-12 col-md-5">
                <button id="new-sublab-btn" class="btn btn-success" data-toggle="modal" data-target="#collection-folder" style="border-radius: 4px;">
                  <i class="fa fa-folder"></i>
                  新增实验
                </button>
                  <div class="modal fade" id="collection-folder" tabindex="-1" role="dialog" aria-labelledby="collection-folder-label" aria-hidden="true">
                      <div class="modal-dialog" style="width: 38%;">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                  <h4 class="modal-title" id="collection-folder-label">新增实验</h4>
                              </div>
                              <div class="modal-body" style="width: auto;height: auto;text-align: center;">
                                  <label>实验号(7位)<input class="para form-control" id="l_id" type="input" onkeyup="value=value.replace(/[^\d]/g,'')"/></label>
                                  <label>实验名称(2~12字)<input class="para form-control" id="l_name" type="input"/></label>
                                  <label>实验分组号(4位)<input class="para form-control" id="l_tag" type="input" onkeyup="value=value.replace(/[^\d]/g,'')"/></label>
                              </div>
                              <div class="modal-footer" style="margin-top: 0;padding-bottom: 0">
                                  <div style="float:right">
                                      <a id="create_sublab" class="btn btn-large btn-danger">
                                          提交
                                      </a>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="col-md-1 hidden-md">
                <h4 class="panel-title text-center" style="position: absolute; left: 45%;">
                  <span id="lab-status" class="badge">物理实验脚本管理</span>
                </h4>
              </div>
            </div>
          </div>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="py_script_editor_area"><textarea id="py_editor" style="display:none;"></textarea></div>
                <div role="tabpanel" class="tab-pane fade" id="latex_editor_area"><textarea id="latex_editor" style="display:none;"></textarea></div>
                <div role="tabpanel" class="tab-pane fade" id="lab_table_editor_area"><textarea id="table_editor" style="display:none;"></textarea></div>
            </div>
            <ul id="editor_tab" class="nav nav-tabs" style="display: block;">
                <li class="active" style="width: 33%;text-align: center;">
                    <a href="#py_script_editor_area" data-toggle="tab" style="height: 40px;padding-top: 10px;"">
                        <div>Python脚本</div>
                    </a>
                </li>
                <li style="width: 33%;text-align: center;">
                    <a href="#latex_editor_area" data-toggle="tab" style="height: 40px;padding-top: 10px;"">
                        <div>LaTeX模板</div>
                    </a>
                </li>
                <li style="width: 33%;text-align: center;">
                    <a href="#lab_table_editor_area" data-toggle="tab" style="height: 40px;padding-top: 10px;">
                       <div> 实验表格HTML</div>
                    </a>
                </li>
            </ul>
        </div>
      </div>
        <div id="button-group-script" style="margin-top: 8px;">
            <div class="btn-group" style="width: 100%;">
                <button id="button-save-script" class="btn btn-success" style="border-radius: 4px 0 0 4px;width: 10%;">
                    <span class="sr-only">y</span>
                    <i class="fa fa-save"></i>
                    <span id="collect-report-text">保存实验</span>
                </button>
                <button id="button-push-script" class="btn btn-success"style="border-radius: 0 4px 4px 0;width: 10%;">
                    <span class="sr-only">y</span>
                    <i class="fa fa-send"></i>
                    <span id="collect-report-text">发布实验</span>
                </button>
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
  <script src="./js/consoleCore.js"></script>
  <script src="./js/jquery.cookie.js"></script>
  <script>
      var G_BASE_URL = '<?php echo url('/');?>';
      initReportPage();

	  te_py=document.getElementById('py_editor');
	  var pyedit = CodeMirror.fromTextArea(te_py, {
		mode: "python",
		lineNumbers: true,
		lineWrapping: true,
		extraKeys: {"Ctrl-Q": function(cm){ cm.foldCode(cm.getCursor()); }},
		keyMap: "sublime",
		autoCloseBrackets: true,
		showCursorWhenSelecting: true,
		theme: "monokai",
		tabSize: 4,
		matchBrackets: true,
		foldGutter: true,
		gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"]
	  });
	  var pyedit_div=pyedit.getWrapperElement();
	  pyedit_div=$(pyedit_div);
	  pyedit_div.height(730);
	  pyedit_div.css('clear','both');

      te_latex=document.getElementById('latex_editor');
      var latexedit = CodeMirror.fromTextArea(te_latex, {
          mode: "text",
          lineNumbers: true,
          lineWrapping: true,
          extraKeys: {"Ctrl-Q": function(cm){ cm.foldCode(cm.getCursor()); }},
          keyMap: "sublime",
          autoCloseBrackets: true,
          showCursorWhenSelecting: true,
          theme: "monokai",
          tabSize: 4,
          matchBrackets: true,
          foldGutter: true,
          gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"]
      });
      var latex_div=latexedit.getWrapperElement();
      latex_div=$(latex_div);
      latex_div.height(730);
      latex_div.css('clear','both');

      te_table=document.getElementById('table_editor');
      var tableedit = CodeMirror.fromTextArea(te_table, {
          mode: "xml",
          lineNumbers: true,
          lineWrapping: true,
          extraKeys: {"Ctrl-Q": function(cm){ cm.foldCode(cm.getCursor()); }},
          keyMap: "sublime",
          autoCloseBrackets: true,
          showCursorWhenSelecting: true,
          theme: "monokai",
          tabSize: 4,
          matchBrackets: true,
          foldGutter: true,
          gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"]
      });
      var table_div=tableedit.getWrapperElement();
      table_div=$(table_div);
      table_div.height(730);
      table_div.css('clear','both');
  </script>
</body>
</html>
@stop