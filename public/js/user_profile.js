$(document).ready(function () {
    $("#confirm_password").change(function (e) {
        var new_pwd = $("#new_password").val();
        var con_pwd = $(this).val();

        if(new_pwd == con_pwd){
            $(this).css('border-color', 'green');
        }
        else{
            $(this).css('border-color', 'red');
            $(this).val('');
        }
    });
});
