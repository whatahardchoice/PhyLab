
errorcode-expression
====
错误说明只作为参考，具体问题请到错误码所在文件的函数进行分析
---------


错误码|错误说明                       |所在文件           |函数
-----|-------------------------------|------------------|-----
0000 |无错误                         |无                 |无
-----|-------------------------------|------------------|-----
7101|登陆失败|PhylabAuthController.php|postLogin
7102|注册请求无效|PhylabAuthController.php|postRegister
-----|-------------------------------|------------------|-----
7201|用户信息更新错误，数据库操作失败   |UserController.php|update
7202|头像文件流异常                   |UserController.php|setAvatar
7203|数据库操作异常                   |UserController.php|setAvatar
7204|文件格式错误                     |UserController.php|setAvatar
7205|上传参数异常                     |UserController.php|setAvatar
-----|-------------------------------|------------------|-----
7301|无权限                           |ConsoleController.php|updateReport
7302|没有更新权限，联系root管理员       |ConsoleController.php|updateReport
7303|更新失败(wrong_id)                |ConsoleController.php|updateReport
7304|没有发布权限，联系root管理员        |ConsoleController.php|confirmRport
7305|发布失败                          |ConsoleController.php|confirmRport
7306|没有权限                          |ConsoleController.php|deleteUnpublishedReport
7307|实验id不存在                          |ConsoleController.php|deleteUnpublishedReport
7308|实验已发布，请联系root管理员         |ConsoleController.php|deleteUnpublishedReport
7309|文件操作异常                     |ConsoleController.php|getTable
7310|文件操作异常|ConsoleController.php|getScript
7311|文件操作异常|ConsoleController.php|getTex
7312|文件操作异常|ConsoleController.php|getMD
7313|报告号码已存在|ConsoleController.php|createSublab
7314|上传失败，文件格式或大小不符合要求|ConsoleController.php|uploadPreparePdf
7315|上传失败，没有找到文件|ConsoleController.php|uploadPreparePdf
-----|-------------------------------|------------------|-----
7401|exception|ReportController.php|createTex
7402|脚本生成失败|ReportController.php|createTex
7403|exception|ReportController.php|createMD
7404|脚本生成失败|ReportController.php|createMD
7405|exception|ReportController.php|show
7406|exception|ReportController.php|getXmlForm
-----|-------------------------------|------------------|-----
7501|检查失败|StarController.php|create
7502|没有此类型报告|StarController.php|create
7503|报告查询失败|StarController.php|create
7504|收藏报告失败|StarController.php|create
7505|超过收藏最大值|StarController.php|create
7506|收藏创建失败|StarController.php|create
7507|不存在pdf文件|StarController.php|create
7508|删除失败|StarController.php|delete
7509|删除失败|StarController.php|delete
7510|未找到文件|StarController.php|show
-----|-------------------------------|------------------|-----
7601|身份验证成功|DesexpController.php|index
7602|未找到实验|DesexpController.php|getDesexp
-----|-------------------------------|------------------|-----
7701|没有权限更新公告栏|IndexController.php|modifyBulletin|
-----|-------------------------------|------------------|-----
8001|ajax.fail()网络问题|consoleCore.js|所有函数
8002|ajax.fail()网络问题|desexp.js|所有函数
8003|ajax.fail()网络问题|reportCore.js|所有函数
8004|ajax.fail()网络问题|user.js|所有函数



