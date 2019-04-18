var CUR_PDF = null;
var lid = 0;
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
    // if(browser()=="FF"){
        // document.getElementById('firefox_pdf').style.display='block';
        // CUR_PDF = 'firefox_pdf';
    // }
    // else if(browser()=="IE6"||browser()=="IE7"){
        // alert("Please use the above version of IE8 or other browsers");
    // }
    // else {
        // document.getElementById('chrom_pdf').style.display='block';
        // CUR_PDF = 'chrom_pdf';
        // cp('./prepare_pdf/phylab_test.pdf');
    // }
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

var showCode=0;

function initReportPage() {
    check();
    $('#report-num').text($('#collection-iframe').contents().find('#collection-list').children().length);
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
                $('#lab-' + labgroup+ '-collapse').append('<li class="list-group-item btn" id="lab-' + data.reports[labgroup][sublab]['id'] + '">' + data.reports[labgroup][sublab]['id'] + ' ' + data.reports[labgroup][sublab]['experimentName'] + ((parseInt(data.reports[labgroup][sublab]['status'])&1)?'':'(未发布)') + '</li>');
                sessionStorage.setItem(data.reports[labgroup][sublab]['id'] + '_article_id', data.reports[labgroup][sublab]['relatedArticle']);
            }
        }

        // $("#editor_tab > li").click(function(){return false;});
        $('#lab-select-modal .list-group li').click(function () {
            CUR_SUBLAB = /lab-(\d{7})/.exec(this.id)[1];
            CUR_LAB_GROUP = /lab-(\d{4})-collapse/.exec($(this).parent()[0].id)[1];
            $('#lab-select button').text($(this).children().text()).append('<span class="caret"></span>');
            $('#lab-name').text($(this).text());
            $('#lab-select-modal').modal('hide');
            //changePdf('prepare',CUR_LAB_GROUP + ".pdf");
            $('#lab-status').text('实验' + CUR_SUBLAB + '计算脚本');
            $.ajax('./getTable', {
                data: {'id': CUR_SUBLAB},
            }).done(function (data) {
                // $("#editor_tab > li").off('click');
                $('#button-view-preparation').removeAttr("disabled");
                $('#button-generate-report').removeAttr("disabled");
                $('#collect-report').attr("disabled", true);
                $('#labdoc').html(data['contents']);
                tableedit.setValue(data['contents']);
                tableedit.refresh();
				//myCodeMirror.setValue(data);
				$('#labdoc').show();
				//cmdiv.hide();
				$('#pv-button-text').html('查看数据表代码');
				showCode=0;
                recordTableValue();

                $('#labdoc table input').change(function () {
                    inputs_val = JSON.parse(localStorage.getItem($('#username').text() + CUR_SUBLAB + '-table'));
                    inputs_val[this.id] = $(this).val()
                    localStorage.setItem($('#username').text() + CUR_SUBLAB + '-table', JSON.stringify(inputs_val));
                })

            }).fail(function (xhr, status) {
                alert('失败: ' + xhr.status + ', 原因: ' + status);
            });
            $.ajax('./getScript', {
                data: {'id': CUR_SUBLAB},
            }).done(function (data) {
                pyedit.setValue(data['contents']);
                pyedit.refresh();
            }).fail(function (xhr, status) {
                alert('失败: ' + xhr.status + ', 原因: ' + status);
            });
            $.ajax('./getTex', {
                data: {'id': CUR_SUBLAB},
            }).done(function (data) {
				latexedit.setValue(data['contents']);
				latexedit.refresh();
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
	if (!showCode) {
        $('#labdoc').hide();
		cmdiv.show();
		myCodeMirror.refresh();
		showCode=1;
		$('#pv-button-text').html('预览数据表');
	} else {
		$('#labdoc').html(myCodeMirror.getValue());
        $('#labdoc').show();
		cmdiv.hide();
		$('#pv-button-text').html('查看数据表代码');
		showCode=0;
	}
});

$('#collect-report').click(function () {
    if($(this).children('.sr-only').text()=='y'){
        deleteReportStar();
    }
    else {
        createStar();
    }
});

var testa=0;

$('#create_sublab').click(function (){
	lid=$('#l_id').val();
	var lname=$('#l_name').val();
	var ltag=$('#l_tag').val();
	if (isNaN(lid)||isNaN(ltag)||lname.length<=2||lname.length>=12||lid.length!=7||ltag.length!=4) {
		alert('输入有误');
		return;
	}
	$.ajax('./createLab', {
		data: {'LId': lid, 'LName': lname, 'LTag': ltag },
	}).done(function (data) {
		if (data.status=='success')
        {
            alert('创建成功');
            location.reload();
        }

		else
		    alert(data.msg);
		$('#collection-folder').modal('hide');
	}).fail(function (xhr, status) {
		alert('失败: ' + xhr.status + ', 原因: ' + status);
	});	
});

$('#button-save-script').click(function () {
   // $("#labdoc").html(tableedit.getValue());
    $.post('./report/updatereport', {
        'reportId': CUR_SUBLAB,
        'reportScript': pyedit.getValue(),
        'reportHtml': tableedit.getValue(),
        'reportTex': latexedit.getValue()
    }).done(function (data) {
        alert(data.message);
    }).fail(function (xhr, status) {
        alert('失败: ' + xhr.status + ', 原因: ' + status);
    });
});

$('#button-push-script').click(function () {
    $.post('./report/confirmReport', {
        'reportId': CUR_SUBLAB
    }).done(function (data) {
        alert(data.message);
    }).fail(function (xhr, status) {
        alert('失败: ' + xhr.status + ', 原因: ' + status);
    });
});

/*
	WAHC 2019
*/

$("#add-labpreview-btn").click(function () {

    if (typeof CUR_LAB_GROUP === 'undefined')
    {
        alert("请先选择实验！");
        return false;
    }

})

$("#btn-upload-preview").click(function () {


    if (typeof CUR_LAB_GROUP === 'undefined')
    {
        $('#upload_preview_modal').modal('hide');
        alert("请先选择实验！");
        return false;
    }

    if ($("#input-prepare-pdf").get(0).files.length == 0)
    {
        alert("请先选择一个文件！");
        return false;
    }
    //e.preventDefault();
    let formData = new FormData();
    let file = $("#input-prepare-pdf").get(0).files[0];
    if (file.name.split('.').pop() != "pdf")
    {
        alert("文件格式不正确！");
        return false;
    }
    if (file.size > 5242800)
    {
        alert("文件过大了！");
        return false;
    }


    formData.append("prepare-pdf", file);
    formData.append("labID", CUR_LAB_GROUP);
    $.ajax({
        type:"POST",
        url:"./console/uploadPre",
        data:formData,
        contentType:false,
        processData:false
        }
    )
        .done(function (data) {
            alert(data.message);
        })
        .fail(function (xhr, status) {
            alert('失败: ' + xhr.status + ', 原因: ' + status);
        });

    $('#upload_preview_modal').modal('hide'); //or  $('#IDModal').modal('hide');
    return false;
});

$("#btn-test-generate").click(function () {

    if (typeof CUR_SUBLAB === 'undefined')
    {
        $('#upload_preview_modal').modal('hide');
        alert("请先选择实验！");
        return false;
    }


    let valid = checkInput();
    if (!valid)
        return false;


    var xmlString = SetXMLDoc_lab();
    if (xmlString === null)
        return;
    var postData = 'id=' + CUR_SUBLAB + '&' + 'xml=' + xmlString;

    $.ajax({
        type:"POST",
        url:"./report",
        data:{'id':CUR_SUBLAB, "xml":xmlString},
        beforeSend: function () {
            $('#error-log-title').text("").append("正在运行，请稍侯");
            $('#error-text').text("");
            $('#modal-error-log').modal('show');
        }
    }).done(function (data) {
        if (data['status'] == 'fail')
        {
            $('#error-log-title').text("").append("Oops！运行出错了");
        }
        else
        {
            $('#error-log-title').text("").append("运行成功");
        }
        let errStr = "";
        for (let i = 0; i < data['errorLog'].length; i++)
        {
            errStr += data['errorLog'][i];
            errStr += '<br>';
        }
        $('#error-text').text("").append(errStr);
        $('#modal-error-log').modal('show');

    }).fail(function (xhr, status) {
        alert('AJAX POST失败: ' + xhr.status + ', 原因: ' + status);
    });
});

$("#lab_table_editor_area").keyup(function () {
    $("#labdoc").html(tableedit.getValue());
});

$("#btn-delete-lab").click(function () {

    if (typeof CUR_SUBLAB=== 'undefined') {
        $('#modal-delete-confirm').modal('hide');
        alert("请先选择实验！");
        return false;
    }
});

$("#btn-delete-confirm").click(function () {

    if (typeof CUR_SUBLAB === 'undefined')
    {
        $('#modal-delete-confirm').modal('hide');
        alert("请先选择实验！");
        return false;
    }

    //check if this could be deleted
    $.get('./getreport').done(function (data) {

        let labToDelete= data.reports[CUR_LAB_GROUP].find(function (d) {
            return (d.id == CUR_SUBLAB);
        })

        if (parseInt(labToDelete.status)&1)
        {
            $('#modal-delete-confirm').modal('hide');
            alert("此报告已发布，请联系超级管理员");
            return false;
        }
        else
        {
            //delete
            $.post("./report/delete", {
                'id':CUR_SUBLAB
            }).done(function (data) {
                alert(data.message);
                location.reload();
            }).fail(function (xhr, status) {
                alert('失败: ' + xhr.status + ', 原因: ' + status);
            });
        }
        $('#modal-delete-confirm').modal('hide');

        return false;
    });

});

$('#btn-submit-error').click(function () {

    //TODO upload to backend
    $('#modal-error-log').modal('hide');
});


function checkInput()
{
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