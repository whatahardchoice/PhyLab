@extends('layout.main')


<div class="navbar-banner layout-no-margin-top">
  <div class="banner-container">
    <div class="container">
      <div class="row">
        <div class="hidden-xs col-md-7">
          <div class="container-title">
            &nbsp;PhyLab<br/>物理数据中心
          </div>
        </div>
        <div class="col-xs-12 col-md-5" @if (!$auth) style="display: block" @else style="display: none" @endif>
          <form method="post">
            <div class="form-group">
              <input class="form-control" name="name" id="name" type="text" placeholder="用户名">
            </div>
            <div class="form-group">
              <input class="form-control" name="email" id="email" type="email" placeholder="邮箱">
            </div>
            <div class="form-group">
              <input class="form-control" name="password" id="password" type="password" placeholder="密码">
            </div>
            <button id='quick-regist' class="btn register" type="button">注册</button>
            <p class="tip">点击“注册”，表示您已经同意我们的隐私条款</P>
          </form>
        </div>
      </div>
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
      <span>Copyright @2016-2016 物理实验报告平台</span>
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