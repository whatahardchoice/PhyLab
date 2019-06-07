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
        cp('./prepare_pdf/phylab_test.pdf',0);
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

/**
 * This function is used to change files to show(including pad and html)
 *
 * @param type
 * @param fileName
 * @param ishtml
 */
function changePdf(type,fileName,ishtml){
    var path = "";
    if(type=="prepare"){
        path = "./prepare_pdf/";
    }
    else if(type=="tmp"){
        path = "./pdf_tmp/";
    }
    else if(type=="star"){
        path = "./star_pdf/";
    }
    if(ishtml){
        document.getElementById("show-html").style.display = 'block';
        $('#html-iframe').attr("src",path+fileName);
    }else{
        document.getElementById("show-html").style.display = 'none';
    }
    $("#pdf_object").attr("data",path+fileName);
    $('#pdf_embed').attr("src",path+fileName);
    cp(path+fileName,ishtml);
}

//PhyLab2.0新增脚本
/**
 * This function is to show report index
 */
function initReportPage() {
    check();
    window.onload = function() {
      var num = document.getElementById('collection-iframe').contentWindow.document.getElementById('collection-list').getAttribute('data-value');
      $('#report-num').text(num);
    };
    $('#wait-report').css('height', $('#' + CUR_PDF).outerHeight());
    $('#wait-report').css('width', $('#' + CUR_PDF).outerWidth());
    $('#reply-notice').css('height', $('#comment-editor').outerHeight());
    $('#reply-notice').css('width', $('#comment-editor').outerWidth());

    $.get('./getreport').done(function (data) {
        for (var labgroup in data.reports) {
            $('#lab-list').append(
                '<div class="panel panel-default" id="lab-group-' + labgroup+ '"> \
                    <div class="panel-heading btn" id="lab-' + labgroup+ '-heading" role="tab"> \
                      <h4 class="panel-title">\
                        <div data-toggle="collapse" data-parent="#accordion" href="#lab-' + labgroup+ '-collapse" aria-expanded="false" aria-controls="lab-' + labgroup+ '-collapse"> \
                          ' + labgroup+ '\
                        </div> \
                      </h4> \
                    </div> \
                    <div class="panel-collapse collapse list-group" id="lab-' + labgroup+ '-collapse" role="tabpanel" aria-labelledby="lab-' + labgroup+ '-heading">\
                    </div> \
                  </div>');
            for (var sublab in data.reports[labgroup]) {
                $('#lab-' + labgroup+ '-collapse').append('<li class="list-group-item btn" id="lab-' + data.reports[labgroup][sublab]['id'] + '">' + data.reports[labgroup][sublab]['id'] + ' ' + data.reports[labgroup][sublab]['experimentName'] + '</li>')
                sessionStorage.setItem(data.reports[labgroup][sublab]['id'] + '_article_id', data.reports[labgroup][sublab]['relatedArticle']);
            }
        }
        $('#lab-select-modal .list-group li').click(function () {
            CUR_SUBLAB = /lab-(\d{7})/.exec(this.id)[1];
            CUR_LAB_GROUP = /lab-(\d{4})-collapse/.exec($(this).parent()[0].id)[1];
            $('#lab-select button').text($(this).children().text()).append('<span class="caret"></span>');
            $('#lab-name').text($(this).text());
            $('#lab-select-modal').modal('hide');
            changePdf('prepare',CUR_LAB_GROUP + ".pdf",0);
            $('#lab-status').text('实验组' + CUR_LAB_GROUP + '预习报告');
            $.ajax('./table', {
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
                loadComments(sessionStorage.getItem(CUR_SUBLAB + '_article_id'), 0);
                $('#btn-group-comment-group').show();
                CUR_COMMENT_GROUPS_INDEX = 0;
            }).fail(function (xhr, status) {
                alert('失败: ' + xhr.status + ', 原因: ' + status);
            });
        });
    }).fail(function (xhr, status) {
        alert('失败: ' + xhr.status + ', 原因: ' + status);
    });
}

/**
 * To cache your data
 */
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

/**
 * To show preparation report
 */
$('#button-view-preparation').click(function () {
    changePdf('prepare',CUR_LAB_GROUP + ".pdf",0);
    $('#lab-status').text('实验组' + CUR_LAB_GROUP + '预习报告');
});

$('#button-generate-report').click(function () {

    if (typeof CUR_SUBLAB === 'undefined')
    {
        $('#modal-report-select').modal('hide');
        alert("请先选择实验！");
        return false;
    }
});

/**
 * To create latex report
 */
$('#button-generate-latex').click(function () {

    if (typeof CUR_SUBLAB === 'undefined')
    {
        $('#upload_preview_modal').modal('hide');
        alert("请先选择实验！");
        return false;
    }

    let valid = checkInput();
    if (!valid)
    {
        $('#modal-report-select').modal('hide');
        return false;
    }

    var xmlString = SetXMLDoc_lab();
    if (xmlString === null)
        return;
    var postData = 'id=' + CUR_SUBLAB + '&' + 'xml=' + xmlString;
    $('#wait-report').fadeIn();
    PostAjax("./report/createTex",postData,function(){
        if (this.readyState==4 && this.status==200){
            var jsonText = eval("(" + this.responseText + ")");
            if(jsonText["status"]=='success') {

                changePdf('tmp',jsonText['link'],0);
                $('#preading-report').attr('disabled', false);
                $('#preading-report').attr("onclick", "openTab('./pdf_tmp/"+jsonText['link']+"');");
                alert('生成latex成功！');
                $('#lab-status').text('子实验' + CUR_SUBLAB + '数据报告');
                $('#collect-report').attr('link',jsonText['link']);
                $('#collect-report').removeAttr("disabled");
            }
            else
                errorFunction(jsonText["message"]+jsonText["errorcode"]);
            $('#wait-report').fadeOut();
        }
        else if(this.readyState==4 && this.status!=200) {
            $('#wait-report').fadeOut();
            errorFunction("生成报告失败readyState==4 status!=200");
        }
    });
    $('#modal-report-select').modal('hide');
});

/**
 * To create html report
 */
$('#button-generate-markdown').click(function () {
    if (typeof CUR_SUBLAB === 'undefined')
    {
        $('#upload_preview_modal').modal('hide');
        alert("请先选择实验！");
        return false;
    }

    let valid = checkInput();
    if (!valid)
    {
        $('#modal-report-select').modal('hide');
        return false;
    }

    var xmlString = SetXMLDoc_lab();
    if (xmlString === null)
        return;
    var postData = 'id=' + CUR_SUBLAB + '&' + 'xml=' + xmlString;
    $('#wait-report').fadeIn();
    PostAjax("./report/createMD",postData,function(){
        if (this.readyState==4 && this.status==200){
            var jsonText = eval("(" + this.responseText + ")");
            if(jsonText["status"]=='success') {
                changePdf('tmp',jsonText['link'],1);
                $('#preading-report').attr('disabled', false);
                $('#preading-report').attr("onclick", "openTab('./pdf_tmp/"+jsonText['link']+"');");
                alert('生成markdown成功！');
                $('#lab-status').text('子实验' + CUR_SUBLAB + '数据报告');
            }
            else
                errorFunction(jsonText["message"]+jsonText["errorcode"]);
            $('#wait-report').fadeOut();
        }
        else if(this.readyState==4 && this.status!=200) {
            $('#wait-report').fadeOut();
            errorFunction("生成报告失败readyState==4 status!=200");
        }
    });

    $('#modal-report-select').modal('hide');
});

$('#collect-report').click(function () {
    if($(this).children('.sr-only').text()=='y'){
        createStar();
    }
    else {
        deleteReportStar();
    }
});

/**
 * Comment function
 */

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
        alert('失败: ' + xhr.status + ', 原因: ' + status+'8003');
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
        alert("评论失败8003");
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
        alert('失败: ' + xhr.status + ', 原因: ' + status+'8003');
    });
}

function checkInput()
{
    if(CUR_SUBLAB == '1020113')
        return true;

    let inputs = $("#labdoc").find("input");
    if (inputs.length === 0)
        return false;
    let invalid = 0;
    let pattern = new RegExp('^\\d+(.\\d+)?$');
    for (let i = 0; i < inputs.length; i++)
    {
        if (!pattern.test(inputs[i].value)) {
            invalid++;
        }
    }
    if (invalid !== 0)
    {
        alert("您有"+invalid+"处输入不合法，请检查输入，输入只能为数字且不能有空格。");
        return false;
    }
    else
        return true;
}

$('#btn-submit-error').click(function () {

    //TODO upload to backend
    $('#modal-error-log').modal('hide');
});


function openTab(url) {
    // Create link in memory
    var a = window.document.createElement("a");
    a.target = '_blank';
    a.href = url;

    // Dispatch fake click
    var e = window.document.createEvent("MouseEvents");
    e.initMouseEvent("click", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
    a.dispatchEvent(e);
};
