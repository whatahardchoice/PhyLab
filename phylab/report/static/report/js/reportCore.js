var CUR_LAB_GROUP;
var CUR_SUBLAB;
var CUR_PDF;
var CUR_COMMENT_GROUPS_NUM;
var CUR_COMMENT_GROUPS_INDEX;

function check(){
    if(browser()=="FF"){
        document.getElementById('firefox_pdf').style.display='block';
        CUR_PDF = 'firefox_pdf';
    }
    else if(browser()=="IE6"||browser()=="IE7"){
        alert("Please use the above version of IE8 or other browsers");
    }
    else {
        document.getElementById('chrom_pdf').style.display='block';
        CUR_PDF = 'chrom_pdf';
        cp('/static/report/pdf/stragety.pdf');
    }
    $('#lab_collapse').collapse({
        toggle: false
    })
    $('#button-view-preparation').attr("disabled", true);
    $('#button-generate-report').attr("disabled", true);
    $('#collect-report').attr("disabled", true);
}

function errorFunction(message){
    alert(message);
}

function changePdf(type,pdfName){
    var path = ""
    if(type=="prepare"){
        path = "/static/report/pdf/prepare_pdf/";
    }
    $("#pdf_object").attr("data",path+pdfName);
    $('#pdf_embed').attr("src",path+pdfName);
    cp(path+pdfName);
}

//PhyLab2.0新增脚本
function initReportPage() {
    check();
    $(document).ready(function () {
        $('#report-num').text($($('#collection-iframe').contents().find('#collection-list')).attr('num'));
        $('#wait-report').css('height', $('#' + CUR_PDF).outerHeight());
        $('#wait-report').css('width', $('#' + CUR_PDF).outerWidth());
        $('#reply-notice').css('height', $('#comment-editor').outerHeight());
        $('#reply-notice').css('width', $('#comment-editor').outerWidth());
        $('#lab-select-modal .list-group li').click(function () {
            CUR_SUBLAB = /lab-(\d{7})/.exec(this.id)[1];
            CUR_LAB_GROUP = /lab-(\d{4})-collapse/.exec($(this).parent()[0].id)[1];
            $('#lab-select button').text($(this).children().text()).append('<span class="caret"></span>');
            $('#lab-name').text($(this).text());
            $('#lab-select-modal').modal('hide');
            changePdf('prepare',CUR_LAB_GROUP + ".pdf");
            $('#lab-status').text('实验组' + CUR_LAB_GROUP + '预习报告');
            $.ajax(REPORT_DATA_TABLE_URL, {
                data: {'id': CUR_SUBLAB},
            }).done(function (data) {
                $('#button-view-preparation').removeAttr("disabled");
                $('#button-generate-report').removeAttr("disabled");
                $('#collect-report').attr("disabled", true);
                $('#labdoc').html(data);

                recordTableValue();

                $('#labdoc table input').change(function () {
                    inputs_val = JSON.parse(localStorage.getItem($('#username').text() + CUR_SUBLAB + '-table'));
                    inputs_val[this.id] = $(this).val()
                    localStorage.setItem($('#username').text() + CUR_SUBLAB + '-table', JSON.stringify(inputs_val));
                })
                $('#button-next-comment-group').attr('disabled', true);
                $('#comment-area-title').text(CUR_SUBLAB + '评论区');
                // loadComments(sessionStorage.getItem(CUR_SUBLAB + '_article_id'), 0);
                $('#btn-group-comment-group').show();
                CUR_COMMENT_GROUPS_INDEX = 0;
            }).fail(function (xhr, status) {
                alert('失败: ' + xhr.status + ', 原因: ' + status);
            });
        });
    });
}

function recordTableValue() {
    var inputs_val = localStorage.getItem($('#username').text() + CUR_SUBLAB + '-table');
    if (inputs_val) {
        inputs_val = JSON.parse(inputs_val);
        $('#labdoc table input').each(function () {
            $(this).val(inputs_val[this.id]);
        })
    }
    else {
        inputs_val = {};
        $('#labdoc table input').each(function () {
            inputs_val[this.id] = $(this).val();
        })
        localStorage.setItem($('#username').text() + CUR_SUBLAB + '-table', JSON.stringify(inputs_val));
    }
}

$('#button-view-preparation').click(function () {
    changePdf('prepare',CUR_LAB_GROUP + ".pdf");
    $('#lab-status').text('实验组' + CUR_LAB_GROUP + '预习报告');
});

$('#button-generate-report').click(function () {
    var xmlString = SetXMLDoc_lab();
    if (xmlString === null)
        return;
    $('#wait-report').fadeIn();
    $.ajax({
        type: "POST",
        url: REPORT_GENERATE_URL,
        data: {
            "id": CUR_SUBLAB,
            "xml": xmlString
        },
        success: function(data, status){
            if(data["status"] == 'success') {
                changePdf('tmp', data['link']);
                $('#lab-status').text('子实验' + CUR_SUBLAB + '数据报告');
                $('#collect-report').attr('link', data['link']);
                $('#collect-report').removeAttr("disabled");
            }
            else
                errorFunction(data["message"]);
        },
        error: function(){
            errorFunction("生成报告失败");
        },
        complete: function(){
            $('#wait-report').fadeOut();
        }
    });
});

/*
$('#collect-report').click(function () {
    if($(this).children('.sr-only').text()=='y'){
        createStar();
    }
    else {
        deleteReportStar();
    }
});

$('#button-comment-reply').click(function () {
    sendComment(sessionStorage.getItem(CUR_SUBLAB + '_article_id'), $('#comment-editor').val());
});

$('#button-next-comment-group').click(function () {
    CUR_COMMENT_GROUPS_INDEX++;
    if (CUR_COMMENT_GROUPS_INDEX === CUR_COMMENT_GROUPS_NUM - 1) {
        $(this).attr('disabled', true);
    }
    if (CUR_COMMENT_GROUPS_INDEX === 1) {
        $('#button-prev-comment-group').removeAttr('disabled');
    }
    loadComments(sessionStorage.getItem(CUR_SUBLAB + '_article_id'), CUR_COMMENT_GROUPS_INDEX);
});

$('#button-prev-comment-group').click(function () {
    CUR_COMMENT_GROUPS_INDEX--;
    if (CUR_COMMENT_GROUPS_INDEX === 0) {
        $(this).attr('disabled', true);
    }
    if (CUR_COMMENT_GROUPS_INDEX === CUR_COMMENT_GROUPS_NUM - 2) {
        $('#button-next-comment-group').removeAttr('disabled');
    }
    loadComments(sessionStorage.getItem(CUR_SUBLAB + '_article_id'), CUR_COMMENT_GROUPS_INDEX);
});

function sendComment(article_id, message) {
    var post_hash = 0;
    $.ajax(G_BASE_URL + '/wecenter/?/article/ajax/phash/', {
        method: 'post',
        async: false
    }).done(function (data) {
        post_hash = JSON.parse(data)['rsm']['new_post_hash'];
    }).fail(function (xhr, status) {
        alert('失败: ' + xhr.status + ', 原因: ' + status);
    });
    $.post(G_BASE_URL + '/wecenter/?/article/ajax/save_comment/', {
        'post_hash': post_hash,
        'article_id': article_id,
        'message': message
    }).done(function (data) {
        data = JSON.parse(data);
        if (data['errno'] === 1) {
            $('#reply-notice-check').attr('class', 'fa fa-check');
            $('#reply-notice-text').text('评论成功');
        }
        else {
            $('#reply-notice-check').attr('class', 'fa fa-exclamation');
            $('#reply-notice-text').text(data['err']);
        }
        loadComments(sessionStorage.getItem(CUR_SUBLAB + '_article_id'), 0);
        //alert('成功, 收到的数据: ' + JSON.parse(data));
    }).fail(function (xhr, status) {
        $('#reply-notice-check').attr('class', 'fa fa-exclamation');
        $('#reply-notice-text').text('评论失败');
    }).always(function () {
        $('#reply-notice').fadeIn();
        setTimeout('$(\'#reply-notice\').fadeOut()', 1500);
    });
}

function loadComments(article_id, group_id) {
    $('#comment-area').html(
        '<table id="table-comment-area" class="table table-hover"> \
            <tr> \
                <th style="width: 10%;">用户名</th> \
                <th style="width: 90%;">评论</th> \
            </tr> \
        </table>');
    $.post(G_BASE_URL + '/wecenter/?/article/ajax/get_comments/', {
        'article_id': article_id,
        'page': 0
    }).done(function (data) {
        data = JSON.parse(data);
        if (data['errno'] === 1) {
			$('#button-comment-reply').removeAttr('disabled');
            var i = 4;
            var comments_count = data['rsm']['comments_count'];
            var comment_groups_base = comments_count - (group_id + 1) * 5;
            var last_group_comments_num = comments_count % 5;
            CUR_COMMENT_GROUPS_NUM = Math.ceil(comments_count / 5);
            if (group_id === CUR_COMMENT_GROUPS_NUM - 1 && last_group_comments_num > 0) {
                i = last_group_comments_num - 1;
                comment_groups_base = 0;
            }
            for (; i >= 0; i--) {
                $('#table-comment-area').append(
                    '<tr> \
                        <td>' + data['rsm']['comments'][(comment_groups_base + i).toString()]['user_info']['user_name'] + '</td> \
                        <td>' + data['rsm']['comments'][(comment_groups_base + i).toString()]['message'] + '</td> \
                    </tr>');
            }
            $('#btn-group-comment-group').show();
            if (CUR_COMMENT_GROUPS_NUM > 1 && group_id < CUR_COMMENT_GROUPS_NUM - 1)
                $('#button-next-comment-group').removeAttr('disabled');
        }
        else
			$('#button-comment-reply').attr('disabled','1');
    }).fail(function (xhr, status) {
        alert('失败: ' + xhr.status + ', 原因: ' + status);
    });
}*/
