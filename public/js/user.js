$(document).ready(function() {
    $("#submit-action-login").on('click', function(event) {
        console.log($("#uemail").val());
        event.preventDefault();
        $.ajax({
           url: '/api/user/login',
           method: 'POST',
           data: {
               email: $("#uemail").val(),
               password: $("#psw").val(),
           }
        }).done(function (response) {
            if (response.error) {
                alert(response.message);
            } else {
                window.location = '/account/';
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.log(JSON.stringify(jqXHR));
            console.log(textStatus);
            console.log(JSON.stringify(errorThrown));
        });
    });
});