var lid = 0;

function check(){
    $('#lab_collapse').collapse({
        toggle: false
    })
    $('#button-view-preparation').attr("disabled", true);
    $('#button-generate-report').attr("disabled", true);
    $('#collect-report').attr("disabled", true);
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
                if(data['errorcode']!="0000"){
                    alert(data['errorcode']);
                }
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
                if(data['errorcode']!="0000"){
                    alert(data['errorcode']);
                }
                pyedit.setValue(data['contents']);
                pyedit.refresh();
            }).fail(function (xhr, status) {
                alert('失败: ' + xhr.status + ', 原因: ' + status);
            });
            $.ajax('./getTex', {
                data: {'id': CUR_SUBLAB},
            }).done(function (data) {
                if(data['errorcode']!="0000"){
                    alert(data['errorcode']);
                }
				latexedit.setValue(data['contents']);
				latexedit.refresh();
            }).fail(function (xhr, status) {
                alert('失败: ' + xhr.status + ', 原因: ' + status);
            });
            $.ajax('./getMD', {
                data: {'id': CUR_SUBLAB},
            }).done(function (data) {
                if(data['errorcode']!="0000"){
                    alert(data['errorcode']);
                }
                mdedit.setValue(data['contents']);
                mdedit.refresh();
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
		    alert(data.msg+data['errorcode']);
		$('#collection-folder').modal('hide');
	}).fail(function (xhr, status) {
		alert('失败: ' + xhr.status + ', 原因: ' + status);
	});	
});

$('#button-save-script').click(function () {

    if (typeof CUR_SUBLAB === 'undefined')
    {
        alert("请先选择实验！");
        return false;
    }
   // $("#labdoc").html(tableedit.getValue());
    $.post('./report/updatereport', {
        'reportId': CUR_SUBLAB,
        'reportScript': pyedit.getValue(),
        'reportHtml': tableedit.getValue(),
        'reportTex': latexedit.getValue(),
        'reportMD': mdedit.getValue()
    }).done(function (data) {
        alert(data.message);
    }).fail(function (xhr, status) {
        alert('失败: ' + xhr.status + ', 原因: ' + status);
    });
});

$('#button-push-script').click(function () {

    if (typeof CUR_SUBLAB === 'undefined')
    {
        alert("请先选择实验！");
        return false;
    }


    $.post('./report/confirmReport', {
        'reportId': CUR_SUBLAB
    }).done(function (data) {
        alert(data.message);
    }).fail(function (xhr, status) {
        alert('失败: ' + xhr.status + ', 原因: ' + status);
    });
});

/*
	Contributed by team WhatAHardChoice 2019.04
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
            alert(data.message+data['errorcode']);
        })
        .fail(function (xhr, status) {
            alert('失败: ' + xhr.status + ', 原因: ' + status);
        });

    $('#upload_preview_modal').modal('hide'); //or  $('#IDModal').modal('hide');
    return false;
});

$("#btn-test-generate-tex").click(function () {

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
        url:"./report/createTex",
        data:{'id':CUR_SUBLAB, "xml":xmlString},
        beforeSend: function () {
            $('#error-log-title').text("").append("正在运行，请稍侯");
            $('#error-text').text("");
            $('#modal-error-log').modal('show');
        }
    }).done(function (data) {
        if (data['status'] == 'fail')
        {
            $('#error-log-title').text(data['errorcode']).append("Oops！运行出错了");
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



$("#btn-test-generate-md").click(function () {

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
        url:"./report/createMD",
        data:{'id':CUR_SUBLAB, "xml":xmlString},
        beforeSend: function () {
            $('#error-log-title').text("").append("正在运行，请稍侯");
            $('#error-text').text("");
            $('#modal-error-log').modal('show');
        }
    }).done(function (data) {
        if (data['status'] == 'fail')
        {
            $('#error-log-title').text(data['errorcode']).append("Oops！运行出错了");
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
        alert("您有"+invalid+"处输入不合法，请检查输入，输入只能为数字且不能有空格.");
        return false;
    }
    else
        return true;
}