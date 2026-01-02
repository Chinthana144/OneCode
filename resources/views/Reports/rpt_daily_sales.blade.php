@extends('layouts.layout')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>
                @if (is_null($camp))
                    Daily Sales Report
                @else
                    Daily Sales Report in <b>{{ $camp->name }}</b>
                @endif
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('rptDailySales.search') }}" method="get">
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

            <table class="table">
                <tr>
                    <th>No</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Uername</th>
                    <th>Package</th>
                    <th>Duration</th>
                    <th>Price</th>
                </tr>
                @foreach ($sales as $sale)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ Str::substr($sale->purchaseDateTime, 0, 10) }}</td>
                        <td>{{ $sale->customer->fullname }}</td>
                        <td>{{ $sale->customer->username }}</td>
                        <td>{{ $sale->package->name }}</td>
                        <td>{{ $sale->package->duration }} days</td>
                        <td>{{ $sale->price }}</td>
                    </tr>
                @endforeach
            </table>
            <div>
                {{ $sales->links() }}
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#start_date").change(function() {
                var start_date = $(this).val();
                $("#end_date").val(start_date);
            });
        });
    </script>
@endsection
