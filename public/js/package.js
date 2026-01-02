$(document).ready(function () {

$("#btn_add_package").click(function(){
    $("#packageAddModal").modal('toggle');
});

$("#btn_close_package_add").click(function(){
    $("#packageAddModal").modal('hide');
});

$("#tbl_packages").on('click', '.btn_edit_package', function(){
    let row = $(this).closest('tr');
    let id = row.data('id');

    $.ajax({
        type: "get",
        url: "/getOnePackage",
        data: {
            'package_id' : id
        },
        // dataType: "dataType",
        success: function (response) {
            // console.log(response);

            var package_id = response['id'];

            $("#packageEditModal").modal('toggle');

            $("#hide_package_id").val(package_id);
            $("#cmb_edit_customer_type").val(response['customerType_id']);
            $("#edit_package_name").val(response['name']);
            $("#edit_package_price").val(response['price']);
            $("#edit_package_duration").val(response['duration']);
            $("#edit_package_bandwidth").val(response['bandwidth']);
            $("#edit_package_downloadlimit").val(response['downloadlimit']);
            $("#edit_package_uploadlimit").val(response['uploadlimit']);

            if (response['status'] == 1) {
                $("#edit_package_status").prop('checked', true);
            } else {
                $("#edit_package_status").prop('checked', false);
            }

        }
    });
});

});//package jquery
