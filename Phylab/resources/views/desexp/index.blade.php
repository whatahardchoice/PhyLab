@extends('layout.main')
@section('contents')

<div style="margin-top: 30px;height:auto;overflow:hidden" class="float-container">
  <!--  contents -->
  <div class="col-md-8 col-md-offset-2 col-xs-12 " style="margin-bottom: 50px">

    <nav class="navbar navbar-default" style="background-color: #c2ccd0;margin-top: 25px;box-shadow:2px 2px 5px rgb(200,200,200);border:none;" role="navigation">
      <div class="container-fluid" style="display: flex; align-items: center">
        <div class="navbar-header col-xs-12  col-md-9 text-center">
          <a class="navbar-brand" href="#"><strong style="font-size: large">设计性实验</strong></a>

        </div>
        <div class="text-center col-xs-12 col-md-3">
            <select class="form-control" id="desexp-select" style="margin:0" onchange="switchContent()">
              <option selected="selected" disabled="disabled" style="display: none">请选择实验</option>
              @foreach($expOptions as $option)
              <option value="{{$option->id}}">{{$option->name}}</option>
              @endforeach
            </select>
        </div>
      </div>
    </nav>

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