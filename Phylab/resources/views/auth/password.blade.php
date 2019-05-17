<style type="text/css">
    .center {
        position:absolute;
        top:50%;
        left:50%;
        margin:-200px 0 0 -300px;
        width:600px;
        height:400px;
        z-index:198;
    }
</style>
<body style="background-color: #aeb3b9;">
<div style="text-align: center;background-color: #1b6d85"  class="center">
<form method="POST" action="/password/email" class="form-group">
    {!! csrf_field() !!}
    <div style="height: 80px; margin-top: 80px" class="text-center">
    <span ><strong style="font-size: xx-large" class="col-md-12">请 输 入 您 的 邮 箱 地 址</strong></span>
    </div>
        <div class="col-md-12" style="height: 80px">
        <span style="font-size: xx-large">Email: </span>
        <input type="email" style="font-size: xx-large" placeholder="example@xxx.com"  name="email" value="{{ old('email') }}" class="form-group">
    </div>

    <div class="col-md-12">
        <button type="submit" style="font-size: xx-large;background-color: #aeb3b9;width: 300px;height: 60px;margin-left: 150px" class="form-control btn btn-block">
            发送密码重置链接
        </button>
    </div>
</form>
</div>
</body>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="./js/bootstrap.min.js"></script>
<link rel="stylesheet" href="{{URL::asset('css/bootstrap.min.css')}}">