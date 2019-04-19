@extends('layout.main')

@section('contents')
<div class="navbar-banner layout-no-margin-top" style="margin-top: 0px">
  <div class="banner-container">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-md-5 col-md-offset-7" @if (!$auth) style="display: block" @else style="display: none" @endif>
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
            <p class="tip">点击“注册”，表示您已经同意我们的隐私条款.</P>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="introduce-container" id="fourFeatures">
  <div class="container">
    <div class="row">
      <div class="introduce-unit col-sm-6 col-md-3">
        <div><a class="introduce-img"><img src="./img/report-feature.png"></a></div>
        <div><span class="introduce-span">详细的物理实验预习报告</span></div>
      </div>
      <div class="introduce-unit col-sm-6 col-md-3">
        <div><a class="introduce-img"><img src="./img/platform-feature.png"></a></div>
        <div><span class="introduce-span">手机、电脑同步使用</span></div>
      </div>
      <div class="introduce-unit col-sm-6 col-md-3">
        <div><a class="introduce-img"><img src="./img/phy-feature.png"></a></div>
        <div><span class="introduce-span">快速全面地计算实验结果</span></div>
      </div>
      <div class="introduce-unit col-sm-6 col-md-3">
        <div><a class="introduce-img"><img src="./img/community-feature.png"></a></div>
        <div><span class="introduce-span">社区中愉快地分享知识</span></div>
      </div>
    </div>
  </div>
</div>

<div class="hidden-xs line-and-laboratory">
  <div class="container">
    <div class="clearfix text-center item-header">
      <span class="line"></span>
      <span class="text-center item-title">已支持实验</span>
      <span class="line"></span>
    </div>
    <div class="tab-content">
      <div class="tab-pane clearfix active" id="study-line">
        <div class="col-xs-12 col-sm-6 col-md-4">
          <a href="{{URL::route('report')}}">
            <div class="path-item">
              <div class="col-xs-5 col-sm-4 path-img">
                <img src="./img/1011-mechanics-lab.png" style="background-color: #3b3b7e">
              </div>
              <div class="col-xs-7 col-sm-8">
                <div class="path-name"><h4>1011</h4>力学系列实验</div>
                <div class="path-course-num">
                  2 个子实验
                </div>
              </div>
              <div class="desc-layer">
                <div class="center">1010113 拉伸法测钢丝弹性模量<br/>1010212 扭摆法测量转动惯量</div>
              </div>
            </div>
          </a>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
          <a href="{{URL::route('report')}}">
            <div class="path-item">
              <div class="col-xs-5 col-sm-4 path-img">
                <img src="./img/1021-thermology-lab.png" style="background-color: #3b3b7e">
              </div>
              <div class="col-xs-7 col-sm-8">
                <div class="path-name"><h4>1021</h4>热学系列实验</div>
                <div class="path-course-num">
                  1 个子实验
                </div>
              </div>
              <div class="desc-layer">
                <div class="center">1020113 测量冰的溶解热实验<!--br/>1020212 电热法测量焦耳热功当量实验--></div>
              </div>
            </div>
          </a>
        </div>
        <!--div class="col-xs-12 col-sm-6 col-md-4">
          <a href="{{URL::route('report')}}">
            <div class="path-item">
              <div class="col-xs-5 col-sm-4 path-img">
                <img src="./img/1031-oscillograph-lab.png" style="background-color: #3b3b7e">
              </div>
              <div class="col-xs-7 col-sm-8">
                <div class="path-name"><h4>1031</h4>示波器的应用</div>
                <div class="path-course-num">
                  0 个子实验
                </div>
              </div>
              <div class="desc-layer">
                <div class="center"><1030113 模拟示波器的使用（必做）></div>
              </div>
            </div>
          </a>
        </div-->
        <div class="col-xs-12 col-sm-6 col-md-4">
          <a href="{{URL::route('report')}}">
            <div class="path-item">
              <div class="col-xs-5 col-sm-4 path-img">
                <img src="./img/1061-lens-lab.png" style="background-color: #3b3b7e">
              </div>
              <div class="col-xs-7 col-sm-8">
                <div class="path-name"><h4>1061</h4>薄透镜和单球面镜焦距的测量</div>
                <div class="path-course-num">
                  2 个子实验
                </div>
              </div>
              <div class="desc-layer">
                <div class="center">1060111 物距像距法测量透镜焦距<br/>1060213 自准直法测量透镜焦距<!--br/>1060312 共轭法测量凸透镜焦距<br/>1060412 单球面镜焦距的测量--></div>
              </div>
            </div>
          </a>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
          <a href="{{URL::route('report')}}">
            <div class="path-item">
              <div class="col-xs-5 col-sm-4 path-img">
                <img src="./img/1071-spectrometer-lab.png" style="background-color: #3b3b7e">
              </div>
              <div class="col-xs-7 col-sm-8">
                <div class="path-name"><h4>1071</h4>分光仪的调整及其应用</div>
                <div class="path-course-num">
                  3 个子实验
                </div>
              </div>
              <div class="desc-layer">
                <div class="center">1070212 测量三棱镜的顶角（必选）<br/>1070312 最小偏向角法测量棱镜的折射率1070322<br/>掠入射法测量棱镜的折射率</div>
              </div>
            </div>
          </a>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
          <a href="{{URL::route('report')}}">
            <div class="path-item">
              <div class="col-xs-5 col-sm-4 path-img">
                <img src="./img/1081-interference1-lab.png" style="background-color: #3b3b7e">
              </div>
              <div class="col-xs-7 col-sm-8">
                <div class="path-name"><h4>1081</h4>光的干涉实验I（分波阵面法）1</div>
                <div class="path-course-num">
                  1 个子实验
                </div>
              </div>
              <div class="desc-layer">
                <div class="center">1080114 激光双棱镜干涉<br/>1080124 激光劳埃镜干涉</div>
              </div>
            </div>
          </a>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
          <a href="{{URL::route('report')}}">
            <div class="path-item">
              <div class="col-xs-5 col-sm-4 path-img">
                <img src="./img/1082-interference2-lab.png" style="background-color: #3b3b7e">
              </div>
              <div class="col-xs-7 col-sm-8">
                <div class="path-name"><h4>1082</h4>光的干涉实验I（分波阵面法）2</div>
                <div class="path-course-num">
                  2 个子实验
                </div>
              </div>
              <div class="desc-layer">
                <div class="center">1080215 钠光双棱镜干涉<br/>1080225 钠光劳埃镜干涉</div>
              </div>
            </div>
          </a>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
          <a href="{{URL::route('report')}}">
            <div class="path-item">
              <div class="col-xs-5 col-sm-4 path-img">
                <img src="./img/1091-interference-lab.png" style="background-color: #3b3b7e">
              </div>
              <div class="col-xs-7 col-sm-8">
                <div class="path-name"><h4>1091</h4>光的干涉实验II（分振幅法）</div>
                <div class="path-course-num">
                  1 个子实验
                </div>
              </div>
              <div class="desc-layer">
                <div class="center">1090114 迈克尔逊干涉（必做）<!--br/>1090212 牛顿环干涉<br/>1090312 劈尖干涉--></div>
              </div>
            </div>
          </a>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
          <a href="{{URL::route('report')}}">
            <div class="path-item">
              <div class="col-xs-5 col-sm-4 path-img">
                <img src="./img/1091-interference-lab.png" style="background-color: #3b3b7e">
              </div>
              <div class="col-xs-7 col-sm-8">
                <div class="path-name"><h4>2111</h4>全息照相</div>
                <div class="path-course-num">
                  1 个子实验
                </div>
              </div>
              <div class="desc-layer">
                <div class="center">2110114 反射全息照相及其应用<!--br/>1090212 牛顿环干涉<br/>1090312 劈尖干涉--></div>
              </div>
            </div>
          </a>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
          <a href="{{URL::route('report')}}">
            <div class="path-item">
              <div class="col-xs-5 col-sm-4 path-img">
                <img src="./img/1091-interference-lab.png" style="background-color: #3b3b7e">
              </div>
              <div class="col-xs-7 col-sm-8">
                <div class="path-name"><h4>2141</h4>燃料电池</div>
                <div class="path-course-num">
                  1 个子实验
                </div>
              </div>
              <div class="desc-layer">
                <div class="center">2140113 燃料电池综合特性测量<!--br/>1090212 牛顿环干涉<br/>1090312 劈尖干涉--></div>
              </div>
            </div>
          </a>
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
                <img src="./img/1447574686560.jpg">
              </div>
            </div>
          </div>
          <div class="col-xs-4">
            <div class="social-item footer-qq-item">
              <i class="fa fa-qq"></i>
              <div class="footer-qq">
                QQ: 1009310853(实验网站交流群)
              </div>
            </div>
          </div>
          <div class="col-xs-4">
            <div class="social-item footer-blog-item">
              <a href="http://www.cnblogs.com/Default1406/" target="_blank ">
                <i class="fa fa-rss"></i>
              </a>
              <div class="footer-blog">
                WhatAHardChoice
              </div>
            </div>
          </div>
        </div>
        <div class="col-xs-6 col-sm-3 col-md-2 footer-col ">
          <div class="col-title ">团队</div>
          <a href="http://www.cnblogs.com/hardchoice/" target="_blank">关于我们</a><br>
          <a href="Mailto:hardchoice@163.com" target="_blank">联系我们</a><br>
          <a href="http://www.cnblogs.com/hardchoice/" target="_blank ">加入我们</a><br>
          <a href="http://www.cnblogs.com/hardchoice/" target="_blank ">技术博客</a><br>
        </div>
        <div class="col-xs-6 col-sm-3 col-md-2 footer-col ">
          <div class="col-title ">合作</div>
          <a href="#" target="_blank ">上传资源</a><br>
          <a href="#" target="_blank ">教师合作</a><br>
          <a href="#" target="_blank ">友情链接</a>
        </div>
        <div class="col-xs-6 col-sm-3 col-md-2 footer-col ">
          <div class="col-title ">模块</div>
          <a href="/report" target="_blank ">实验</a><br>
          <a href="/wecenter/?/account/login/" target="_blank ">社区</a><br>
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