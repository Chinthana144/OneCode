$(document).ready(function () {
    $("#btn_open_user_access").click(function(){
        $("#user_access_modal").modal('toggle');
    });

    $("#tbl_user_access").on('click', '.btn_edit_access', function(){
        let row = $(this).closest('tr');
        let user_access_id = row.data('id');

        var has_create = $("#create_access_"+user_access_id).is(':checked') ? 1 : 0;
        var has_view = $("#view_access_"+user_access_id).is(':checked') ? 1 : 0;
        var has_edit = $("#edit_access_"+user_access_id).is(':checked') ? 1 : 0;
        var has_delete = $("#delete_access_"+user_access_id).is(':checked') ? 1 : 0;

        $.ajax({
            type: "get",
            url: "/update-useraccess",
            data: {
                user_access_id: user_access_id,
                has_create: has_create,
                has_view: has_view,
                has_edit: has_edit,
                has_delete: has_delete
            },
            // dataType: "dataType",
            success: function (response) {
                // console.log(response);
                if(response.status){
                    alert('User access updated successfully');
                } else {
                    alert('Failed to update user access');
                }

            }
        });
    });
});//user access.js
