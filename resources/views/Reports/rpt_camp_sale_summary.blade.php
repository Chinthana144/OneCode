@extends('layouts.layout')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>
               Camps Sale Summary Report
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('rptCampSaleSummary.search') }}" method="get">
                <div class="row">
                    <div class="col-md-5">
                        <label for="" class="form-label">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control"
                            value="{{ isset($start_date) ? $start_date : '' }}">
                    </div>
                    <div class="col-md-5">
                        <label for="" class="form-label">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control"
                            value="{{ isset($end_date) ? $end_date : '' }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" name="action" value="search" class="btn btn-primary mt-4">Search</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <button type="submit" name="action" value="excel" class="btn btn-success m-2">Download Excel</button>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" name="action" value="pdf" class="btn btn-danger m-2">Download PDF</button>
                    </div>
                </div>
            </form>

            <table class="table table-bordered">
                <tr>
                    <th rowspan="2" class="text-center">Camp Name</th>
                    <th colspan="2" class="text-center">Subscription</th>
                    <th colspan="2" class="text-center">Voucher</th>
                    <th rowspan="2" class="text-center">Total</th>
                </tr>
                <tr>
                    <th>Count</th>
                    <th>Sale</th>
                    <th>Count</th>
                    <th>Sale</th>
                </tr>
                @foreach ($sales as $sale)
                    <tr class="text-center">
                        <td>{{ $sale['camp'] }}</td>
                        <td>{{ $sale['subscription_count']}}</td>
                        <td>{{ $sale['subscription_sale']}}</td>
                        <td>{{ $sale['voucher_count']}}</td>
                        <td>{{ $sale['voucher_sale']}}</td>
                        <td>{{ $sale['total_sale']}}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
