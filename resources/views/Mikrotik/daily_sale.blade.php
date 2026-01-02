@extends('layouts.layout')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>Daily Sale</h5>
        </div>
        <div class="card-body">
            <p>calculate daily sale</p>

            <form action="{{ route('client.transferData') }}" method="post">
                @csrf
                <input type="date" name="transfer_date" class="form-control">

                <button type="submit" class="btn btn-primary">Transfer Subscription</button>
            </form>
        </div>
    </div>
@endsection
