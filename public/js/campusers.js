$(document).ready(function () {
    $("#btn_open_campusers").click(function () {
        $("#campuser_modal").modal('toggle');
    });

    $("#tbl_campusers").on('click', '.btn_open_edit', function(){
        let row = $(this).closest('tr');
	    let id = row.data('id');

        // alert("idi = " + id);

        $.ajax({
            type: "get",
            url: "/getOneCampuser",
            data: {
                campuser_id : id
            },
            success: function (response) {
                // alert(response['data']);
                console.log(response['data']);

                var camp_id = response['data']['camp_id'];
                var user_id = response['data']['user_id'];

                // alert("camp = " + camp_id);
                $("#campuser_edit_modal").modal('toggle');
                $("#hide_campuser_id").val(id);

                $("#p_campuser").text("camp id = " + camp_id);

                $("#cmb_edit_camps").attr('selectedIndex', camp_id);
            }
        });
    });
});
