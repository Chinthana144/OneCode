<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Trizent</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div id="div_background">

        <div id="div_login">
            <a href="/">
                <img src="{{ asset('images/company/com_logo_1.png') }}" alt="" id="com_logo">
            </a>

            <form action="{{ route('login') }}" method="post">
                @csrf
                <div class="div_input">
                    <label for="input_email" class="lbl_input">Email</label>
                    <input type="text" name="email" id="input_email" class="txt_input" placeholder="Email" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="div_input">
                    <label for="input_password" class="lbl_input">Password</label>
                    <input type="password" name="password" id="input_password" class="txt_input" placeholder="Password" required autocomplete="current-password">
                    <input type="checkbox" id="chk_password" name="chk_password" value="Bike">
                    <label for="chk_password">show password</label><br>
                </div>

                <div>
                    <button type="submit" id="btn_login">Login</button>
                </div>

                <a href="#">Forget Password?</a>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('chk_password').addEventListener('change', function() {
            var passwordInput = document.getElementById('input_password');
            if (this.checked) {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        });
    </script>
</body>
</html>
