function errorAlert(xmessage){
    var message = typeof(xmessage) == "undefined"?null:xmessage;
    message = message==null ? "未知错误,该收藏可能已被删除":message;
    alert("操作失败："+message);
}
function createStar(){
    var url="/user/star";
    var postData = "link="+encodeURI($('#collect-report').attr('link'))+"&reportId="+CUR_SUBLAB;
    PostAjax(url,postData,function(){
        if (this.readyState==4 && this.status==200){
            var jsonText = eval("(" + this.responseText + ")");
            //alert(this.responseText);
            //alert(jsonText["status"]);
            if(jsonText["status"]=='success'){
                $('#collect-report').attr('dbid',jsonText['id']);
                $('#collect-report i').attr('class','fa fa-bookmark');
                $('#collect-report-text').text('取消收藏');
                $('#collect-report .sr-only').text("n");
                alert("已添加至个人收藏夹！");
                $('#collection-iframe').attr('src', $('#collection-iframe').attr('src'));
                $('#report-num').text(parseInt($('#report-num').text()) + 1);
            }
            else{
                errorAlert(jsonText["message"]+jsonText["errorcode"]);
            }
        }
        else if(this.readyState==4 && this.status!=200){
            errorAlert(null);
        }
    });
}
function deleteReportStar(){
    var url="/user/star";
    var postData = "_method=DELETE&id="+encodeURI($('#collect-report').attr('dbid'));
    PostAjax(url,postData,function(){
        if (this.readyState==4 && this.status==200){
            var jsonText = eval("(" + this.responseText + ")");
            //alert(this.responseText);
            //alert(jsonText["status"]);
            if(jsonText["status"]=='success'){
                $('#collect-report i').attr('clas','fa fa-bookmark-o');
                $('#collect-report-text').text('收藏此报告');
                $('#collect-report .sr-only').text("y");
                alert("已取消收藏！");
                $('#collection-iframe').attr('src', $('#collection-iframe').attr('src'));
                $('#report-num').text(parseInt($('#report-num').text()) - 1);
            }
            else{
                errorAlert(jsonText["message"]+jsonText["errorcode"]);
            }
        }
        else if(this.readyState==4 && this.status!=200){
            errorAlert(null);
        }
    });
}
function deleteStar(id){
    var url="/user/star";
    var postData = "_method=DELETE&id="+id;
    PostAjax(url,postData,function(){
        if (this.readyState==4 && this.status==200){
            var jsonText = eval("(" + this.responseText + ")");
            //alert(this.responseText);
            //alert(jsonText["status"]);
            if(jsonText["status"]=='success'){
                $('#star_'+id).hide("slow");
            }
            else{
                errorAlert(jsonText["message"]+jsonText["errorcode"]);
            }
        }
        else if(this.readyState==4 && this.status!=200){
            errorAlert(null);
        }
    });
}
