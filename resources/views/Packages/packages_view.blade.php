@extends('layouts.layout')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>
                @if (is_null($camp))
                    Packages
                @else
                    Packages in <b>{{ $camp->name }}</b>
                @endif
            </h5>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-md-6">
                    @can('create',  App\Models\Package::class)
                        {{-- <a href="/add-packages" class="btn btn-primary">Add Package</a> --}}
                        <button type="button" id="btn_add_package" class="btn btn-primary btn-sm">Add Package</button>
                    @endcan
                </div>
                <div class="col-md-6">
                    <form action="{{ route('package.search') }}" method="get">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="package_search" class="form-control" placeholder="Search..."
                                value="{{ isset($search) ? $search : '' }}">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table_responsive">
                <table class="table" id="tbl_packages">
                    <tr>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Duration<br>(Days)</th>
                        <th>Price<br>(AED)</th>
                        <th>Status</th>
                        @can('update',  App\Models\Package::class)
                            <th>Edit</th>
                        @endcan
                    </tr>
                    @foreach ($packages as $package)
                        <tr data-id="{{ $package->id }}">
                            <td>{{ $package->customerType->customerType }}</td>
                            <td>{{ $package->name }}</td>
                            <td>{{ $package->duration }}</td>
                            <td>{{ $package->price }}</td>
                            <td>
                                @if ($package->status == 1)
                                    <p class="text-success">Active</p>
                                @else
                                    <p class="text-danger">Inactive</p>
                                @endif
                            </td>
                            @can('update', App\Models\Package::class)
                                <td>
                                    <button type="button" class="btn btn-outline-warning btn-sm btn_edit_package"><i class="bx bx-edit"></i></button>
                                </td>
                            @endcan
                        </tr>
                    @endforeach
                </table>
            </div>

            <div>
                {{ $packages->links() }}
            </div>
        </div>
    </div>

    @include('Packages.package_add_modal')
    @include('Packages.package_edit_modal')

    <script src="{{ asset('js/package.js') }}"></script>
@endsection
