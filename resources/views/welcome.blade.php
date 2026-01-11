<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Trizent</title>

    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/company/trizent_icon.ico') }}">
</head>
<body>
    {{-- <div id="top_bar">
        <h1>CloudTik</h1>
    </div> --}}
    <div id="background">
        <img src="{{ asset('images/welcome/nw_back_image01.jpg') }}" alt="no image" id="background_image">
    </div>

    <div id="div_center">
        <h1 id="h1_title">TRIZENT Network Solution</h1>
        {{-- <button id="btn_user_login">User Login</button> --}}

        <div id="div_buttons">
            <div>
                <a href="/login" id="link_user" class="link_button">User Login</a>
            </div>
            <div>
                <a href="/cust_login" id="link_admin" class="link_button">Customer Login</a>
            </div>
        </div>

        <h4>Coming soon ...</h4>
    </div>

    <div id="footer">
        <p>© 2025 Trizent. All rights reserved.</p>
        <p>Powered by Trizent Software.</a></p>

    </div>
</body>
</html>
