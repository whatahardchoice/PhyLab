
errorcode-expression
====


错误码|错误说明                       |所在文件           |函数
-----|-------------------------------|------------------|-----
0000 |无错误                         |无                 |无
     |                               |                  |    
7201|用户信息更新错误，数据库操作失败   |UserController.php|update|
7202|头像文件流异常                   |UserController.php|setAvatar
7203|数据库操作异常                   |UserController.php|setAvatar
7204|文件格式错误                     |UserController.php|setAvatar
7205|上传参数异常                     |UserController.php|setAvatar
    |                                |                  |      
7301|文件操作异常                     |ConsoleController.php|getTable
7302|文件操作异常|ConsoleController.php|getScript
7303|文件操作异常|ConsoleController.php|getTex
7304|文件操作异常|ConsoleController.php|getMD
7305|报告号码已存在|ConsoleController.php|createSublab
7306|上传失败，文件格式或大小不符合要求|ConsoleController.php|uploadPreparePdf
7307|上传失败，没有找到文件|ConsoleController.php|uploadPreparePdf
|||
7401|脚本生成失败|ReportController.php|createTex
7402|脚本生成失败|ReportController.php|createMD
7403|报告不存在|ReportController.php|show
7404|报告不存在|ReportController.php|getXmlForm
7405|无权限|ReportController.php|updateReport
7406|没有更新权限,请联系root管理员|ReportController.php|updateReport
7407|更新失败(wrong_id)|ReportController.php|updateReport
7408|没有发布权限，请联系root管理员|ReportController.php|confirmReport
7409|发布失败|ReportController.php|confirmReport
7410|没有权限|ReportController.php|deleteUnpublishedReport
7411|实验id不存在|ReportController.php|deleteUnpublishedReport
7412|实验已发布，请联系root管理员|ReportController.php|deleteUnpublishedReport
|||
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
|||
7601|身份验证成功|DesexpController.php|index
7602|未找到实验|DesexpController.php|getDesexp


