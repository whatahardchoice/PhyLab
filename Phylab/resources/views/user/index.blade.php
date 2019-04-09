
@extends('layout.main')

@section('contents')


<!--div class="container" style = "margin-top: 50px"-->



    <div class="row" style="margin-top:30px;padding:30px">
        <div style = "text-align: center ; padding-top:40px" class="col-xs-12 col-md-4">
            <img src = "" alt="个人头像" style = "width: 250px ; height: 250px ; background-color: #ececf6 ">

            <h3>{{$username}}</h3>

            <h4>{{$grade}}级</h4>
        </div>

        <div class="col-md-8 col-xs-12" >
    <ul id="myTab" class="nav nav-tabs">
        <li class="active">
            <a href="#home" data-toggle="tab">
                个人信息
            </a>
        </li>
        <li><a href="#favs" data-toggle="tab">收藏夹</a></li>

    </ul>
    <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade in active" id="home">
            <div style="background-color: #ececf6;box-shadow: inset 1px -1px 1px #444, inset -1px 1px 1px #444; padding : 20px;">

                <form class="form-horizontal" action="" >
                    <div class="form-group">
                        <label for="firstname" class="col-sm-2 control-label">名字</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="firstname" placeholder={{$username}}>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for = "sex" class = "col-sm-2 control-label" >性别</label>
                        <div id = "sex" class = "col-sm-8">
                            <label class = "radio-inline col-sm-3">

                                <input type = "radio" name = "sex" id = "sex_radio1" value = "man"><span style = "margin-left: 5px">男</span></label>

                            <label class = "radio-inline">
                                <input type = "radio" name = "sex" id = "sex_radio2" value = "woman"><span style = "margin-left: 5px">女</span></label>

                        </div>
                    </div>
                    <div class = "form-group">
                        <label for = "grade" class = "col-sm-2 control-label">年级</label>
                        <div class="col-sm-8">
                            <select class = "form-control" id = "grade" name = "grade">
                                <option>2016</option>
                                <option>2017</option>
                                <option>2018</option>
                                <option>2019</option>
                                <option>2020</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="col-sm-2 control-label">邮箱</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="email" placeholder={{$email}}>
                        </div>
                    </div>
                    <div class = "form-group">
                        <label for = "intro" class = "col-sm-2 control-label">简介</label>
                        <div class = "col-sm-8">
                            <textarea id="intro" class = "form-control" row = 4 placeholder={{$introduction}}></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for = "update" class = "col-md-2 control-label"></label>
                        <div class="col-sm-8">
                            <button id = "update" type="button" class="btn btn-success" style = "width: 100px">更新</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
        <div class="tab-pane fade" id="favs">
            <iframe id="collection-iframe" src="{{URL::route('star')}}" style="width:100%;height: 320px;" frameborder="0"></iframe>
        </div>
    </div>

</div>
</div>



<!--/div-->

<footer id="site-footer" class="navbar-fixed-bottom">
    <div class="text-center copyright" style="margin-top: 0">
        <span>Copyright @2016-2016 物理实验报告平台</span>
    </div>
</footer>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="./js/bootstrap.min.js"></script>
<!--自定义js脚本-->
<script src="./js/global.js"></script>
<script src="./js/login.js"></script>
<script type="text/javascript">$('.carousel').carousel()</script>
<script src="./js/statistics.js"></script>
<script src="./js/regist.js"></script>


</body>
</html>


@stop