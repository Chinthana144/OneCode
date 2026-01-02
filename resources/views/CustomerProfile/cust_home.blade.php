@extends('CustomerProfile.cust_layout')

@section('content')

    <div id="div_customer_details">
        <h5>
            Hi, {{ $customer->fullname }}
            <br>
            {{ $customer->username }}
        </h5>
    </div>

    {{-- has running package --}}
    @if ($running_package)
        <div id="div_running_package">
            <input type="hidden" id="running_end_date" value="{{ $customer->expiry_datetime }}">
            <h4>
                Expire Date:
                <br>
                <strong>{{ $customer->expiry_datetime }}</strong>
            </h4>

            <h4 class="mt-3">Remaining Time</h4>
            <p id="countdown"></p>
        </div>
    @endif

    {{-- has active packages --}}
    @if ($active_packages->count() > 0)
        <div id="div_active_packages">
            <h3>Active Packages</h3>
            @foreach ($active_packages as $package)
                <div class="div_package">
                    <h5>{{ $package->package->name }}</h5>
                    <p class="p_date">Date: <strong>{{ $package->purchaseDate }}</strong></p>
                    <p class="p_duration">Duration: <strong>{{ $package->package->duration }} Days</strong></p>
                    <p class="p_price">Price: <strong> {{ $package->price }} AED</strong></p>
                </div>
            @endforeach
        </div>
    @endif

    <script>
        $(document).ready(function () {
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
@endsection
