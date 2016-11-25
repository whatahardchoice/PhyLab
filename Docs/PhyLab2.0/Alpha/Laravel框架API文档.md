# Laravel框架API文档

## 登录注册

### 登录
- 请求方式：POST
- 内容：```"email"=>'邮箱或用户名',"password"=>'password'```
- 返回方式：Json
- 内容：```"status"=>SUCCESS_MESSAGE || AuthenticationFailException()```
- 说明：如果含有remember参数，会记住用户名密码。
### 注册
- 请求方式：POST
- 内容：``` 'name'  => '用户名','email' => '邮箱','student_id' => '学号','password'  => '密码'```
- 返回方式：Json
- 内容：```"status"=>FAIL_MESSAGE && "message"=>错误信息```
- 说明：注册完毕后会默认直接登录。

## 收藏以及获取收藏

### 收藏报告
- 请求方式：POST
- 内容：```"link"=>'报告的路径',"reportId"=>'对应的报告id'```
- 返回方式：Json
- 内容：```"status"=>SUCCESS_MESSAGE && "id"=>'收藏的报告的id' || "status"=>FAIL_MESSAGE && "message"=>'收藏报告失败'```
### 获取收藏
- 请求方式：POST
- 内容：```"id"=>'用户的id'```
- 返回方式：Json
- 内容：```"id" =>'报告id',"name" =>'报告名字',"link" =>'报告路径',"time" =>'报告生成时间'```
- 说明：上述返回Json实际上为一个实验报告条目的Json数组，实际返回Json格式为{'0'=>[一个报告],'1'=>[一个报告]}。

## 个人信息的获取以及更新

### 获取
- 请求方式：POST
- 内容：```"id"=>'用户的id'```
- 返回方式：Json
- 内容：```"avatarPath"=>'头像路径',"username"=>'用户名',"sex"=>'性别',"company"=>'公司',"companyAddr"=>'公司地址',"birthday"=>'生日',"introduction"=>'介绍'```

### 更新
- 请求方式：POST
- 内容：```"id"=>'用户的id','password'=>'密码','username'=>'用户名','birthday'=>'生日','sex'=>'性别','company'=>'公司','companyAddr'=>'公司地址','introduction'=>'介绍'```
- 返回方式：Json
- 内容：```"status"=>SUCCESS_MESSAGE```
- 说明：请求内容中可以发送上述Json的任意子集更新对应数据。

## 发送实验数据以获取PDF报告

### 发送
- 请求方式：POST
- 内容：```"id"=>'实验的id','xml'=>'实验数据的xml'```
- 返回方式：Json
- 内容：```"status"=> "生成状态","experimentId" => "生成实验id","link"  => "生成报告路径","message" => "生成信息"```

### 获取
- 请求方式：POST
- 内容：```"id"=>'实验报告的id'```
- 返回方式：Json
- 内容：```"status"=> "生成状态","experimentId" => "生成实验id","experimentName"  => "实验的名字","prepareLink" => "报告的地址"```

