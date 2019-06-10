# Laravel框架API文档

## 登录注册/公告/设计性实验/小工具

### 登录
- url: 
    ```
    /login
    ```
- 请求方式：POST
- 内容：
    ```
    "email"=>'邮箱或用户名',
    "password"=>'密码'
    ```
- 返回方式：Json
- 内容：
    ```
    "status"=>SUCCESS_MESSAGE || "status"=>FAIL_MESSAGE && "errorcode"=>"7101"
    ```
- 说明：如果含有remember参数，会记住用户名密码。

### 注册
- url: 
    ```
    /register
    ```
- 请求方式：POST
- 内容：
    ```
    'name'  => '用户名',
    'email' => '邮箱',
    'student_id' => '学号',
    'password'  => '密码'
    ```
- 返回方式：Json
- 内容：
    ```
    "status"=>FAIL_MESSAGE && "message"=>错误信息 && "errorcode"=>"7102"
    ```
- 说明：注册完毕后会默认直接登录。

###获取设计性实验
- url
```
/desexp/{id}
```
- 请求方式：GET
- 内容：
```
"id"=>"设计性实验id"
```
- 返回方式：json
- 内容：
```
'status'=>'', 'message'=> '', 'id'=>'', 'link'=>'', 'name' => '','errorcode'=>''
```
###公告栏
- url:
```
/modifyBulletin
```
- 请求方式： POST
- 内容：
```
无
```
- 返回方式：json
- 内容：
```
"status"=> "",
"errorcode"=>"",
"message" => ""
```
###小工具
- url:
```
/tools
```
- 请求方式：GET
- 内容：
```
无
```
- 返回方式：view
- 内容：
```
小工具主页面
```
## 收藏以及获取收藏

### 收藏夹收藏报告
- url: 
    ```
    /user/star
    ```
- 请求方式：POST
- 内容：
    ```
    "link"=>'报告的路径',
    "reportId"=>'对应的报告id'
    ```
- 返回方式：Json
- 内容：
    ```
    "status"=>SUCCESS_MESSAGE && "id"=>'收藏的报告的id' || 
    "status"=>FAIL_MESSAGE && "message"=>'收藏报告失败' && "errorcode"
    ```

### 获取收藏夹
- url: 
    ```
    /user/star
    ```
- 请求方式：GET
- 内容：无参数
- 返回方式：Json
- 内容：
    ```
    "id" =>'报告id',
    "name" =>'报告名字',
    "link" =>'报告路径',
    "time" =>'报告生成时间'
    ```
- 说明：上述返回Json实际上为一个实验报告条目的Json数组，实际返回Json格式为{'0'=>[一个报告],'1'=>[一个报告]}，并且这个数组在数据的“stars”键里。
###删除收藏
- url:
    ```
	/user/star
 	```
- 请求方式：delete
- 内容：
	```
	"id"=>"需要删除的收藏文件"
	```
- 返回方式：json
- 返回内容：
	```
	status;errorcode;messgae
	```

###展示收藏的报告
- url:
	```
	/user/star/{id}
	```
- 请求方式：GET
- 内容：
	```	
	"id"=>"需要展示的收藏报告的id"
	``` 
- 返回方式：view页面(html)
- 返回内容:
```
报告文件
```
###下载收藏报告
- url:
	```
	/user/star/download/{id}
	```
- 请求方式：GET
- 内容：
```
"id"=>"需要展示的收藏报告的id"
```
- 返回方式：json
- 返回内容:
```
报告文件
```


## 个人信息的获取以及更新

### 获取个人信息
- url: 
    ```
    /user
    ```
- 请求方式：GET
- 内容：
    ```
    "id"=>'用户的id'
    ```
- 返回方式：Json
- 内容：
    ```
    "avatarPath"=>'头像路径',
    "username"=>'用户名',
	"studentId"=>'学号'
	“grade”=>'年级'
	"email"=>'邮箱'
    "sex"=>'性别',
    "company"=>'公司',
    "companyAddr"=>'公司地址',
    "birthday"=>'生日',
    "introduction"=>'介绍'
    ```

### 更新个人信息
- url: 
    ```
    /user/
    ```
- 请求方式：POST
- 内容：
    ```
    "id"=>'用户的id',
    'password'=>'密码',
    'username'=>'用户名',
    'birthday'=>'生日',
    'sex'=>'性别',
    'company'=>'公司',
    'companyAddr'=>'公司地址',
    'introduction'=>'介绍'
    ```
- 返回方式：Json
- 内容：
    ```
    "status"=>SUCCESS_MESSAGE || "status"=>FAIL_MESSAGE && "errorcode"=>"72.."
    ```
- 说明：请求内容中可以发送上述Json的任意子集更新对应数据。

###设置头像
- url:
```
/user/avatar
```
- 请求方式：POST
- 内容：
```
avatar图像文件
```
- 返回方式:json
- 返回内容：
```
$data = ["status"=>"","avatarPath"=>"","message"=>"","errorcode"=>""]
```

##密码操作
###填写重置密码用户的邮箱
- url:
```
password/email
```
- 请求方式：POST
- 内容：
```
"email"=>"邮箱"
```
- 返回方式：return
- 内容：
```
成功或失败的提示信息
```
###提交重置密码
- url:
```
password/reset
```
- 请求方式：POST
- 内容：
```
'token' => 'required',
'email' => 'required|email',
'password' => 'required|confirmed|min:6'
```
- 返回方式：redirect重定向
- 返回内容：
```
view页面
```

## 实验报告的获取与生成

### 获取所有实验信息
- url: 
    ```
    /getreport
    ```
- 请求方式：GET
- 返回方式：Json
- 内容：
    ```
    ["report"=> [
    		["id"=> "实验编号",
    		"experimentName" => "实验名称",
    		"relatedArticle"  => "相关的评论区文章",
    		"status" => "发布状态（0为未发布，1为发布）"],
    		[...],
    		...
    	]
    ]
    ```

### 获取实验输入表格
- url: 
    ```
    /table
    ```
    
- 请求方式：GET

- 参数（附加在url后）：

    ```
    'id'=>'报告编号'
    ```

- 返回方式：HTML，出错返回json

- 内容：对应报告编号的html表格

### 生成LaTeX报告

* url：

  ```
  /report/createTex
  ```

* 请求方式：POST

* 内容：

  ```
  ['id'=>'实验编号',
  'xml'=>'实验数据，由前端转为xml']
  ```

* 返回方式：JSON

* 内容：

  ```
  ['status'=>'生成状态，success/fail',
  'experimentId'=>'实验id',
  'link'=>'pdf文件名',
  'message'=>'错误信息',
  'test'=>'实验生成指令',
  'errorcode'=>'错误编码',
  'errorLog'=>'控制台使用的后台输出信息',]
  ```

  

### 生成Markdown报告

- url：

  ```
  /report/createMD
  ```

- 请求方式：POST

- 内容：

  ```
  ['id'=>'实验编号',
  'xml'=>'实验数据，由前端转为xml']
  ```

- 返回方式：JSON

- 内容：

  ```
  ['status'=>'生成状态，success/fail',
  'experimentId'=>'实验id',
  'link'=>'html文件名',
  'message'=>'错误信息',
  'test'=>'实验生成指令',
  'errorcode'=>'错误编码',
  'errorLog'=>'控制台使用的后台输出信息',]
  ```



## 控制台

### 新建实验

- url：

  ```
  /createLab
  ```

- 请求方式：GET

- 参数：

  ```
  'LId'=>'实验编号7位',
  'LName'=>'实验名称',
  'LTag'=>'实验分组4位'
  ```

- 返回方式：JSON

- 内容：

  ```
  ['status'=>'状态，success/fail',
  'message'=>'信息',
  'errorcode'=>'错误编码']
  ```



### 上传实验预习报告

- url：

  ```
  /console/uploadPre
  ```

- 请求方式：POST

- 内容：

  ```
  'labID'=>'实验编号',
  'prepare-pdf'=>"pdf文件，大小不超过5M"
  ```

- 返回方式：JSON

- 内容：

  ```
  ['status'=>'状态，success/fail',
  'message'=>'信息',
  'errorcode'=>'错误编码']
  ```



### 获取实验Python脚本/表格/Latex模板/Markdown模板

- url：

  ```
  /getScript /getTable /getTex /getMD
  ```

- 请求方式：GET

- 内容：

  ```
  'id'=>'实验编号'
  ```

- 返回方式：JSON

- 内容：

  ```
  ['status'=>'状态，success/fail',
  'contents'=>'相应的文件内容',
  'errorcode'=>'错误编码']
  ```



### 更新实验

- url：

  ```
  /console/updatereport
  ```

- 请求方式：POST

- 内容：

  ```
  'reportId'=>'实验编号'
  ```

- 返回方式：JSON

- 内容：

  ```
  ['status'=>'状态，success/fail',
  'message'=>'信息',
  'errorcode'=>'错误编码']
  ```



### 发布实验

- url：

  ```
  /console/confirmReport
  ```

- 请求方式：POST

- 内容：

  ```
  'reportId'=>'实验编号'
  ```

- 返回方式：JSON

- 内容：

  ```
  ['status'=>'状态，success/fail',
  'message'=>'信息',
  'errorcode'=>'错误编码']
  ```



### 删除未发布实验

- url：

  ```
  /console/delete
  ```

- 请求方式：POST

- 内容：

  ```
  'id'=>'实验编号'
  ```

- 返回方式：JSON

- 内容：

  ```
  ['status'=>'状态，success/fail',
  'message'=>'信息',
  'errorcode'=>'错误编码']
  ```





