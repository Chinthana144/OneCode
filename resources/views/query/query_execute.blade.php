@extends('layouts.layout')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>Database Query Execution</h5>
        </div>
        <div class="card-body">
            <p>Only admins and programmers can have access to this page.</p>

            <div class="container container-md">

                <label for="">Select Query</label>
                <textarea name="txt_select_query" id="txt_select_query" cols="30" rows="4" class="form-control">

                </textarea>
                <button class="btn btn-primary mt-2" id="btn_select_query">Execute</button>
                
                <p id="p_data"></p>

            </div>
        </div>
    </div>

    <script src="{{ asset('js/db_query.js') }}"></script>
@endsection
