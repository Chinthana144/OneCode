@extends('layouts.layout')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>Mikrotik Testing</h5>
        </div>
        <div class="card-body">

             <div>
                Identity: {{ $response[0]['name'] }}
            </div>

            {{-- @foreach ($users as $user)
                <p>{{ $user['name'] }}</p>
            @endforeach --}}

            <hr>

            <p>get profiles</p>
{{--
            @foreach ($profiles as $pro)
                <p>{{ $pro['name'] }}</p>
            @endforeach --}}


            <div>
                <form action="{{ route('mikrotik.checkConnection') }}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-primary m-2">Check Connection</button>
                </form>
            </div>

        </div>
    </div>
@endsection
