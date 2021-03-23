$(document).ready(function() {
    if (localStorage.getItem('auth_token')) {
        window.location = '/account';
    }

    $("#submit-action-login").on('click', function(event) {
        const username = $("#uemail").val();
        const password = $("#psw").val();
        const authToken = "Basic " + btoa(username + ":" + password);

        event.preventDefault();

        $.ajax({
            url: '/api/user/login',
            type: 'POST',
            dataType: 'json',
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Authorization", authToken);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown);
            },
            success: function(response) {
                if (response.error) {
                    alert(response.message);
                } else {
                    localStorage.setItem('auth_token', authToken);
                    window.location = '/account/';
                }
            },
        });
    });
});