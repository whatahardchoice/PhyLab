@extends('layout.main')

@section('contents')
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  

  <title>绪论题库</title>
  <!-- <link href="/index.php?m=Format&a=rss" rel="alternate" type="application/rss+xml" title="RSS 2.0" /> -->
  <script type="text/javascript" src="{{URL::asset('tikuxulun_files')}}/jquery.js"></script>
  <link type="text/css" rel="stylesheet" href="{{URL::asset('tikuxulun_files')}}/layout.css">

  <script>
    jQuery(function () {
      var st = 180;
      jQuery('#nav_all>li').mouseenter(function () {
        jQuery(this).addClass('liactive').find('dl').stop(false, true).slideDown(st);
      }).mouseleave(function () {
        jQuery(this).removeClass('liactive').find('dl').stop(false, true).slideUp(st);
      });
    });
  </script>
  <script type="text/javascript">  document.oncontextmenu=function(e){return false;} </script> 
      <style> body { -moz-user-select:none; } </style>
</head>
<body onselectstart="return false">
  <div id="wrap">
    <div class="left_side">
      <div class="left_side_con">
        <div class="left_logo_nav">
          
          <ul id="nav_all">
          <li class=""><a href="tikuxulun" class="yiji" id="current"> ▪ 绪论</a>
              <dl style="display: none;">
              </dl>
            </li><li class=""><a href="tikuqimo" class="yiji"> ▪ 期末</a>
            <dl style="display: none;">
            </dl>
          </li>       </ul>
        </div>
      </div>
    </div>
    <div class="right_side_1">
      
            <style>
              body { background:url('{{URL::asset('images')}}/bg_1.png') no-repeat fixed center 0; }
            }
          </style>
          <script src="{{URL::asset('tikuxulun_files')}}/jquery.min.js"></script>
          <script src="{{URL::asset('tikuxulun_files')}}/jQuery.BlackAndWhite.js"></script>
          <script>
            jQuery(window).load(function(){
              jQuery('.bwlanrenzhijia').BlackAndWhite({
                hoverEffect:true,
              });
            });
          </script>
		  <script src="./tikuqimo_files/pdfobject.js"></script>
		  <script type="text/javascript">
       window.onload = function (){
			  var adress = "{{URL::asset('tikuxulun_files')}}/doc/物理实验绪论考试模拟题.pdf";
              var success = new PDFObject({ url:adress  }).embed("showbox");
       };
	   //此处加入新加入的pdf的代码
	   function changepdf(ad){
			var adress = ad;
			success = new PDFObject({ url:adress  }).embed("showbox");
	   };
     </script>
	 <div id="showbox" style="width:800px;height:1000px;margin:0 auto">
	 </div>
          <div class="right_side">
            <div class="page_content">
              <!--<h3 class="title_h3"> ▪ 原创</h3>-->
              <div class="show_box">
                <div class="img-scroll img-scroll_original"> <span class="prev"></span> <span class="next"></span>
                  <div class="original_list">
                    <ul style="margin-left: 0px;">
                      
              
                        <li class="">
  <a href=javascript:changepdf("{{URL::asset('tikuxulun_files')}}/doc/物理实验绪论考试模拟题.pdf")>
              <img style="top: 66px; position: relative;" src="{{URL::asset('tikuxulun_files')}}/1.jpg" title="xulun1"><span class="redbg">绪论考题            </span></a> </li><li class="hover">
  <a href=javascript:changepdf("{{URL::asset('tikuxulun_files')}}/doc/北航物理实验绪论考试真题(4套题含答案).pdf")>
              <img style="top: 0px; position: relative;" src="{{URL::asset('tikuxulun_files')}}/2.png" title="xulun2"><span class="redbg">绪论真题及答案              </span></a> </li><li class="">
			  <a href=javascript:changepdf("{{URL::asset('tikuxulun_files')}}/doc/物理绪论II.pdf")>
              <img style="top: 66px; position: relative;" src="{{URL::asset('tikuxulun_files')}}/3.png" title="xulun3"><span class="redbg">绪论考题2            </span></a> </li>
              </ul>
  <div class="clear"></div>
</div>
</div>
</div>
</div>
</div>
<script type="text/javascript">
  var position_top=146;
  $(function(){
    $(".original_list ul li").hover(function(){
      var index = $(this).index();

      $('.original_list ul li.hover a img').css({"position":"relative","top":position_top});
      position_top = $('.original_list ul li').eq(index).find("a img").css("top");
      $('.original_list ul li').removeClass("hover");
      $('.original_list ul li').eq(index).addClass("hover");
      $('.original_list ul li').eq(index).find("a img").css({"position":"relative","top":"0px"
    });
    })
  })

  function DY_scroll(wraper,prev,next,img,speed,or)
  { 
    var wraper = $(wraper);
    var prev = $(prev);
    var next = $(next);
    var img = $(img).find('ul');
    var w = img.find('li').outerWidth(true);
    var s = speed;
    next.click(function()
    {
     $('.original_list ul li.hover a img').css({"position":"relative","top":position_top});
     position_top = $('.original_list ul li:eq(4)').find("a img").css("top");
     $('.original_list ul li').removeClass("hover");
     $('.original_list ul li:eq(4)').addClass("hover");
     $('.original_list ul li:eq(4)').find("a img").css({"position":"relative","top":"0px"
   });

     img.animate({'margin-left':-w},function()
     {
       img.find('li').eq(0).appendTo(img);
       img.css({'margin-left':0});
     });
   });
    prev.click(function()
    {
      img.find('li:last').prependTo(img);
      img.css({'margin-left':-w});
      img.animate({'margin-left':0});
    });
    if (or == true)
    {
     ad = setInterval(function() { next.click();},s*1000);
     wraper.hover(function(){clearInterval(ad);},function(){ad = setInterval(function() { next.click();},s*1000);});

   }
 }
 DY_scroll('.img-scroll','.prev','.next','.original_list',3,true);// true为自动播放，不加此参数或false就默认不自动
</script> 



</body></html>