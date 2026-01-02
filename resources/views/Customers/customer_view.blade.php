@extends('layouts.layout')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>
                @if (is_null($camp))
                    Customers
                @else
                    Customers in <b>{{ $camp->name }}</b>
                @endif
            </h5>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-md-4">
                    @can('create', App\Models\Customer::class)
                        <button type="button" id="btn_open_addcustomer" class="btn btn-primary">Add Customer</button>
                    @endcan
                </div>
                <div class="col-md-4">

                </div>
                <div class="col-md-4">
                    <form action="{{ route('customer.search') }}" method="get">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="customer_search" class="form-control" placeholder="Search..."
                                value="{{ isset($search) ? $search : '' }}">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table_responsive">
                <table class="table" id="tbl_customers">
                    <tr>
                        <th>Type</th>
                        <th>Full Name</th>
                        <th>Phone</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Expire Date</th>
                        @can('update', App\Models\Customer::class)
                            <th>Edit</th>
                        @endcan
                        @can('delete', App\Models\Customer::class)
                            <th>Delete</th>
                        @endcan

                    </tr>
                    @foreach ($customers as $customer)
                        <tr data-id = {{ $customer->id }}>
                            <td>{{ $customer->customerType->customerType }}</td>
                            <td>{{ $customer->fullname }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td>
                                {{ $customer->username }}
                                <br>
                                {{ $customer->mac_address }}
                            </td>
                            <td>{{ $customer->password }}</td>
                            <td>
                                {{ $customer->expiry_datetime != null ? $customer->expiry_datetime : 'N/A' }}
                            </td>
                            @can('update', App\Models\Customer::class)
                            <td>
                                <button type="button" class="btn btn-outline-warning btn-sm btn_open_edit"><i class="bx bx-edit"></i></button>
                            </td>
                            @endcan
                            @can('delete', App\Models\Customer::class)
                            <td>
                                {{-- add delete form here --}}
                                <form action="{{ route('customer.deactivate') }}" method="post">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                    <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bx bx-trash"></i></button>
                                </form>
                            </td>
                            @endcan
                        </tr>
                    @endforeach
                </table>
            </div>

            <div>
                {{ $customers->links() }}
            </div>
        </div>
    </div>

    @include('Customers.customer_add_modal')
    @include('Customers.customer_edit_modal')

    <script src="{{ asset('js/customers.js') }}"></script>
@endsection
