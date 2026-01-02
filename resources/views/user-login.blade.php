<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('css/userlogin.css') }}">
</head>
<body>
    <div class="container">
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

    <div id="div_login_form">

        <form action="" method="post">
            @csrf
            <h3>Login Here</h3>

            <div class="form-group mb-3">
                <label for="contact_no">Phone No</label>
                <input type="text" name="contact_no" placeholder="Phone" id="contact_no" class="form-control" required>
            </div>

            <div class="form-group mb-3">
              <label for="customer_pwd">Password</label>
                <input type="password" name="customer_pwd" placeholder="Password" id="customer_pwd" class="form-control" required>
            </div>

            <button type="submit" id="btn_login">Login</button>

            <div id="div_register">
                <a href="/userregister" id="link_register">Register</a>
            </div>
        </form>
    </div>
</body>
</html>
