# Postman接口测试使用说明

## 安装

从这里下载安装：[Postman](<http://www.getpostman.com/>)

参考安装文档：[安装文档](<https://learning.getpostman.com/docs/postman/launching_postman/installation_and_updates/>)

## 导入测试内容

在Postman界面左上角找到`Import`字样的按钮，导入项目根目录下postman文件夹中的json文件。

导入后可以看到Collections栏目下新增了一个叫做test的测试集。

按第二篇技术博客（见结尾）配置好工作目录，将files文件夹下的内容移动到工作目录内。

## 修改变量

将鼠标移到test测试集上，点击`...->Edit`

修改其中的Variables栏目中**domain**项为部署项目所在的域名。该变量会被替换到每个测试的请求域名中。

## 运行测试

将鼠标移到test测试集上，点击`三角->Run`打开Runner

在Runner中勾选**Run collection without using stored cookies**，去掉**Save cookies after collection run**，点击蓝色运行按钮即可。

## 如何修改及新增测试

请参考这两篇博客以及[Postman文档](<https://learning.getpostman.com/docs/postman/launching_postman/installation_and_updates/>)

[【技术博客】 利用Postman和Jmeter进行接口性能测试](https://www.cnblogs.com/hardchoice/p/10947275.html)

[【技术博客】Postman接口测试教程 - 环境、附加验证、文件上传测试](https://www.cnblogs.com/hardchoice/p/11042356.html)