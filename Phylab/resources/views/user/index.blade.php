
@extends('layout.main')

@section('contents')


<div class="container" style = "margin: 20px">

    <div class="row">

        <div style = "text-align: center ; padding-top:40px" class="col-md-4 col-lg-4">
            <img src = "" alt="个人头像" style = "width: 250px ; height: 250px ; background-color: #ececf6 ">

            <h3>{{$username}}</h3>

            <h4>{{$grade}}级</h4>
        </div>

        <div class="col-md-8 col-lg-8" style="background-color: #ececf6;box-shadow: inset 1px -1px 1px #444, inset -1px 1px 1px #444; padding : 20px;">
            <!--
             <div class = "btn-group">
                 <button type = "button" class = "btn btn-info">个人信息</button>
                 <button type = "button" class = "btn btn-info">收藏夹</button>
             </div>
             -->
            <form class="form-horizontal" action="" >
                <div class="form-group">
                    <label for="firstname" class="col-lg-3 control-label">名字</label>
                    <div class="col-lg-9">
                        <input type="text" class="form-control" id="firstname" placeholder={{$username}}>
                    </div>
                </div>
                <div>
                    <label for = "sex" class = "col-md-2 control-label" >性别</label>
                    <div id = "sex" class = "col-md-8">
                        <label class = "radio-inline col-md-3">

                            <input type = "radio" name = "sex" id = "sex_radio1" value = "man"><span style = "margin-left: 5px">男</span></label>

                        <label class = "radio-inline">
                            <input type = "radio" name = "sex" id = "sex_radio2" value = "woman"><span style = "margin-left: 5px">女</span></label>

                    </div>
                </div>
                <div class = "form-group">
                    <label for = "grade" class = "col-md-2 control-label">年级</label>
                    <div class="col-lg-8">
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
                    <label for = "intro" class = "col-md-2 control-label">简介</label>
                    <div class = "col-md-8">
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
</div>

<footer id="site-footer">
    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-4 clearfix footer-col">
                    <div class="col-lx-12 text-center">
                        <img id="footer-logo" src="./img/phylab_logo_single.svg"/>
                        <div class="footer-slogan">PhyLab</div>
                    </div>
                    <div class="col-xs-4">
                        <div class="social-item footer-weixin-item">
                            <i class="fa fa-weixin" aria-hidden="true"></i>
                            <div class="footer-weixin">
                                <img src="http://www.buaaphylab.com/img/1447574686560.jpg">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="social-item footer-qq-item">
                            <i class="fa fa-qq"></i>
                            <div class="footer-qq">
                                QQ: 229407702 (实验网站交流群)
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="social-item footer-blog-item">
                            <a href="http://www.cnblogs.com/Default1406/" target="_blank ">
                                <i class="fa fa-rss"></i>
                            </a>
                            <div class="footer-blog">
                                Default2014
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3 col-md-2 footer-col ">
                    <div class="col-title ">团队</div>
                    <a href="#" target="_blank">关于我们</a><br>
                    <a href="#" target="_blank">联系我们</a><br>
                    <a href="http://www.cnblogs.com/Default1406/" target="_blank ">加入我们</a><br>
                    <a href="http://www.cnblogs.com/Default1406/" target="_blank ">技术博客</a><br>
                </div>
                <div class="col-xs-6 col-sm-3 col-md-2 footer-col ">
                    <div class="col-title ">合作</div>
                    <a href="#" target="_blank ">上传资源</a><br>
                    <a href="#" target="_blank ">教师合作</a><br>
                    <a href="#" target="_blank ">友情链接</a>
                </div>
                <div class="col-xs-6 col-sm-3 col-md-2 footer-col ">
                    <div class="col-title ">模块</div>
                    <a href="#" target="_blank ">实验</a><br>
                    <a href="#" target="_blank ">社区</a><br>
                    <a href="#" target="_blank ">反馈</a><br>
                </div>
                <div class="col-xs-6 col-sm-3 col-md-2 footer-col ">
                    <div class="col-title ">支持实验</div>
                    <a href="./report" target="_blank ">1011</a><br>
                    <a href="./report" target="_blank ">1021</a><br>
                    <a href="./report" target="_blank ">1071</a><br>
                    <a href="./report" target="_blank ">1081</a><br>
                    <a href="./report" target="_blank ">1091</a><br>
                    <a href="./report" , target="_blank ">全部</a>
                </div>
            </div>
        </div>
        <div class="text-center copyright">
            <span>Copyright @2016-2019 物理实验报告平台 {{$username}}</span>
        </div>
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