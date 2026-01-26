$(document).ready(function () {
    $("#tbl_access_plans").on('click', '.btn_reset_plan', function(){
        var id = $(this).data('id');
        alert('id = ' + id);
        // $("#plan_reset_modal").modal('toggle');
    });
});//access plans jQuery
