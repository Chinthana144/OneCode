@extends('layouts.layout')

@section('content')
    <div class="card mb-3">
        <div class="card-header">
            <h5>User Profile</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <h2 class="center">Hi, {{ $user->name }}</h2>
                <h4>{{ $user->email }}</h4>
            </div>

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

    <div class="card mt-3">
        <div class="card-header">
            <h5>Change Password</h5>
        </div>
        <div class="card-body">
            <p>Do you want to change password?</p>

            <form action="{{ route('users.updatepwd') }}" method="post">
                @csrf
                @method('PUT')
                <input type="hidden" name="hide_user_id" value="{{ $user->id }}">
                <input type="hidden" name="hide_route" value="profile">

                {{-- <input type="text" name="old_password" class="form-control mb-2" placeholder="Old Password" required> --}}

                <div class="col-md-6">
                    <label for="">New Password</label>
                    <input type="password" name="new_password" id="new_password" class="form-control mb-2" placeholder="New Password" required>

                    <label for="">Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control mb-2" placeholder="Confirm Password" required>
                </div>

                <button type="submit" class="btn btn-primary">Change Password</button>
            </form>

        </div>
    </div>

    <script src="{{ asset('js/user_profile.js') }}"></script>

@endsection
