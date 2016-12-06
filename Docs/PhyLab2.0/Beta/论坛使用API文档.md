# 论坛API文档

框架的逻辑结构存贮在app目录下

## 新建文章

- app/publish/main.php中的article_action()函数设置文章的publish路径。
- app/publish/ajax.php中定义了各种交互函数。

    - publish_article_action()新建文章。
        - title 文章标题
        - category_id 文章分类id
        - message 消息
        - attach_ids 附件ids
        - seccode_verify 验证码
        - topics 话题
        - post_hash hash值
        - attach_access_key 上传安全码
        - _is_mobile 是否为手机

    - modify_article_action()修改文章。
        - article_id 文章id
        - title 标题
        - category_id 文章分类id
        - message 消息
        - seccode_verify 验证码
        - attach_ids 附件ids
        - post_hash hash值
        - do_delete 是否删除
        - topics 话题

    - publish_question_action()新建问题。
        - question_content 问题内容
        - category_id 文章分类id
        - question_detail 问题详情
        - seccode_verify 验证码
        - topics 话题
        - attach_ids 附件ids
        - weixin_media_id 微信id
        - post_hash hash值
        - attach_access_key 上传安全码
        - ask_user_id 问者id
        - _is_mobile 是否为手机

    - modify_question_action()修改问题。
        - question_id 问题id
        - category_id 文章分类id
        - question_content 问题内容
        - question_detail 问题详情
        - attach_ids 附件ids
        - seccode_verify 验证码
        - post_hash hash值
        - do_delete 是否删除
        - modify_reason 修改理由

## 获取文章内容 & 添加评论

- app/article/main.php中的index_action()函数设置获取相关文章。
- 需要往index_action()通过GET方式传输id(article的id),item_id(评论的id)。
- app/article/ajax.php中定义了各种交互函数。

    - article_vote_action()赞同文章或评论。
        - type 文章类型"article" or "comment"
        - item_id 目标id
        - rating 排名

    - remove_comment_action()删除评论。
        - comment_id 评论id

    - remove_article_action()删除文章。
        - article_id 文章id

    - save_comment_action()存储评论。
        - article_id 文章id
        - message 消息
        - seccode_verify 验证码
        - post_hash hash值
        - at_uid 存储的目标id
        - _is_mobile 是否为手机
