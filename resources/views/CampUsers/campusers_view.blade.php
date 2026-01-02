@extends('layouts.layout')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>Camp Users
                @can('create', App\Models\CampUser::class)
                    <button class="btn btn-primary btn-sm float-end" id="btn_open_campusers">Add User</button>
                @endcan
            </h5>
        </div>
        <div class="card-body">

            <div class="table_responsive">
                <table class="table" id="tbl_campusers">
                    <tr>
                        <th>Username</th>
                        <th>Camp Name</th>
                        @can('update', App\Models\CampUser::class)
                            <th>Action</th>
                        @endcan
                        @can('delete', App\Models\CampUser::class)
                            <th>Access</th>
                        @endcan
                    </tr>
                    @foreach ($campusers as $campuser)
                        <tr data-id="{{ $campuser->id }}">
                            <td>{{ $campuser->users->name }}</td>
                            <td>{{ $campuser->camps->name }}</td>
                            @can('update', App\Models\CampUser::class)
                                <td>
                                    <button class="btn btn-info btn-sm btn_open_edit">Edit</button>
                                </td>
                            @endcan
                            @can('delete', App\Models\CampUser::class)
                                <td>
                                    <form action="{{ route('campuser.delete') }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="hide_campuser_id" value="{{ $campuser->id }}">
                                        <button id="btn_remove" class="btn btn-danger btn-sm">Remove</button>
                                    </form>
                                </td>
                            @endcan
                        </tr>
                    @endforeach
                </table>
            </div>

        </div>
    </div>

    @include('CampUsers.campuser_modal')
    @include('CampUsers.campuser_edit_modal')

    <script src="{{ asset('js/campusers.js') }}"></script>
@endsection
