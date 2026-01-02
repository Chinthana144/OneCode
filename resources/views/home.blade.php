@extends('layouts.layout')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <div id="div_top">
        <div class="div_box" id="div_sale_box">
            <h6>Daily Sales</h6>
            <i class="bx bx-bar-chart fs-3"></i>
            <h4>{{ $daily_subs_total }} AED</h4>
        </div>
        <div class="div_box" id="div_subs_box">
            <h6>Subscriptions</h6>
            <i class="bx bx-clipboard fs-3"></i>
            <h4>{{ $daily_subs_count }}</h4>
        </div>
        <div class="div_box" id="div_month_sale_box">
            <h6>Monthly Sales</h6>
            <i class="bx bx-book-bookmark fs-3"></i>
            <h4>{{ $monthly_subs_sale }} AED</h4>
        </div>
        <div class="div_box" id="div_users_box">
            <h6>Users</h6>
            <i class="bx bx-user fs-3"></i>
            <h4>{{ $running_users }}</h4>
        </div>
    </div>

    {{-- mobile view --}}
    <div id="div_mobile_top">
        <div class="div_box" id="div_sale_box">
            <h4>
                <i class="bx bx-bar-chart fs-3"></i>
                Daily Sales
                {{ $daily_subs_total }} AED
            </h4>
        </div>
        <div class="div_box" id="div_subs_box">
            <h4>
                <i class="bx bx-clipboard fs-3"></i>
                Subscriptions
                {{ $daily_subs_count }}
            </h4>
        </div>
        <div class="div_box" id="div_month_sale_box">
            <h4>
                <i class="bx bx-book-bookmark fs-3"></i>
                Monthly Sales
                {{ $monthly_subs_sale }} AED
            </h4>
        </div>
        <div class="div_box" id="div_users_box">
            <h4>
                <i class="bx bx-user fs-3"></i>
                Users
                {{ $running_users }}
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7 mt-2">
        <div class="btn-group" role="group" aria-label="Basic example">
            <button type="button" id="btn_7_days" class="btn btn-outline-primary btn-sm">7 days</button>
            <button type="button" id="btn_14_days" class="btn btn-outline-primary btn-sm">14 days</button>
            <button type="button" id="btn_30_days" class="btn btn-outline-primary btn-sm">30 days</button>
        </div>
            <div id="bar_chart" class="p-2"></div>
        </div>

        <div class="col-md-5">
            <div id="donut_chart" class="p-2"></div>
        </div>
    </div>

    {{-- js --}}
    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script src="{{ asset('js/apexcharts.min.js') }}"></script>
@endsection
