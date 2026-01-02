<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('css/userregister.css') }}">
</head>
<body>
    <div class="container">
        <div class="md-12">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <div id="div_register_form">

        <form action="{{ route('wifilogin.store') }}" method="post">
            @csrf
            <input type="hidden" name="camp_id" value="{{ $camp->id }}">
            <h3>Register Here</h3>

            <div class="form-group mb-3">
                <label for="username">Username</label>
                <input type="text" name="customer_name" placeholder="Email or Phone" id="username" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="contact_no">Phone No</label>
                <input type="text" name="contact_no" placeholder="05########" id="contact_no" class="form-control" required>
                <span id="contact_error"></span>
            </div>

            <div class="form-group">
              <label for="customer_pwd">Password</label>
                <input type="password" name="customer_pwd" placeholder="Password" id="customer_pwd" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <input type="checkbox" id="chk_show_pwd" class="form-check-input">
                <label for="chk_show_pwd">Show Password</label>
            </div>

            <div class="form-group mb-3">
              <label for="password">Re-enter Password</label>
                <input type="password" name="customer_re_pwd" placeholder="Password" id="customer_re_pwd" class="form-control" required>
                <span id="pwd_error"></span>
            </div>

            <button type="submit" id="btn_register">Register</button>

            <div id="div_login">
                <button id="btn_back">Back</button>
                {{-- <a href="/userlogin" id="link_login">Login</a> --}}
            </div>
        </form>
    </div>

    <script src="{{ asset('js/wifi_register.js') }}"></script>
</body>
</html>
