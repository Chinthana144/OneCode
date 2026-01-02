@extends('layouts.layout')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>
                @if (is_null($camp))
                    Sales Reports
                @else
                    Sales Reports in <b>{{ $camp->name }}</b>
                @endif
            </h5>
        </div>
        <div class="card-body">
            <div class="col-md-12">
                <p>Daily sales reports</p>
                <a href="/rpt_daily_sales" class="btn btn-primary">Daily Sale Report</a>
                <a href="/rpt_daily_summary" class="btn btn-primary">Daily Sales Summary Report </a>
                <a href="/rpt_sales_summary" class="btn btn-primary">Sales Summary Report</a>
                <hr>
                <p>Userwise sales reports</p>
                <a href="/rpt_user_sales" class="btn btn-primary">User Sales Report</a>
                <a href="/rpt_user_package_summary" class="btn btn-primary">User Package Summary Report</a>
                <a href="/rpt_user_sales_summary" class="btn btn-primary">User Sales Summary Report</a>
                <hr>
                <p>Campwise sales reports</p>
                <a href="#" class="btn btn-primary">Camp Sales Report</a>
                <a href="#" class="btn btn-primary">Camp Summary Report</a>
            </div>
        </div>
    </div>
@endsection
