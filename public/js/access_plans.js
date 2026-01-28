$(document).ready(function () {
    $("#tbl_access_plans").on('click', '.btn_reset_plan', function(){
        var id = $(this).data('id');
        // alert('id = ' + id);
        // $("#plan_reset_modal").modal('toggle');
        $.ajax({
            type: "get",
            url: "/getOneAccessPlan",
            data: {
                access_plan_id: id,
            },
            // dataType: "dataType",
            success: function (response) {
                console.log(response);
                $("#plan_reset_modal").modal('toggle');

                $("#camp_id").val(response['camp_id']);
                $("#access_plan_id").val(id);

                var status = "Active";
                switch (response['status']) {
                    case "1":
                        status = "<span class='badge bg-primary'>Active</span>";
                    break;
                    case "2":
                        status = "<span class='badge bg-success'>Running</span>";
                    break;
                    case "3":
                        status = "<span class='badge bg-warning'>Expired</span>";
                    break;
                    case "4":
                        status = "<span class='badge bg-danger'>Canceld</span>";
                    break;
                    case "5":
                        status = "<span class='badge bg-secondary'>Transferred</span>";
                    break;
                }

                var accessable = "";
                if(response['type'] == 'Subscription')
                {
                    accessable = response['customer_name'] + " - " + response['customer_username'];
                }
                else{
                    accessable = response['code'];
                }

                var htmlDetails = "";
                htmlDetails += "Type: <b>" + response['type'] + "</b><br>";
                htmlDetails += "Customer/Code: <b>" + accessable + "</b><br>";
                htmlDetails += "Package Name: <b>" + response['package_name'] + "</b><br>";
                htmlDetails += "Duration: <b>" + response['package_duration'] + "</b><br>";
                htmlDetails += "MAC: <b>" + response['mac_address'] + "</b><br>";
                htmlDetails += "Login: <b>" + response['login_at'] + "</b><br>";
                htmlDetails += "Expire: <b>" + response['expire_at'] + "</b><br>";
                htmlDetails += "MAC: <b>" + response['mac_address'] + "</b><br>";
                htmlDetails += "Status: <b>" + status + "</b><br>";

                $("#p_details").html(htmlDetails);
            }
        });
    });

    //camp change
    $("#tbl_access_plans").on('click', '.btn_camp_change', function(){
        var id = $(this).data('id');
        $.ajax({
            type: "get",
            url: "/getOneAccessPlan",
            data: {
                access_plan_id: id,
            },
            // dataType: "dataType",
            success: function (response) {
                // console.log(response);
                $("#camp_change_modal").modal('toggle');

                $("#ch_access_plan_id").val(id);

                var status = "Active";
                switch (response['status']) {
                    case "1":
                        status = "<span class='badge bg-primary'>Active</span>";
                    break;
                    case "2":
                        status = "<span class='badge bg-success'>Running</span>";
                    break;
                    case "3":
                        status = "<span class='badge bg-warning'>Expired</span>";
                    break;
                    case "4":
                        status = "<span class='badge bg-danger'>Canceld</span>";
                    break;
                    case "5":
                        status = "<span class='badge bg-secondary'>Transferred</span>";
                    break;
                }

                var accessable = "";
                if(response['type'] == 'Subscription')
                {
                    accessable = response['customer_name'] + " - " + response['customer_username'];
                }
                else{
                    accessable = response['code'];
                }

                var htmlDetails = "";
                htmlDetails += "Type: <b>" + response['type'] + "</b><br>";
                htmlDetails += "Customer/Code: <b>" + accessable + "</b><br>";
                htmlDetails += "Package Name: <b>" + response['package_name'] + "</b><br>";
                htmlDetails += "Duration: <b>" + response['package_duration'] + "</b><br>";
                htmlDetails += "MAC: <b>" + response['mac_address'] + "</b><br>";
                htmlDetails += "Login: <b>" + response['login_at'] + "</b><br>";
                htmlDetails += "Expire: <b>" + response['expire_at'] + "</b><br>";
                htmlDetails += "MAC: <b>" + response['mac_address'] + "</b><br>";
                htmlDetails += "Status: <b>" + status + "</b><br>";

                $("#p_transfer_details").html(htmlDetails);
            }
        });
    });

    //expire date change
    $("#tbl_access_plans").on('click', '.btn_expire_change', function(){
        var id = $(this).data('id');
        $.ajax({
            type: "get",
            url: "/getOneAccessPlan",
            data: {
                access_plan_id: id,
            },
            // dataType: "dataType",
            success: function (response) {
                // console.log(response);
                $("#expire_date_modal").modal('toggle');

                $("#exp_access_plan_id").val(id);

                var status = "Active";
                switch (response['status']) {
                    case "1":
                        status = "<span class='badge bg-primary'>Active</span>";
                    break;
                    case "2":
                        status = "<span class='badge bg-success'>Running</span>";
                    break;
                    case "3":
                        status = "<span class='badge bg-warning'>Expired</span>";
                    break;
                    case "4":
                        status = "<span class='badge bg-danger'>Canceld</span>";
                    break;
                    case "5":
                        status = "<span class='badge bg-secondary'>Transferred</span>";
                    break;
                }

                var accessable = "";
                if(response['type'] == 'Subscription')
                {
                    accessable = response['customer_name'] + " - " + response['customer_username'];
                }
                else{
                    accessable = response['code'];
                }

                var htmlDetails = "";
                htmlDetails += "Type: <b>" + response['type'] + "</b><br>";
                htmlDetails += "Customer/Code: <b>" + accessable + "</b><br>";
                htmlDetails += "Package Name: <b>" + response['package_name'] + "</b><br>";
                htmlDetails += "Duration: <b>" + response['package_duration'] + "</b><br>";
                htmlDetails += "MAC: <b>" + response['mac_address'] + "</b><br>";
                htmlDetails += "Login: <b>" + response['login_at'] + "</b><br>";
                htmlDetails += "Expire: <b>" + response['expire_at'] + "</b><br>";
                htmlDetails += "MAC: <b>" + response['mac_address'] + "</b><br>";
                htmlDetails += "Status: <b>" + status + "</b><br>";

                $("#p_expire_details").html(htmlDetails);
            }
        });
    });

    function updateCountdown() {
        $('.expiry').each(function () {

            const expireTime = new Date($(this).data('expire')).getTime();
            const now = new Date().getTime();
            let distance = expireTime - now;

            if (distance <= 0) {
                $(this).text("Expired");
                return;
            }

            if(isNaN(distance))
            {
                $(this).text("N/A");
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            $(this).text(`${days}d ${hours}h ${minutes}m ${seconds}s`);
            $(this).css('color', 'green');
        });
    }//countdown

    // Update countdown every second
    setInterval(updateCountdown, 1000);
});//access plans jQuery
