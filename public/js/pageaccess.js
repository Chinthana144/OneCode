$(document).ready(function () {

    $('#btn_create_access').click(function (e) {
        e.preventDefault();
        $("#page_create_modal").modal('toggle');
    });
    //close modal
    $("#close_page_create_modal").click(function () {
        $("#page_create_modal").modal('hide');
    });

    $("#tbl_role_pages").on('click', ".btn_change", function () {
        let row = $(this).closest('tr');
        let id = row.data('id');

        var has_access = $("#role_access_" + id).is(":checked") ? 1 : 0;

        $.ajax({
            type: "get",
            url: "/update-permission",
            data: {
                id: id,
                permission: has_access,
            },
            // dataType: "dataType",
            success: function (response) {
                console.log(response);

                if (response['success']) {
                    alert('Access saved successfully');
                }
                else {
                    alert('Access saved failed');
                }
            }
        });
    });
});
