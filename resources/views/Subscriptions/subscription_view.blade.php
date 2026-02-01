@extends('layouts.layout')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>
                @if (is_null($camp))
                    Subscriptions
                @else
                    Subscriptions in <b>{{ $camp->name }}</b>
                @endif
            </h5>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-md-6">
                    Subscriptions
                </div>
                <div class="col-md-6">
                    <form action="{{ route('subscription.search') }}" method="get">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="subscription_search" class="form-control" placeholder="Search..."
                                value="{{ isset($search) ? $search : '' }}">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-1" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mt-1" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table_responsive">
                <table class="table" id="tbl_subscription">
                    <tr>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Username</th>
                        <th>Package</th>
                        <th>Duration</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>User</th>
                        @can('update', App\Models\Subscription::class)
                            <th>Edit</th>
                        @endcan
                        @can('delete', App\Models\Subscription::class)
                            <th>Delete</th>
                        @endcan
                    </tr>

                    @foreach ($subscriptions as $subs)
                        <tr data-id="{{ $subs->id }}">
                            <td>{{ $subs->purchaseDate }}</td>
                            <td>{{ $subs->customer->fullname }}</td>
                            <td>{{ $subs->customer->username }}</td>
                            <td>{{ $subs->package->name }}</td>
                            <td>{{ $subs->package->duration }}</td>
                            <td>{{ $subs->price }}</td>
                            <td>
                                @if ($subs->status == 1)
                                    <span class="badge bg-primary">ACTIVE</span>
                                @elseif($subs->status == 2)
                                    <span class="badge bg-success">RUNNING</span>
                                @elseif($subs->status == 3)
                                    <span class="badge bg-warning">EXPIRED</span>
                                @else
                                    <span class="badge bg-danger">CANCLED</span>
                                @endif
                            </td>
                            <td>{{ $subs->user->name }}</td>

                            @can('update', App\Models\Subscription::class)
                            <td>
                                <button type="button" class="btn btn-warning btn-sm btn_open_edit"><i class="bx bx-edit"></i></button>
                            </td>
                            @endcan
                            @can('delete', App\Models\Subscription::class)
                                <td>
                                    <form action="{{ route('subscription.destroy') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="subscription_id" value="{{ $subs->id }}">
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="bx bx-trash"></i></button>
                                    </form>
                                </td>
                            @endcan
                        </tr>
                    @endforeach
                </table>
            </div>

            <div class="mt-3">
                {{ $subscriptions->links() }}
            </div>
        </div>
    </div>

    @include('Subscriptions.subs_edit_modal')

    <script src="{{ asset('js/subscription.js') }}"></script>
@endsection
