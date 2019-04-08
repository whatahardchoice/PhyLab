<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel = "stylesheet" href="./css/bootstrap.min.css">
</head>
<body>

<div class = "text-center" style = "margin-bottom: 0 ; height: 80px ; padding : 20px ; background-color: #ececf6">
    <h1>PHYLAB</h1>
</div>
<nav class="navbar navbar-expand-sm bg-info navbar-dark">

    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav" style = "margin-left: 50px">
            <li class="nav-item">
                <a class="nav-link" href="#">个人信息</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">收藏夹</a>
            </li>
        </ul>
    </div>
</nav>
<div class="container" style = "margin: 20px">
    <!--
    <div class = "row">
        <h1 style = "margin-left: 30px ; margin-bottom: 30px;">PHYLAB</h1>
    </div>
    -->

    <!--
    <div class="nav-scroller py-1 mb-2">
        <nav class="nav d-flex justify-content-between">
            <a style = "width: 120px ; background-color: #5bc0de" class="p-2 text-muted" href="#">个人信息</a>
            <a style = "width: 120px ; background-color: #5bc0de" class="p-2 text-muted" href="#">收藏夹</a>

            <a class="p-2 text-muted" href="#"></a>
            <a class="p-2 text-muted" href="#"></a>


        </nav>
    </div>
    -->
    <!--
    <div class = "row">
        <nav class="navbar navbar-default" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">菜鸟教程</a>
                </div>
                <div>
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="#">iOS</a></li>
                        <li><a href="#">SVN</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    -->

    <div class="row">

        <div style = "text-align: center ; padding-top:40px" class="col-md-4 col-lg-4">
            <img src = "" , alt="个人头像" style = "width: 250px ; height: 250px ; background-color: #ececf6 ">

            <h3>名字</h3>

            <h4>2016级</h4>
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
                        <input type="text" class="form-control" id="firstname" placeholder="请输入名字">
                    </div>
                </div>
                <div>
                    <label for = "sex" class = "col-md-2 control-label" >性别</label>
                    <div id = "sex" class = "col-md-8">
                        <label class = "radio-inline col-md-3">
                            <input type = "radio" name = "sex" id = "sex_radio1" value = "man"><span style = "margin-left: 5px">男</span></label>
                        </label>
                        <label class = "radio-inline">
                            <input type = "radio" name = "sex" id = "sex_radio2" value = "woman"><span style = "margin-left: 5px">女</span></label>
                        </label>
                    </div>
                </div>
                <div class = "form-group">
                    <label for = "grade" class = "col-md-2 control-label">年级</label>
                    <div class="col-lg-8">
                        <select class = "form-control" id = "grade">
                            <option>2016级</option>
                            <option>2017级</option>
                            <option>2018级</option>
                            <option>2019级</option>
                            <option>2020级</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">邮箱</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="email" placeholder="请输入邮箱">
                    </div>
                </div>
                <div class = "form-group">
                    <label for = "intro" class = "col-md-2 control-label">简介</label>
                    <div class = "col-md-8">
                        <textarea id="intro" class = "form-control" row = 4></textarea>
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

</body>
</html>