@extends('layouts.layout')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>Add Users</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 border border-primary rounded p-2">
                    <h5>Add Hotspot Users</h5>
                    <form action="{{ route('mikrotik.store') }}" method="post">
                        @csrf
                        <label for="" class="form-label">Username</label>
                        <input type="text" name="username" class="form-control">

                        <label for="" class="form-label">Password</label>
                        <input type="password" name="pwd" class="form-control">

                        <button type="submit" class="btn btn-primary m-2">Submit</button>
                    </form>
                </div>

                <div class="col-md-6 border border-primary rounded p-2">
                    <h5>Bind MAC Address</h5>
                    <form action="{{ route('mikrotik.bindmac') }}" method="post">
                        @csrf
                        <label for="" class="form-label">Select Camp</label>
                        <select name="cmb_camp" id="cmb_camp" class="form-select">
                            @foreach ($camps as $camp)
                                <option value="{{ $camp->id }}">{{ $camp->name }}</option>
                            @endforeach
                        </select>

                        <label for="" class="form-label">Username</label>
                        <input type="text" name="username" class="form-control">

                        <label for="" class="form-label">Mac Address</label>
                        <input type="text" name="mac_address" class="form-control">

                        <button type="submit" class="btn btn-primary m-2">Bind MAC Address</button>
                    </form>
                </div>

                <div class="col-md-6 border border-primary rounded p-2">
                    <h5>Unbind MAC Address</h5>
                    <form action="{{ route('mikrotik.unbindmac') }}" method="post">
                        @csrf
                        <label for="" class="form-label">Select Camp</label>
                        <select name="cmb_camp" id="cmb_camp" class="form-select">
                            @foreach ($camps as $camp)
                                <option value="{{ $camp->id }}">{{ $camp->name }}</option>
                            @endforeach
                        </select>

                        <label for="" class="form-label">Mac Address</label>
                        <input type="text" name="mac_address" class="form-control">

                        <button type="submit" class="btn btn-primary m-2">Bind MAC Address</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/mikrotik_sub.js') }}"></script>
@endsection
