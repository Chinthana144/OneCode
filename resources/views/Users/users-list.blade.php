@extends('layouts.layout')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>
                Users
                @can('create', App\Models\User::class)
                    <button id="btn_open_register" class="btn btn-primary btn-sm float-end">Register</button>
                @endcan
            </h5>
        </div>
        <div class="card-body">
            <div class="table_responsive">
                <table class="table" id="tbl_users">
                    <tr>
                        <th>ID</th>
                        <th>Role</th>
                        <th>Name</th>
                        <th>Email</th>
                        @can('update', App\Models\User::class)
                            <th>Action</th>
                        @endcan
                    </tr>
                    @foreach ($users as $user)
                        <tr data-id="{{ $user->id }}">
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->role->name }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            @can('update', App\Models\User::class)
                                <td>
                                    <button class="btn btn-primary btn-sm btn_change_password">Change Password</button>
                                    <button class="btn btn-info btn-sm btn_open_edit">Edit</button>
                                </td>
                            @endcan
                        </tr>
                    @endforeach
                </table>
            </div>

        </div>
    </div>

    @include('Users.register_modal')
    @include('Users.password_modal')
    @include('Users.edit_modal')

    <script src="{{ asset('js/users.js') }}"></script>
@endsection
