@extends('layouts.layout')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>
                User Access
                @can('create', App\Models\UserAccess::class)
                    <button class="btn btn-primary float-end" id="btn_open_user_access">Add Access</button>
                @endcan
            </h5>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                    <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                    <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table_responsive">
                <table class="table" id="tbl_user_access">
                    <tr>
                        <th>Camp</th>
                        <th>User</th>
                        <th>Page</th>
                        <th>Create</th>
                        <th>View</th>
                        <th>Edit</th>
                        <th>Delete</th>
                        @can('update', App\Models\UserAccess::class)
                            <th>Change</th>
                        @endcan
                        @can('delete', App\Models\UserAccess::class)
                            <th>Delete</th>
                        @endcan
                    </tr>
                    @foreach ($user_accesses as $access)
                        <tr data-id="{{ $access->id }}">
                            <td>{{ $access->camp->name }}</td>
                            <td>{{ $access->user->name }}</td>
                            <td>{{ $access->page->pagename }}</td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input chk_access" type="checkbox" role="switch"
                                        id="create_access_{{ $access->id }}" {{ $access->create ? 'checked' : '' }}>
                                    <label class="form-check-label" for="create_access_{{ $access->id }}">Create</label>
                                </div>
                            </td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input chk_access" type="checkbox" role="switch"
                                        id="view_access_{{ $access->id }}" {{ $access->view ? 'checked' : '' }}>
                                    <label class="form-check-label" for="view_access_{{ $access->id }}">View</label>
                                </div>
                            </td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input chk_access" type="checkbox" role="switch"
                                        id="edit_access_{{ $access->id }}" {{ $access->edit ? 'checked' : '' }}>
                                    <label class="form-check-label" for="edit_access_{{ $access->id }}">Edit</label>
                                </div>
                            </td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input chk_access" type="checkbox" role="switch"
                                        id="delete_access_{{ $access->id }}" {{ $access->delete ? 'checked' : '' }}>
                                    <label class="form-check-label" for="delete_access_{{ $access->id }}">Delete</label>
                                </div>
                            </td>

                            @can('update', App\Models\UserAccess::class)
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm btn_edit_access">Change</button>
                                </td>
                            @endcan

                            @can('delete', App\Models\UserAccess::class)
                                <td>
                                    <form action="{{ route('useraccess.delete') }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="hide_access_id" value="{{ $access->id }}">
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            @endcan
                        </tr>
                    @endforeach
                </table>
                <div class="d-flex float-end mt-3">
                    {{ $user_accesses->links() }}
                </div>
            </div>

        </div>
    </div>

    @include('UserAccess.user_access_modal')

    <script src="{{ asset('js/useraccess.js') }}"></script>
@endsection
