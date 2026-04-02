const divCustomer = document.getElementById("div_customer_login");
const divVoucher = document.getElementById("div_voucher_login");

//hide voucher div
divVoucher.style.display = "none";

//buttons
const btnCustomer = document.getElementById('btn_customer');
const btnVoucher = document.getElementById('btn_voucher');

//add classes
btnCustomer.classList.add('selected_color');

//button on click
btnCustomer.onclick = function(){
    // console.log("customer form select");
    //change color
    btnCustomer.classList.remove('deselected_color');
    btnCustomer.classList.add('selected_color');

    btnVoucher.classList.remove('selected_color');
    btnVoucher.classList.add('deselected_color');

    divVoucher.style.display = "none";
    divCustomer.style.display = "block";
}

btnVoucher.onclick = function(){
    // console.log("voucher form select");
    //change color
    btnVoucher.classList.remove('deselected_color');
    btnVoucher.classList.add('selected_color');

    btnCustomer.classList.remove('selected_color');
    btnCustomer.classList.add('deselected_color');

    divVoucher.style.display = "block";
    divCustomer.style.display = "none";
}

// document.getElementById('frm_login').addEventListener('submit', function(e){
//     e.preventDefault();

//     var camp_id = document.getElementById('camp_id').value;
//     var mac = document.getElementById('mac').value;
//     var username = document.getElementById('username').value;
//     var password = document.getElementById('password').value;

//     document.getElementById("message").innerHTML = "Checking credentials...";

//     fetch('https://cloudtik.trizent.net/api/wifi_login', {
//         method: 'POST',
//         headers: {
//             "Content-Type": "application/json"
//         },
//         body: JSON.stringify({
//             camp_id: camp_id,
//             mac: mac,
//             username: username,
//             password: password
//         })
//     })
//     .then(response => response.json())
//     .then(data => {
//         // console.log(data);
//         if(data.status === 'success'){
//             document.getElementById("message").innerHTML = 
//                 "login successful! <br>" + 
//                 "Login Time: " + data.login_datetime + "<br>" +
//                 "Session Expiry: " + data.expire_datetime + "<br>";

//             // Small delay so user can see message
//             setTimeout(function() {

//                 document.querySelector('#mikrotikLogin input[name="username"]').value = username;
//                 document.querySelector('#mikrotikLogin input[name="password"]').value = password;

//                 document.getElementById('mikrotikLogin').submit();

//             }, 2000);
//         }//login success
//         else {

//             document.getElementById('message').style.color = "red";
//             document.getElementById('message').innerHTML = data.message;
//         }
//     })
//     .catch(error => {
//         document.getElementById('message').style.color = "red";
//         document.getElementById('message').innerHTML = "Server error. Please try again.";
//     });
// });//form submit