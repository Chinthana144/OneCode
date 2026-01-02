@extends('layouts.layout')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>Add New Camp</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('camps.store') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <label for="" class="form-label">Camp Name</label>
                        <input type="text" class="form-control" name="name">

                        <label for="" class="form-label mt-2">Camp Location</label>
                        <input type="text" class="form-control" name="location">

                        <label for="" class="form-label mt-2">Contact Person Name</label>
                        <input type="text" class="form-control" name="contactPerson">

                        <label for="" class="form-label mt-2">Cantact Phone</label>
                        <input type="text" class="form-control" name="contactPhone">

                        <label for="" class="form-label mt-2">Camp email</label>
                        <input type="text" class="form-control" name="contactEmail">

                        <label for="" class="form-label mt-2">Monthly Target</label>
                        <input type="number" step="0.01" class="form-control" name="monthly_target" value="0.00">

                        <label for="" class="form-label mt-2">Camp Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="campstatus" name="chk_camp_stat" checked>
                            <label class="form-check-label" for="campstatus">Active</label>
                        </div>
                    </div>

                    <div class="col-md-6">

                        <label for="" class="form-label">Mikrotik Host</label>
                        <input type="text" class="form-control" name="mikritikIP">

                        <label for="" class="form-label mt-2">Mikrotik Port</label>
                        <input type="text" class="form-control" name="mikritikPort">

                        <label for="" class="form-label mt-2">Mikrotik Username</label>
                        <input type="text" class="form-control" name="mikrotikUsername">

                        <label for="" class="form-label mt-2">Mikrotik Password</label>
                        <input type="text" class="form-control" name="mikrotikPassword">

                        <label for="" class="form-label mt-2">RADIOUS Secret</label>
                        <input type="text" class="form-control" name="radiusSecret">

                        <label for="" class="form-label mt-2">RADIOUS IP</label>
                        <input type="text" class="form-control" name="radiusIP">

                        <button type="submit" class="btn btn-primary float-end mt-2 w-50">Save</button>
                    </div>


                </div>

            </form>
        </div>
    </div>
@endsection
