<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Trizent Infratech</title>
    <link rel="stylesheet" href="{{ asset('css/cust_login.css') }}">
</head>
<body>
    <div id="div_customer_login">
        <a href="/">
            <img src="{{ asset('images/company/com_logo_1.png') }}" alt="" id="img_logo">
        </a>


        <h3>Customer Login</h3>

        <form action="{{ route('customer.login') }}" method="post">
            @csrf
            <label for="username" class="labels">Username(phone)</label>
            <input type="text" name="username" id="username" placeholder="05XXXXXXXX" class="input_field" required>

            <label for="pwd" class="labels">Password</label>
            <input type="password" name="pwd" id="pwd" placeholder="Enter your password" class="input_field" required>
            <br>
            <input type="checkbox" name="show_pwd" id="show_pwd">
            <label for="show_pwd" id="lbl_show_pwd">Show Password</label>
            <br>

            <button type="submit" class="btn_submit">Login</button>
        </form>
    </div>

    <script>
        const pwd = document.getElementById('pwd');
        const show_pwd = document.getElementById('show_pwd');

        show_pwd.addEventListener('click', function(){
            if(pwd.type === "password"){
                pwd.type = "text";
            }else{
                pwd.type = "password";
            }
        });
    </script>
</body>
</html>
