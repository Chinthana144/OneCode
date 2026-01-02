$(document).ready(function () {

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



});//mikrotik subscription jQuery
