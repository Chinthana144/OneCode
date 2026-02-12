$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#btn_select_query").click(function (e) { 
        e.preventDefault();
        var query = $("#txt_select_query").val();

        $.ajax({
            type: "post",
            url: "/execue_query",
            data: {
                query: query,
            },
            // dataType: "dataType",
            success: function (response) {
                console.log(response);
                var type = response['type'];

                $("#p_data").text(type);

                if(type == 'select')
                {
                    $("#txt_result").val(JSON.parse(response['data']));
                }
            }
        });
    });
});