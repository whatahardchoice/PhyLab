
var labDoc3dot1415926;
var CUR_LAB_GROUP;
var CUR_SUBLAB;
var CUR_PDF;
var CUR_COMMENT_GROUPS_NUM;
var CUR_COMMENT_GROUPS_INDEX;
function lab(index){
    this.index = index;
    this.dbId = getDbId(index);
    this.xmlTxt;
    this.flush = function(){
        this.xmlTxt=SetXMLDoc_lab();
    }
    this.getIndex = function(){
        return this.index;
    }
    this.getXML = function(){
        if(this.xmlTxt!=null)return this.xmlTxt;
    }
    this.getDbId = function(){
        return this.dbId;
    }
}
function getDbId(index){
    return $('#back_info a[index='+index+']').attr('db-id');
}
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
        cp('./prepare_pdf/phylab_test.pdf');
    }
    $('#lab_collapse').collapse({
        toggle: false
    })
    $('#button-view-preparation').attr("disabled", true);
    $('#button-generate-report').attr("disabled", true);
    $('#collect-report').attr("disabled", true);
}
function eleDisable(){
    SetDisable('importBtn',true);
    SetDisable('collectBtn',true);
    SetDisable('selectBtn',true);
    SetDisable('exportBtn',true);
    SetDisable('InputLabIndex',true);
}
function eleEnable(){
    SetDisable('importBtn',false);
    SetDisable('collectBtn',true);
    SetDisable('exportBtn',true);
}
function eleReset(){
    SetDisable('selectBtn',false);
    SetDisable('importBtn',false);
    SetDisable('InputLabIndex',false);
}
function collectEnable(){
    SetDisable('collectBtn',false);
    document.getElementById('collectIco').setAttribute("class","glyphicon glyphicon-star-empty");
    document.getElementById('collectText').innerHTML = "收藏";
}

function collectLab(ico_id,txt_id){
    var ico = document.getElementById(ico_id);
    var txt = document.getElementById(txt_id);
    var check = txt.innerHTML;
    if(check=="取消收藏"){
        deleteReportStar(ico,txt);
    }
    else if(check=="收藏"){
        createStar(ico,txt);
    }
    else
        alert("Button text can not be [txt] when use this function!Please Use 收藏/取消收藏");
}
function SelectLab(index,ref){
    var lt = document.getElementById(ref);
    if((new RegExp("^10(11|12|21|22|31|41|61|71|81|82|91)$")).test(index)){
        labDoc3dot1415926 = new lab(index);
        lt.innerHTML = '实验' + index + '分组预习报告';
        return true;
    }
    else{
        return false;
    }
}

//USE jquery version 2.1.4, bootstrap.min.js
function inputCheck(){
    var a = $.merge($("input.para"),$("input.var"));
    for(var i = 0; i<a.length; i++) a[i].setAttribute("value",a[i].getAttribute("aria-label"));
}
function selectBtnClick(){
    if(SelectLab($('#InputLabIndex')[0].value,'LabText')){
        $('.alert').hide();
        $('#LabStatus')[0].innerHTML = "预览";
        changePdf('prepare',labDoc3dot1415926.getIndex()+".pdf");
        eleEnable();
    }
    else{
        $('.alert').show();
    }
}
function importBtnClick(){
    $("#lab_table_"+labDoc3dot1415926.getIndex()).modal("toggle");
}
function collectBtnClick(){
    collectLab('collectIco','collectText');
}
function exportBtnClick(){
    eleDisable();
    try{
        $('#lab_collapse').collapse('hide');
        $('#loading-container').fadeIn();
        setTimeout('Post_lab(errorFunction)',1000+Math.random()*2000);
    }catch(e){
        $('#loading-container').fadeOut();
        error();
    }
}

function errorFunction(message){
    alert(message);
}

$("#InputLabIndex").bind("keypress",function(){
    if(event.keyCode==13) {
        if(SelectLab($('#InputLabIndex')[0].value,'LabText')){
            $('.alert').hide();
            $('#LabStatus')[0].innerHTML = "预览";
            changePdf('prepare',labDoc3dot1415926.getIndex()+".pdf");
            eleEnable();
            return false;
        }
        else $('.alert').show();
        return false;
    }
    else return true;
})
$('a.lab_title').bind('click',function(){
    //USE reportCore.js, bootstrap.min.js
    if($('#InputLabIndex').attr("disabled")=="disabled")return;
    if(SelectLab(this.title,'LabText')){
        $('.alert').hide();
        $('#LabStatus')[0].innerHTML = "预览";
        changePdf('prepare',labDoc3dot1415926.getIndex()+".pdf");
        eleEnable();
    }
    else $('.alert').show();
});
$('a.lab_index').bind('click',function(){
    //USE reportCore.js, bootstrap.min.js
    if($('#InputLabIndex').attr("disabled")=="disabled")return;
    if(SelectLab(this.innerHTML,'LabText')){
        $('.alert').hide();
        $('#LabStatus')[0].innerHTML = "预览";
        changePdf('prepare',labDoc3dot1415926.getIndex()+".pdf");
        eleEnable();
    }
    else $('.alert').show();
});
$('input.para').bind('keyup',function(){
    if((new RegExp("^\\d+(.\\d+)?$")).test(this.value)==false) $(this).addClass("wrong-input");
    else $(this).removeClass("wrong-input")
})
$('input.var').bind('keyup',function(){
    if((new RegExp("(^\\d+(.\\d+)?$)|(^$)")).test(this.value)==false) $(this).addClass("wrong-input");
    else $(this).removeClass("wrong-input")
})
$('button.btn-Save').bind('click',function(){
    var index = labDoc3dot1415926.getIndex();
    var paraArray,varArray;
    var labStr = "", wrong_count = 0, i = 1, ErrType = 1;
    while((tp=document.getElementById("check_"+index+"_"+i))!=null){
        if(tp.checked)labStr += "input.para"+"."+index+"_"+i+",";
        i++;
    }
    //get selected sublab
    if(labStr==""){
        document.getElementById("ErrorText_"+index).innerHTML = "请先选择需要保存数据的子实验（￣▽￣）~*　)";
        setShowHide("btnError_"+index,"btnSave_"+index,3000);
    }
    else{
        labStr = labStr.substring(0,labStr.lastIndexOf(','));
        paraArray = $(labStr);
        labStr = labStr.replace(new RegExp("para","gm"),"var");
        varArray = $(labStr);
        //get data form input, para can't be null
        paraArray.each(function(){
            if($(this).hasClass("wrong-input")) wrong_count++;
            else if(this.value==""){
                wrong_count++;
                $(this).addClass("wrong-input");
            }
            //else if((new RegExp("(^\\d+(.\\d+)?$)")).test(this.value)==false){error();return false;}
        })
        varArray.each(function(){
            if($(this).hasClass("wrong-input")) wrong_count++;
            //else if((new RegExp("(^\\d+(.\\d+)?$)|(^$)")).test(this.value)==false){error();return false;}
        })
        //check data
        if(wrong_count==0){
            $("#lab_table_"+index).modal('toggle');
            if(labStr!=""){
                SetDisable('exportBtn',false);
                labDoc3dot1415926.flush();
            }//when no selected sublab exist, just close modal
        }
        else{
            document.getElementById("ErrorText_"+index).innerHTML = "有"+wrong_count+"处输入不合法，请检查标红输入框";
            setShowHide("btnError_"+index,"btnSave_"+index,3000);
        }
    }
})

function changePdf(type,pdfName){
    var path = ""
    if(type=="prepare"){
        path = "./prepare_pdf/";
    }
    else if(type=="tmp"){
        path = "./pdf_tmp/";
    }
    else if(type=="star"){
        path = "./star_pdf/"
    }
    $("#pdf_object").attr("data",path+pdfName);
    $('#pdf_embed').attr("src",path+pdfName);
    cp(path+pdfName);
}
function Post_lab(postErrorFunc){
    var xmlString = labDoc3dot1415926.getXML();
    var dbId = labDoc3dot1415926.getDbId();
    var postData = "xml="+encodeURI(xmlString)+"&id="+dbId;
    PostAjax("./report",postData,function(){
        if (this.readyState==4 && this.status==200){
            var jsonText = eval("(" + this.responseText + ")");
            //alert(this.responseText);
            //alert(jsonText["status"]);
            if(jsonText["status"]=='success'){
                changePdf('tmp',jsonText['link']);
                $('#collectBtn').attr('link',jsonText['link']);
                $('#loading-container').fadeOut();
                eleReset();
                $('#LabStatus')[0].innerHTML = "数据";
                collectEnable();
            }
            else{
                postErrorFunc(jsonText["message"]);
                $('#loading-container').fadeOut();
                eleReset();
            }
        }
        else if(this.readyState==4 && this.status!=200){
            postErrorFunc("生成报告失败");
            $('#loading-container').fadeOut();
            eleReset();
        }
    });
}

//PhyLab2.0新增脚本
function initReportPage() {
    check();
    $(document).ready(function () {
        $('#report-num').text($($('#collection-iframe').contents().find('#collection-list')).attr('num'));
    });
    $('#wait-report').css('height', $('#' + CUR_PDF).outerHeight());
    $('#wait-report').css('width', $('#' + CUR_PDF).outerWidth());
    $('#reply-notice').css('height', $('#comment-editor').outerHeight());
    $('#reply-notice').css('width', $('#comment-editor').outerWidth());
    // $.get('./report').done(function (data) {
    //     data = JSON.parse(data);
    // });
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
            changePdf('prepare',CUR_LAB_GROUP + ".pdf");
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
    var postData = 'id=' + CUR_SUBLAB + '&' + 'xml=' + xmlString;
    $('#wait-report').fadeIn();
    PostAjax("./report",postData,function(){
        if (this.readyState==4 && this.status==200){
            var jsonText = eval("(" + this.responseText + ")");
            //alert(this.responseText);
            //alert(jsonText["status"]);
            if(jsonText["status"]=='success') {
                changePdf('tmp',jsonText['link']);
                $('#lab-status').text('子实验' + CUR_SUBLAB + '数据报告');
                $('#collect-report').attr('link',jsonText['link']);
                $('#collect-report').removeAttr("disabled");
            }
            else
                errorFunction(jsonText["message"]);
            $('#wait-report').fadeOut();
        }
        else if(this.readyState==4 && this.status!=200) {
            $('#wait-report').fadeOut();
            errorFunction("生成报告失败");
        }
    });
});

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
}