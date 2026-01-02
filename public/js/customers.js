$(document).ready(function () {
    $("#phone_error").css('display', 'none');

    $("#btn_open_addcustomer").click(function () {
        $("#customer_add_modal").modal('toggle');
    });

    //hide add modal
    $("#btn_close_add_modal").click(function (){
        $("#customer_add_modal").modal('hide');
    });

    $("#txt_phone").on('change', function(){
        // alert("Phone number validation triggered.");
        var phone = $("#txt_phone").val();
        const phoneRegex = /^0\d{9}$/;

        if (!phoneRegex.test(phone)) {
            $("#phone_error").css('display', 'block');
            $("#phone_error").html("<small>Phone number must start with 0 and be exactly 10 digits.</small>");
            $("#phone_error").css('color', 'red');
            $(this).val('');
        } else {
            $("#phone_error").css('display', 'none');
            $("#phone_error").text("");
            $("#phone_error").css('color', 'green');
        }
    });

    $("#tbl_customers").on('click', '.btn_open_edit', function(){
        let row = $(this).closest('tr');
        let id = row.data('id');
        // alert("Edit button clicked for customer ID: " + id);

        $.ajax({
            type: "get",
            url: "/getOneCustomer",
            data: {
                id : id
            },
            success: function (response) {
                // console.log(response);

                var camp_id = response['camp_id'];
                var customerType_id = response['customerType_id'];
                var fullname = response['fullname'];
                var phone = response['phone'];
                // var email = response['email'];
                var username = response['username'];
                var password = response['password'];
                var customer_id = response['id'];

                $("#customer_edit_modal").modal('toggle');
                $("#hide_customer_id").val(id);

                $("#hide_customer_id").val(customer_id);
                $("#cmb_edit_camp").val(camp_id);
                $("#cmb_edit_customer_type").val(customerType_id);
                $("#edit_fullname").val(fullname);
                $("#edit_phone").val(phone);
                // $("#txt_edit_email").val(email);
                $("#edit_username").val(username);
                $("#edit_password").val(password);
                $("#chk_edit_customer_stat").prop('checked', response['status'] === 1);
            }
        });
    });

    $("#btn_close_edit_modal").click(function (){
        $("#customer_edit_modal").modal('hide');
    });
});//customers jquery
