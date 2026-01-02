$(document).ready(function () {
    $("#btn_open_register").click(function (e) {
        e.preventDefault();
        $("#register_modal").modal('toggle');
    });

    //password
    $("#tbl_users").on('click', '.btn_change_password', function(){
        let row = $(this).closest('tr');
        let id = row.data('id');

        $("#password_modal").modal('toggle');

        $("#hide_user_id").val(id);
    });

    //edit user
    $("#tbl_users").on('click', '.btn_open_edit', function(){
        let row = $(this).closest('tr');
        let id = row.data('id');

        $("#edit_modal").modal('toggle');
        $("#hide_edit_user_id").val(id);

        $.ajax({
            type: "get",
            url: "/getOneUser",
            data: {
                user_id: id
            },
            // dataType: "dataType",
            success: function (response) {
                // console.log(response);
                var name = response['name'];
                var email = response['email'];
                var role_id = response['role_id'];

                $("#edit_name").val(name);
                $("#edit_email").val(email);
                $("#cmb_edit_role").val(role_id);
            }
        });
    });

    $("#pwd_re_change").keyup(function () {
        var pwd = $("#pwd_change").val();
        var re_pwd = $("#pwd_re_change").val();

        if(pwd === re_pwd)
        {
            $("#pwd_re_change").css('border-color', 'green');
            $("#btn_pwd_change").attr('disabled', false);
        }
        else
        {
            $("#pwd_re_change").css('border-color', 'red');
            $("#btn_pwd_change").attr('disabled', true);
        }
    });
});//users jQuery
