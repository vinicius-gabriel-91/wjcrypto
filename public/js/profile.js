$(document).ready(function() {
    const authToken = localStorage.getItem('auth_token');

    if (!authToken) {
        alert('Usuário não autenticado');
        window.location = '/user/';
    }

    $.ajax({
        url: '/api/user/info',
        type: 'GET',
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
                localStorage.removeItem('auth_token');
                window.location = '/user/';
            }

            $("#user-name").val(response.user.name);
            $("#user-surname").val(response.user.surname);
            $("#user-email").val(response.user.email);
            $("#user-docnumber").val(response.user.docNumber);
            $("#user-taxvat").val(response.user.taxvat);
            $("#address-street").val(response.address.street);
            $("#address-street-number").val(response.address.streetNumber);
            $("#address-street-number-adition").val(response.address.streetNumberAdition);
            $("#address-city").val(response.address.city);
            $("#address-postalcode").val(response.address.postalCode);
            $("#address-country").val(response.address.country);
            $("#address-telephone").val(response.address.phoneNumber);
        },
    });

    // $.ajax({
    //    url: '/api/user/info',
    //    method: 'GET',
    // }).done(function (response) {
    //     if (response.error) {
    //         alert(response.message);
    //         window.location = "/user";
    //     }
    //     $("#user-name").val(response.user.name);
    //     $("#user-surname").val(response.user.surname);
    //     $("#user-email").val(response.user.email);
    //     $("#user-docnumber").val(response.user.docNumber);
    //     $("#user-taxvat").val(response.user.taxvat);
    //     $("#address-street").val(response.address.street);
    //     $("#address-street-number").val(response.address.streetNumber);
    //     $("#address-street-number-adition").val(response.address.streetNumberAdition);
    //     $("#address-city").val(response.address.city);
    //     $("#address-postalcode").val(response.address.postalCode);
    //     $("#address-country").val(response.address.country);
    //     $("#address-telephone").val(response.address.phoneNumber);
    //
    // }).fail(function(jqXHR, textStatus, errorThrown) {
    //     console.log(JSON.stringify(jqXHR));
    //     console.log(textStatus);
    //     console.log(JSON.stringify(errorThrown));
    // });

    $("#btn-action-update").on('click', function (event) {
        $.ajax({
            url: '/api/account/updateaddress',
            method: 'POST',
            dataType: 'json',
            data: {
                street: $("#address-street").val(),
                streetNumber: $("#address-street-number").val(),
                streetNumberAdition: $("#address-street-number-adition").val(),
                city: $("#address-city").val(),
                postalCode: $("#address-postalcode").val(),
                country: $("#address-country").val(),
                phoneNumber: $("#address-telephone").val(),
            },
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Authorization", authToken);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown);
            },
            success: function (response) {
                if (response.error) {
                    alert(response.message);
                    window.location.reload();
                } else {
                    alert('Endereço atualizado com sucesso')
                    window.location = '/account/'
                }
            },
        });
    });
});