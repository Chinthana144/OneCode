@extends('layouts.layout')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/analysis.css') }}">

    <div class="card">
        <div class="card-header">
            <h5>Data Analyzing of <b>{{ $camp->name }}</b></h5>
        </div>
        <div class="card-body">

            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" id="btn_7_days" class="btn btn-outline-primary btn-sm">7 days</button>
                <button type="button" id="btn_14_days" class="btn btn-outline-primary btn-sm">14 days</button>
                <button type="button" id="btn_30_days" class="btn btn-outline-primary btn-sm">30 days</button>
            </div>

            <div class="row">
                <div class="col-md-9">
                    <div id="bar_chart" class="p-2"></div>
                </div>

                <div class="col-md-3">
                    <div id="div_side">

                        <div class="div_box" id="div_sale_box">
                            <h6>Monthly Sale</h6>
                            <i class="bx bx-book-bookmark fs-3"></i>
                            <h4>{{ $monthly_subs_sale }} AED</h4>
                        </div>

                        <div class="div_box" id="div_subs_box">
                            <h6>Monthly Subscriptions</h6>
                            <i class="bx bx-clipboard fs-3"></i>
                            <h4>{{ $monthly_subs_count }}</h4>
                        </div>

                        <div class="div_box" id="div_customers_box">
                            <h6>Customers</h6>
                            <i class="bx bx-group fs-3"></i>
                            <h4>{{ $total_customers }}</h4>
                        </div>

                    </div>


                </div>

            </div>
        </div>
    </div>

    <script src="{{ asset('js/analysis.js') }}"></script>
    <script src="{{ asset('js/apexcharts.min.js') }}"></script>
@endsection
