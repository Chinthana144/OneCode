@extends('layouts.layout')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>Sales Reports</h5>
        </div>
        <div class="card-body">
            <p>Daily sales reports</p>
                <a href="/rpt_daily_sale" class="btn btn-primary">Daily Sale Report</a>
                <a href="/rpt_sale_summary" class="btn btn-primary">Sales Summary Report</a>
                <hr>
        </div>
    </div>
@endsection
