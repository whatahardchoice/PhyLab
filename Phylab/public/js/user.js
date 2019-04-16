$(function() {
    $('#user-form').submit(function(event) {

        event.preventDefault(); // Prevent the form from submitting via the browser
        let form = $(this);
        //var form = new FormData(document.getElementById("user-form"));
        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize(),
            //data: form,
            //cache: false,
            //contentType: false,
            //processData: false,
        }).done(function(data) {
            // Optionally alert the user of success here...
            alert('更新成功');
        }).fail(function(data) {
            alert("更新失败");
        });
    });
/*
    $('#load-form').submit(function(event) {

        var form = new FormData($( "#load-form" )[0]);
        $.ajax({
            url: form.attr('action') ,
            type: 'post',
            data: form,
            async: false,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                alert('更新成功');
            },
            error: function (data) {
                alert("更新失败");
            }
        });
    });
*/
});
/*
function load_file() {
    var form = new FormData($( "#load_file" )[0]);
    $.ajax({
        url: form.attr('action') ,
        type: 'post',
        data: form,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            alert('更新成功');
        },
        error: function (data) {
            alert("更新失败");
        }
    });
}*/