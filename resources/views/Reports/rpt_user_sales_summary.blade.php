@extends('layouts.layout')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>
                @if (is_null($camp))
                    User Sales Summary Report
                @else
                    User Sales Summary Report in <b>{{ $camp->name }}</b>
                @endif
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('rptUserSalesSummary.search') }}" method="get">
                <div class="row">
                    <div class="col-md-3">
                        <label for="" class="form-label">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control"
                            value="{{ isset($start_date) ? $start_date : '' }}">
                    </div>
                    <div class="col-md-3">
                        <label for="" class="form-label">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control"
                            value="{{ isset($end_date) ? $end_date : '' }}">
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
                    <th>User</th>
                    <th>Count</th>
                    <th>Sale</th>
                </tr>
                @foreach ($sales as $sale)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $sale->user_name }}</td>
                        <td>{{ $sale->row_count }}</td>
                        <td>{{ $sale->total_sales }}</td>
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
