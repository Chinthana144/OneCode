$(document).ready(function () {
    //initialize
    $("#btn_customer_history").css('display', 'none');
    $("#btn_add_subscription").attr('disabled', 'true');

    $("#cmb_customer").select2({
        placeholder: 'Search Customers',
        ajax:{
            // type: "method",
            url: "/getCustomers",
            dataType: "json",
            delay:250,
            data: function(params){
                return{
                    q: params.term
                };
            },
            processResults: function(data){
                return {
                    results: data.map(customer => ({
                        id: customer.id,
                        text: customer.fullname + ' - ' + customer.username + ' - ' + customer.phone
                    }))
                };
            },
        },
        cache: true,
        minimumInputLength: 1,
    });

    $("#cmb_customer").change(function () {
        var customer_id = $(this).val();
        var camp_id = $("#hide_camp_id").val();

        $.ajax({
            type: "get",
            url: "/getOneCustomer",
            data: {
                id: customer_id
            },
            // dataType: "dataType",
            success: function (response) {
                // alert(response['name']);
                // console.log(response);
                var customer_fullname = response['fullname'];
                var customer_username = response['username'];
                var customer_phone = response['phone'];

                var cust_data = "Fullname: <b>" + customer_fullname + "</b><br>" + "Username: <b>" + customer_username +"</b><br> Phone: <b>" + customer_phone + "</b>";

                $("#p_customer_details").html(cust_data);

                $("#btn_customer_history").css('display', 'block');
            }

        });

        var package_data = "<option value='0'>Select Package</option>";
        $.ajax({
            type: "get",
            url: "/getCustomerPackages",
            data: {
                customer_id: customer_id,
                camp_id: camp_id,
            },
            success: function (response) {
                $.each(response, function (key, value) {
                    package_data += "<option value='"+value['id']+"'>Name: "+value['name']+" | "+value['duration']+"(days) | Price: "+value['price']+" AED</option>";
                });

                $("#cmb_subscription_packages").empty();
                $("#cmb_subscription_packages").append(package_data);
            }
        });
    });

    $("#cmb_subscription_packages").change(function () {
        //check values
        var package_id = $("#cmb_subscription_packages").val();

        if(package_id > 0)
        {
            var customer_id = $("#cmb_customer").val();
            var package_id = $("#cmb_subscription_packages").val();

            $("#hide_customer_id").val(customer_id);
            $("#hide_package_id").val(package_id);
            $("#btn_add_subscription").attr('disabled', false);

            //get one package
            $.ajax({
                type: "get",
                url: "/getOnePackage",
                data: {
                    package_id: package_id,
                },
                // dataType: "dataType",
                success: function (response) {
                    // console.log(response);
                    // alert("price = " + price);

                    let package_data = "Package: <b>"+response['name']+"</b><br>Duration: <b>"+response['duration']+"</b><br>Price: <b>"+response['price']+" AED</b>"

                    $("#p_package_details").html(package_data);
                }
            });
        }
        else
        {
            $("#btn_add_subscription").attr('disabled', true);
        }
    });

    $("#frm_subscription").submit(function (e) {
        e.preventDefault();
        var customer_id = $("#hide_customer_id").val();
        var package_id = $("#hide_package_id").val();

        $.ajax({
            type: "post",
            url: "/store-subscription",
            data: $(this).serialize(),
            // dataType: "dataType",
            success: function (response) {
                console.log(response);
                var has_success = response['success'];
                // var message = response['message'];

                // alert('data - ' + has_success);

                if(has_success)
                {
                    var subscription_id = response['subscription_id'];

                    alert("Subscription added successfully. continue to QR code");

                    window.location.reload();

                    //print receipt
                    // let printWindow = window.open('/receipt-print?subscription_id='+subscription_id, '_blank');

                    // setTimeout(function(){
                    //     window.location.href = '/invoice';
                    // }, 500);
                }
                else
                {
                    alert('task failed...');
                }
            }
        });
    });

//=============================== Customer ===========================//
$("#btn_customers").click(function(){
    $("#customer_modal").modal('toggle');

    //populate customer types
    $.ajax({
        type: "get",
        url: "/getCustomerTypes",
        // data: "data",
        // dataType: "dataType",
        success: function (response) {
            // console.log(response);
            $("#cmb_customer_type").empty();
            $.each(response, function (key, value) {
                $("#cmb_customer_type").append("<option value="+value['id']+">"+value['customerType']+"</option>");
            });
        }
    });
});

$("#frm_customer").submit(function(){
    // e.preventDefault();
    $.ajax({
        type: "post",
        url: "/storeOneCustomer",
        data: $(this).serialize(),
        // dataType: "dataType",
        success: function (response) {
            // console.log(response);
            $("#customer_modal").modal('hide');
        }
    });
});

$("#btn_customer_history").click(function(){
    $("#history_modal").modal('toggle');

    var customer_id = $("#cmb_customer").val();

    //get customer details
    $.ajax({
        type: "get",
        url: "/getOneCustomer",
        data: {
            id: customer_id,
        },
        // dataType: "dataType",
        success: function (response) {
            // console.log(response);
            var customer_name = response['fullname'];
            var customer_phone = response['phone'];
            var customer_username = response['username'];

            $("#h5_customer_detail").text(customer_name+" | " + customer_phone + " | " + customer_username);
        }
    });

    $.ajax({
        type: "get",
        url: "/getCustomerSubscriptions",
        data: {
            customer_id:customer_id,
        },
        // dataType: "dataType",
        success: function (response) {
            // console.log(response);
            var status = "";
            var sub_data = "";
            sub_data += "<tr>";
            sub_data += "<th>Package</th>";
            sub_data += "<th>Duration</th>";
            sub_data += "<th>Start Time</th>";
            sub_data += "<th>End Time</th>";
            sub_data += "<th>Price</th>";
            sub_data += "<th>Status</th>";
            sub_data += "</tr>";
            $.each(response, function (key, value) {
                sub_data += "<tr>";
                sub_data += "<td>"+value['name']+"</td>";
                sub_data += "<td>"+value['duration']+"</td>";
                sub_data += "<td>"+value['subscriptionStartTime']+"</td>";
                sub_data += "<td>"+value['subscriptionEndTime']+"</td>";
                sub_data += "<td>"+value['price']+"</td>";
                switch (value['status']) {
                    case 1:
                        status = "<p class='text-success border border-success rounded text-center' style='padding: 0px; margin:0px;'>Active</p>";
                    break;
                    case 2:
                        status = "<p class='text-warning border border-warning rounded text-center' style='padding: 0px; margin:0px;'>Pending</p>";
                    break;
                    case 3:
                        status = "<p class='text-primary border border-primary rounded text-center' style='padding: 0px; margin:0px;'>Cancled</p>";
                    break;
                    default:
                        status = "<p class='text-danger border border-danger rounded text-center' style='padding: 0px; margin:0px;'>Expired</p>";
                    break;
                }
                sub_data += "<td>"+status+"</td>";
                sub_data += "</tr>";
            });

            $("#tbl_customer_subscriptions").html(sub_data);
        }
    });
});//history

//validate username
$("#txt_add_username").change(function(){
    var username = $(this).val();

    $.ajax({
        type: "get",
        url: "/getCustomerByUsername",
        data: {
            username: username,
        },
        // dataType: "dataType",
        success: function (response) {
            if(response)
            {
                alert("customer already exists.");
                $("#txt_add_username").val("");
                $("#txt_add_username").css('border-color', 'red');
                $("#txt_add_username").focus();
            }
            else
            {
                $("#txt_add_username").css('border-color', 'green');
            }
        }
    });
});

//================================= Functions ==================================//

});//invoice jQuery
