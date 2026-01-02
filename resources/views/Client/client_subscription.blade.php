@extends('layouts.layout')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>Subscription in <b>{{ $camp->name }}</b></h5>
        </div>
        <div class="card-body">
            <table class="table">
                <tr>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Username</th>
                    <th>Package</th>
                    <th>Duration</th>
                    <th>Price</th>
                    <th>User</th>
                </tr>
                @foreach ($client_subs as $subs)
                    <tr>
                        <td>{{ $subs->purchaseDate }}</td>
                        <td>{{ $subs->customer->fullname }}</td>
                        <td>{{ $subs->customer->username }}</td>
                        <td>{{ $subs->package->name }}</td>
                        <td>{{ $subs->package->duration }}</td>
                        <td>{{ $subs->price }}</td>
                        <td>{{ $subs->user->name }}</td>
                    </tr>
                @endforeach
            </table>
            <div class="pagination">
                {{ $client_subs->links() }}
            </div>
        </div>
    </div>
@endsection
