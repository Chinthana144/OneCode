@extends('layouts.layout')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>
                @if (is_null($camp))
                    Daily Sales Report
                @else
                    Sales Summary Report in <b>{{ $camp->name }}</b>
                @endif
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('rptSaleSummarySearch.search') }}" method="get">
                <div class="row">
                    <div class="col-md-5">
                        <label for="" class="form-label">Select Month</label>
                        <input type="month" name="month" id="month" class="form-control"
                            value="{{ isset($year_month) ? $year_month : '' }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" name="action" value="search" class="btn btn-primary mt-4">Search</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <button type="submit" name="action" value="excel" class="btn btn-success m-2">Download Excel</button>
                        <button type="submit" name="action" value="pdf" class="btn btn-danger m-2">Download PDF</button>
                    </div>
                </div>
            </form>

            <table class="table">
                <tr>
                    <th>No</th>
                    <th>Date</th>
                    <th>Count</th>
                    <th>Sale</th>
                </tr>
                @foreach ($sales as $sale)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $sale->purchaseDate }}</td>
                        <td>{{ $sale->invoice_count }}</td>
                        <td>{{ $sale->total_sales }}</td>
                    </tr>
                @endforeach
            </table>
            <div>
                {{ $sales->links() }}
            </div>
        </div>
    </div>
@endsection
