<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Trizent Infratech</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cust_profile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cust_navbar.css') }}">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
</head>
<body>
    <div id="div_nav_bar">
        <div class="div_nav_item">
            <a href="/cust_home" class="nav_link">
                <i class="bx bx-home fs-3"></i>
                <br>
                Home
            </a>
        </div>

        <div class="div_nav_item">
            <a href="/cust_subscription" class="nav_link">
                <i class="bx bx-layer fs-3"></i>
                <br>
                Subscriptions
            </a>
        </div>

        <div class="div_nav_item">
            <a href="/cust_profile" class="nav_link">
                <i class="bx bx-user fs-3"></i>
                <br>
                Profile
            </a>
        </div>

        <div class="div_nav_item">
            <a href="/cust_logout" class="nav_link">
                <i class="bx bx-power-off fs-3"></i>
                <br>
                Logout
            </a>
        </div>
    </div>
    <div id="div_title">
        <h5>TRIZENT Customer Portal</h5>
    </div>


    @yield('content')

    <div id="div_footer">
        <p>
            &copy; 2025 Trizent Infratech. All rights reserved.
            Powered by Trizent Infratech
        </p>
    </div>
</body>
</html>
