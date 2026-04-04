const divCustomer = document.getElementById("div_customer_login");
const divVoucher = document.getElementById("div_voucher_login");
const divMessage = document.getElementById("message");

//hide voucher div
divVoucher.style.display = "none";

//buttons
const btnCustomer = document.getElementById('btn_customer');
const btnVoucher = document.getElementById('btn_voucher');
const btnQRCode = document.getElementById('btn_qr_generator');

const btnBack = document.getElementById("btn_back");

const qrCanvas = document.getElementById("qrcode");

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

btnQRCode.onclick = function(){
    const inputSection = document.getElementById("inputSection");
    const qrContainer = document.getElementById("div_qr_section");

    const btnLogin = document.getElementById("btn_customer_login");
    const btnQRCode = document.getElementById("btn_qr_generator");
    
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;

    if(username == "" || password == ""){
        
        divMessage.innerHTML = "<p style='color:red;'>Please enter username and password!</p>";
    }
    else{
        btnLogin.style.display = "none";
        btnQRCode.style.display = "none";

        inputSection.style.display = "none";
        qrContainer.style.display = "block";
        
        generateQR();
    } 

}//barcode generate

//back button
btnBack.onclick = function(){
    btnLogin.style.display = "block";
    btnQRCode.style.display = "block";

    inputSection.style.display = "block";
    qrContainer.style.display = "none";

    //clear qrcode
    qrCanvas.innerHTML = "";
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

function generateQR(){
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;
    const mac = document.getElementById("mac").value;
    const ip = document.getElementById("ip").value;
    const campID = document.getElementById("camp_id").value;

    const qrData = JSON.stringify({
        username: username,
        password: password,
        mac: mac,
        ip:ip,
        camp_id: campID,
    }); 

    //clear previous qr
    qrCanvas.innerHTML = "";

    new QRCode(qrCanvas, {
        text: qrData,
        width: 200,
        height: 200,
    });
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