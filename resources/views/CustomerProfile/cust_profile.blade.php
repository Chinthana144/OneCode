@extends('CustomerProfile.cust_layout')

@section('content')

    <div id="div_customer_details">
        <h5>
            Hi, {{ $customer->fullname }}
            <br>
            {{ $customer->username }}
        </h5>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div id="div_profile" class="container">
        <h5>Change Password</h5>
        <form action="{{ route('customer.changePassword') }}" method="post">
            @csrf
            <input type="hidden" name="hide_customer_id" value="{{ $customer->id }}">
            <label for="">Password</label>
            <input type="password" name="cust_pwd" id="cust_pwd" value="{{ $customer->password }}" class="form-control mb-2 pwd">

            <label for="">Re-enter Password</label>
            <input type="password" name="cust_re_pwd" id="cust_re_pwd" value="{{ $customer->password }}" class="form-control mb-2 pwd">

            <input type="checkbox" name="chk_pwd" id="chk_pwd">
            <label for="chk_pwd">Show Password</label>
            <br>
            <button type="submit" class="btn btn-primary mt-2" id="btn_submit">Change Password</button>
        </form>
    </div>

    <script>
        $(document).ready(function () {
            $('#chk_pwd').change(function(){
                let checked = $(this).is(':checked');
                if(checked){
                    $(".pwd").attr('type', 'text');
                }
                else{
                    $(".pwd").attr('type', 'password');
                }
            });

            $('#cust_re_pwd').change(function(){
                var pwd = $("#cust_pwd").val();
                var re_pwd = $(this).val();

                if(pwd != re_pwd){
                    $(".pwd").css('border-color', 'red');
                    $("#btn_submit").attr('disabled', true);
                }
                else{
                    $(".pwd").css('border-color', 'green');
                    $("#btn_submit").attr('disabled', false);
                }
            });
        });
    </script>
@endsection
