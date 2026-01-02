@extends('layouts.layout')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>
                Camps
                @can('create', App\Models\Camp::class)
                    <a href="/add-camp" class="btn btn-primary btn-sm float-end">Add Camp</a>
                @endcan
            </h5>
        </div>
        <div class="card-body">
            <div class="table_responsive">
                <table class="table">
                    <tr>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Host</th>
                        <th>Port</th>
                        <th>Target</th>
                        <th>Status</th>
                        @can('update', App\Models\Camp::class)
                            <th>Action</th>
                        @endcan

                    </tr>
                    @foreach ($camps as $camp)
                        <tr>
                            <td>{{ $camp->name }}</td>
                            <td>{{ $camp->location }}</td>
                            <td>{{ $camp->mikritikIP }}</td>
                            <td>{{ $camp->mikritikPort }}</td>
                            <td>{{ $camp->monthly_target }}</td>
                            <td>
                                @if ($camp->status == 1)
                                    <p class="text-success">Active</p>
                                @else
                                    <p class="text-danger">Inactive</p>
                                @endif
                            </td>

                            @can('update', App\Models\Camp::class)
                                <td>
                                    <form action="{{ route('camps.edit') }}" method="post">
                                        @csrf
                                        <input type="hidden" value="{{ $camp->id }}" name="hide_camp_id">
                                        <button type="submit" class="btn btn-info">Edit</button>
                                    </form>
                                </td>
                            @endcan

                        </tr>
                    @endforeach
                </table>
            </div>


        </div>
    </div>
@endsection
