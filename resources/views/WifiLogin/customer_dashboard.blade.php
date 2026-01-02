<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Trizent Network</title>

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cust_dashboard.css') }}">
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>

</head>

<body>
    <div id="div_main">
        <h1>
            Hi, <strong>{{ $customer->fullname }}</strong>
            <br>
            {{ $customer->username }}
            <br>
            You have successfully logged in!
        </h1>

        @if ($customer)
            <img src="{{ asset('images/animations/checkmark.gif') }}" alt="" id="gif_correct">

            <input type="hidden" id="running_end_date" value="{{ $customer->expiry_datetime }}">

            <div id="div-countdown">
                Expire Date
                <p id="expire_date">{{ $customer->expiry_datetime }}</p>
                Remaining Time
                <p id="countdown"></p>
            </div>

            <div id="div_links">
                <div class="div_links">
                    <a href="https://youtube.com">
                        <img src="{{ asset('images/icons/youtube.png') }}" alt="youtube" id="img_youtube">
                    </a>
                </div>
                <div class="div_links">
                    <a href="https://google.com">
                        <img src="{{ asset('images/icons/google.png') }}" alt="youtube" id="img_youtube">
                    </a>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <script>
        $(document).ready(function() {
            var get_time = $("#running_end_date").val();
            var endTime = new Date(get_time).getTime();

            var countdownTimer = setInterval(() => {
                var now = new Date().getTime(); // Get current time
                var distance = endTime - now; // Calculate the difference

                if (distance < 0) {
                    clearInterval(countdownTimer);
                    $('#countdown').html("Package expired");
                    return;
                }

                // Time calculations
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Display the result
                $('#countdown').html(
                    days + "d " + hours + "h " + minutes + "m " + seconds + "s "
                );
            }, 1000);
        });
    </script>
</body>

</html>
