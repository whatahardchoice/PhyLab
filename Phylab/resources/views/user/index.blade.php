
@extends('layout.main')

@section('contents')


<!--div class="container" style = "margin-top: 50px"-->



    <div class="row" style="margin-top:30px;padding:30px">
        <div style = "text-align: center ; padding-top:40px" class="col-xs-12 col-md-4">
            <img src = "{{$avatarPath}}" alt="个人头像" style = "width: 250px ; height: 250px ; background-color: #ececf6 ">
<!--
            <form action = "/user" method="post" enctype="multipart/form-data" id = "load-form">
                <label for = "avatarPath">选择头像文件：</label>
                <input type = "file" name = "avatarPath" id = "avatarPath">
                <input type="submit" value="上传">
            </form>
-->

            <h3>{{$username}}</h3>

            <h4>{{$grade}}级</h4>
        </div>

        <div class="col-md-6 col-xs-12" >
    <ul id="myTab" class="nav nav-pills col-xs-12">
        <li class="active">
            <a href="#home" data-toggle="tab">
                个人信息
            </a>
        </li>
        <li ><a href="#favs" data-toggle="tab">收藏夹</a></li>
    </ul>

    <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade in active" id="home">
            <div style="background-color: #e4e4e4;padding:20px;">

                <form class="form-horizontal" action="/user" method="post" id="user-form" >
                    <div class="form-group col-xs-12">
                        <label for="username" class="col-sm-2 control-label col-xs-4">名字</label>
                        <div class="col-sm-8 col-xs-8">
                            <input type="text" class="form-control" id="username" name="username" value={{$username}}>
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for = "sex" class = "col-sm-2 control-label col-xs-4" >性别</label>
                        <div id = "sex" class="col-sm-8 col-xs-8 ">
                            <label class = "radio-inline col-sm-2 col-xs-4 text-center">
                                <input type = "radio" name = "sex" id = "sex_radio1" value = "1" @if($sex==1) checked @endif><span style = "margin-left: 5px">男</span>
                            </label>
                            <label class = "radio-inline col-sm-2 col-xs-4 text-center" >
                                <input type = "radio" name = "sex" id = "sex_radio2" value = "2" @if($sex==2) checked @endif><span style = "margin-left: 5px">女</span>
                            </label>
                            <label class = "radio-inline col-sm-2 hidden-xs text-center"  >
                                <input type = "radio" name = "sex" id = "sex_radio3" value = "3" @if($sex==3) checked @endif><span style = "margin-left: 5px">保密</span>
                            </label>

                        </div>
                    </div>
                    <div class = "form-group col-xs-12">
                        <label for = "grade" class = "col-sm-2 col-xs-4 control-label">年级</label>
                        <div class="col-sm-8 col-xs-8">
                            <select class = "form-control" id = "grade" name = "grade">
                                <option value="" @if($grade==0) selected @endif disabled hidden>Choose here</option>
                                <option @if($grade==2016) selected @endif value="2016">2016</option>
                                <option @if($grade==2017) selected @endif value="2017">2017</option>
                                <option @if($grade==2018) selected @endif value="2018">2018</option>
                                <option @if($grade==2019) selected @endif value="2019">2019</option>
                                <option @if($grade==2020) selected @endif value="2020">2020</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-xs-12">
                        <label for="email" class="col-sm-2 col-xs-4 control-label">邮箱</label>
                        <div class="col-sm-8 col-xs-8">
                            <input type="text" class="form-control" id="email" value={{$email}} readonly>
                        </div>
                    </div>
                    <div class = "form-group col-xs-12">
                        <label for = "intro" class = "col-sm-2 col-xs-4 control-label">简介</label>
                        <div class = "col-sm-8 col-xs-8">
                            <textarea id="intro" class = "form-control" row = 4 name="introduction" >{{$introduction}}</textarea>
                        </div>
                    </div>
                    <div class="form-group col-xs-12 text-center">
                        <label for = "update" class = "control-label col-sm-2 col-xs-2"></label>
                        <div class="col-sm-8 col-xs-8 ">
                            <button id = "update" type="submit" class="btn btn-success" style = "width: 100px">更新</button>
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
<script src="./js/user.js"></script>



</body>
</html>


@stop