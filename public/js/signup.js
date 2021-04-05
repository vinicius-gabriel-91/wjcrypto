$(document).ready(function() {
    $("#bnt-action-newuser").on('click', function(event) {
        event.preventDefault();
        $.ajax({
           url: '/api/user/signup',
           method: 'POST',
           data: {
                name: $("#fname").val(),
                surname: $("#lname").val(),
                email: $("#email").val(),
                password: $("#password").val(),
                isBusiness: $("#corporate").val(),
                taxvat: $("#taxvat").val(),
                docNumber: $("#personDocument").val(),
                corporateName: $("#corporateName").val(),
                street: $("#street").val(),
                streetNumber:$("#number").val(),
                streetNumberAdition:$("#numberAdition").val(),
                postalCode:$("#postalCode").val(),
                city:$("#city").val(),
                country:$("#country").val(),
                phoneNumber:$("#phoneNumber").val(),

           }
        }).done(function (response) {
            if (response.error) {
                console.log(response)
                alert(response.message);
            } else {
                alert("Usuario criado com sucesso")
                window.location = '/user/';
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.log(JSON.stringify(jqXHR));
            console.log(textStatus);
            console.log(JSON.stringify(errorThrown));
        });
    });
});