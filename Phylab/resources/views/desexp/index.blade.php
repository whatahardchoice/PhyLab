<!--设计性实验页面，展示不同编号的设计性实验-->
@extends('layout.main')
@section('contents')

<div style="margin-top: 30px;height:auto;overflow:hidden" class="float-container">
  <!--  contents -->
  <div class="col-md-8 col-md-offset-2 col-xs-12 " style="margin-bottom: 50px">

    <nav class="navbar navbar-default" style="background-color: #08c093;margin-top: 25px;box-shadow:2px 2px 5px rgb(200,200,200);border:none;" role="navigation">
      <div class="container-fluid" style="display: flex; align-items: center">
        <div class="navbar-header col-xs-12  col-md-6 text-center">
          <a class="navbar-brand" href="#"><strong style="font-size: large;color:white">设计性实验</strong></a>
        </div>
        <text class="col-md-3" style="color:white">点击虚线显示/隐藏答案</text>
        <div class="text-center col-xs-12 col-md-3 col-md-offset-2">
            <select class="form-control" id="desexp-select" style="margin:0" onchange="switchContent()">
              <option selected="selected" disabled="disabled" style="display: none">请选择实验</option>
              @foreach($expOptions as $option)
              <option value="{{$option->id}}">{{$option->name}}</option>
              @endforeach
            </select>
        </div>
      </div>
    </nav>
    <div style="background: white; box-shadow: 2px 2px 3px rgb(200,200,200);margin-top:10px;margin-bottom:10px;padding:10px">
      <div style="text-align: center">本模块内容均来自《北航基础物理实验考试资料2017修订版》</div>
      <div style="text-align: center">感谢编写及修订此资料同学的帮助</div>
      <div style="text-align: center">本站对此页面的内容<strong style="color:red">正确性不做保证</strong>，还请各位同学多多注意和指正</div>
      <div style="text-align: center">祝各位同学取得好成绩！</div>
    </div>

    <div style="margin-top: 0px;margin-bottom: 20px">
        <iframe id="desexp-iframe" src="/desexp_html/D01.html" onload="this.style.height=this.contentDocument.body.scrollHeight +'px';"
              style="margin-top: 0px;height: 700px;width:100%;border:none;box-shadow:2px 2px 5px rgb(200,200,200);" scrolling="no">
        </iframe>
    </div>
    <div  class="float-button hidden-xs hidden-sm" style="position:fixed; right:5%;bottom:50%;">
      <button id="btn-clear-answer" class="btn btn-success" style="cursor: pointer;border-radius: 50%;box-shadow: 2px 2px 3px rgb(200,200,200);">清空答案</button>
    </div>
  </div>

</div>

<footer id="site-footer">
  <div class="text-center copyright" style="margin-top: 0">
    <span>Copyright @2016-2016 物理实验报告平台</span>
    <script async src="//busuanzi.ibruce.info/busuanzi/2.3/busuanzi.pure.mini.js"></script>
    .<span id="busuanzi_container_page_pv" style="display:inline-flex;">本页面总访问量<span id="busuanzi_value_page_pv"></span>次</span>
  </div>
</footer>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="./js/bootstrap.min.js"></script>
<script src="./js/desexp.js"></script>
  </body>
  </html>
@stop