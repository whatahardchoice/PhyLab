$(function() {
    $('#user-form').submit(function(event) {
        event.preventDefault(); // Prevent the form from submitting via the browser
        let form = $(this);
        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize()
        }).done(function(data) {
            // Optionally alert the user of success here...
            alert('update success')
        }).fail(function(data) {
            // Optionally alert the user of an error here...
        });
    });
});