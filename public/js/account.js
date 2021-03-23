$(document).ready(function () {
    // $("#transaction-amount").inputmask('99-9999999');
    const authToken = localStorage.getItem('auth_token');

    if (!authToken) {
        alert('Usuário não autenticado');
        window.location = '/user/';
    }

    $("#target-account").hide();

    $.ajax({
        url: '/api/account/accountinfo',
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

            $("#name").text(response.name);
            $("#account-number").html(response.account.code);
            $("#account-balance").html(response.account.balance);
        },
    });

    $.ajax({
        url: '/api/transaction/transactionlist',
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
            $.each(response.transactions, function (index, item) {
                $('#table-values').append(
                    `<tr><td>${item.date_time}</td><td>${item.description}</td><td>${item.value}</td><td>${item.code}</td></tr>`
                );
            });
        },
    });


    $('#transaction-type').on('change', function (event){
       if(event.target.value === "Transferencia"){
           $("#target-account").show();
       } else {
           $("#target-account").hide();
       }
    });

    $("#btn-action-transaction").on('click', function(event) {
        $.ajax({
            url: `/api/transaction/${$('#transaction-type').val()}`,
            type: 'POST',
            dataType: 'json',
            data: {
                amount: $("#transaction-amount").val(),
                targetAcountId: $("#target-account").val(),
                transactionId: $("#transaction-type").val(),
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
                    window.location = '/account/';
                } else {
                    window.location = '/account/';
                }
            },
        });
    });

    $("#submit-action-signout").on('click', function (event) {
        localStorage.removeItem('auth_token');
        window.location = '/user/';
    });
});