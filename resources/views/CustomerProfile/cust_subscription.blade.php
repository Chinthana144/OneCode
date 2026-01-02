@extends('CustomerProfile.cust_layout')

@section('content')

    <div id="div_customer_details">
        <h5>
            Hi, {{ $customer->fullname }}
            <br>
            {{ $customer->username }}
        </h5>
    </div>

    @foreach ($subscriptions as $sub)
        <div class="div_sub">
            <div>
                <p>
                    Purchase Date
                    <br>
                    <strong>{{ $sub->purchaseDate }}</strong>
                </p>
            </div>

            <div>
                <p>
                    Login: <strong>{{ $sub->subscriptionStartTime ?? 'N/A' }}</strong>
                    <br>
                    Expire: <strong>{{ $sub->subscriptionEndTime ?? 'N/A' }}</strong>
                </p>
            </div>

            <div>
                <p>
                    Duration: <strong>{{ $sub->package->duration }}</strong> Day(s)
                    <br>
                    Price: <strong>{{ $sub->price }}</strong> AED
                </p>
            </div>

            <div>
                Status
                <br>
                @if ($sub->status == 1)
                    <span class="badge bg-primary">ACTIVE</span>
                @elseif($sub->status == 2)
                    <span class="badge bg-success">RUNNING</span>
                @elseif($sub->status == 3)
                    <span class="badge bg-warning">EXPIRED</span>
                @else
                    <span class="badge bg-danger">CANCLED</span>
                @endif
            </div>
        </div>
    @endforeach

@endsection
