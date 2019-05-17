<style type="text/css">
    .resetcenter {
        position:absolute;
        top:50%;
        left:50%;
        margin:-195px 0 0 -360px;
        width:720px;
        height:390px;
    }
</style>
<body style="background-color: #aeb3b9;">
<div style="background-color: #1b6d85"  class="resetcenter">
    <form method="POST" action="/password/reset" class="form-group">
        {!! csrf_field() !!}
        <input type="hidden" name="token" value="{{ $token }}">
        <div style="height: 80px;font-size: xx-large;margin-top: 10px" class="text-center">
            <label>密 码 修 改</label>
        </div>
        <div style="height: 70px;margin-left: 60px;font-size: xx-large">
            <label style="width: 200px" class="text-left">Email:</label><input type="email" name="email" value="{{ old('email') }}" placeholder="example@xxx.com">
        </div>

        <div style="height: 70px;margin-left: 60px;font-size: xx-large">
            <label style="width: 200px" class="text-left">新密码:</label><input type="password" name="password" placeholder="******">
        </div>

        <div style="height: 70px;margin-left: 60px;font-size: xx-large">
            <label style="width: 200px" class="text-left">确认密码:</label><input type="password" name="password_confirmation" placeholder="******">
        </div>

        <div style="height: 70px;font-size: xx-large">
            <button type="submit" style="width: 360px;margin-left: 180px;background-color: #aeb3b9;">
                重 置 密 码
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