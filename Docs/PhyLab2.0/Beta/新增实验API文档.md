# 新增实验API文档

### 更新报告
- url: 
    ```
    /report/updatereport
    ```
- 请求方式：POST
- 内容：
    ```
    "reportId"=>"报告的id"
    "reportScript"=>"对应的报告的脚本文本内容"
    "reportHtml"=>"对应的报告的HTML文本内容"
    "reportTex"=>"对应的报告的TEX文件文本内容"
    ```
- 返回方式：Json
- 内容：
    ```
    "status"=>SUCCESS_MESSAGE && "message"=>"更新成功" || 
    "status"=>FAIL_MESSAGE && "message"=>"更新失败"
    ```

### 确认报告
- url: 
    ```
    /report/confirmReport
    ```
- 请求方式：POST
- 内容：
    ```
    "reportId"=>"报告的id"
    ```
- 返回方式：Json
- 内容：
    ```
    "status"=>SUCCESS_MESSAGE && "message"=>"发布成功" || 
    "status"=>FAIL_MESSAGE && "message"=>"发布失败" ||
    "status"=>FAIL_MESSAGE && "message"=>"没有权限"
    ```

