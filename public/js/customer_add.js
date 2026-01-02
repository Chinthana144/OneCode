$(document).ready(function () {
    $("#txt_add_username").change(function () {
        var username = $("#txt_add_username").val();
        var has_customer = 0;

        $.ajax({
            type: "get",
            url: "/getCustomerByUsername",
            data: {
                username: username,
            },
            // dataType: "dataType",
            success: function (response) {
                // console.log(response);

                if(response)
                    {
                        alert("customer already exists.");
                        $("#txt_add_username").val("");
                        $("#txt_add_username").css('border-color', 'red');
                        $("#txt_add_username").focus();
                    }
                    else
                    {
                        $("#txt_add_username").css('border-color', 'green');
                    }
            }
        });
    });
});//customer add jquery
