$(document).ready(function () {
    //hide the error message initially
    $("#contact_error").css("display", "none");

    $("#contact_no").keyup(function (e) {
        var contact_no = $(this).val();
        var regex = /^0\d{9}$/;
        if (!regex.test(contact_no) && contact_no.length > 10) {
            // alert("Please enter a valid contact number in the format 05########.");

            $("#contact_no").css("border", "1px solid red");
            $("#contact_error").css("display", "block");
            $("#contact_error").css("color", "red");
            $("#contact_error").text('Invalid contact number format');
            $(this).val('');
        }
        else {
            $("#contact_no").css("border", "1px solid green");
            $("#contact_error").css("display", "none");
        }
    });

    $("#customer_re_pwd").keyup(function (e) {
        var pwd = $("#customer_pwd").val();
        var re_pwd = $(this).val();
        if (pwd !== re_pwd && re_pwd.length === pwd.length) {
            $("#customer_re_pwd").css("border", "1px solid red");
            $("#pwd_error").css("display", "block");
            $("#pwd_error").css("color", "red");
            $("#pwd_error").text('Passwords do not match');
            $(this).val('');
        } else {
            $("#customer_re_pwd").css("border", "1px solid green");
            $("#pwd_error").css("display", "none");
        }
    });

    //show password
    $("#chk_show_pwd").change(function(){
        if ($(this).is(":checked")) {
            $("#customer_pwd").attr("type", "text");
            $("#customer_re_pwd").attr("type", "text");
        } else {
            $("#customer_pwd").attr("type", "password");
            $("#customer_re_pwd").attr("type", "password");
        }
    });

    $("#btn_back").click(function(){
        // go back
        window.history.back();
    });

});//wifi_register.js
