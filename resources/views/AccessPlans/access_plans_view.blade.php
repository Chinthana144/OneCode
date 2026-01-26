@extends('layouts.layout')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>Access Plans</h5>
        </div>
        <div class="card-body">
            <table class="table" id="tbl_access_plans">
                <tr>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Customer/Code</th>
                    <th>Package</th>
                    <th>MAC/IP</th>
                    <th>Start/Expire</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>User</th>
                    @can('update', App\Models\AccessPlanes::class)
                        <th>Change</th>
                    @endcan
                    @can('delete', App\Models\AccessPlanes::class)
                        <th>Delete</th>
                    @endcan
                </tr>
                @foreach ($access_plans as $access_plan)
                    <tr>
                        <td>
                            @if ($access_plan->accessable_type == 'App\Models\Subscriptions')
                                <span class="badge bg-primary">Subscription</span>
                            @else
                                <span class="badge bg-success">Voucher</span>
                            @endif
                        </td>
                        <td>
                            {{ $access_plan->purchaseDate }} <br>
                            {{ $access_plan->purchaseDateTime }}
                        </td>
                        <td>
                            @if ($access_plan->accessable_type == 'App\Models\Subscriptions')
                                {{ $access_plan->accessable->customer->fullname }} <br>
                                {{ $access_plan->accessable->customer->phone }}
                            @else
                                {{ $access_plan->accessable->code }} <br>
                                {{ $access_plan->accessable->expire_date }}
                            @endif
                        </td>
                        <td>
                            {{ $access_plan->package->name }} <br>
                            {{ $access_plan->package->duration }} Days
                        </td>
                        <td>
                            {{ $access_plan->mac_address ?? "N/A" }} <br>
                            {{ $access_plan->ip_address ?? "N/A" }}
                        </td>
                        <td>
                            {{ $access_plan->login_at ?? "N/A" }} <br>
                            {{ $access_plan->expire_at ?? "N/A" }}
                        </td>
                        <td>{{ $access_plan->price }}</td>
                        <td>
                            @switch($access_plan->status)
                                @case(1)
                                    <span class="badge bg-primary">ACTIVE</span>
                                @break
                                @case(2)
                                    <span class="badge bg-success">RUNNING</span>
                                @break
                                @case(3)
                                    <span class="badge bg-warning">EXPIRED</span>
                                @break
                                @case(4)
                                    <span class="badge bg-danger">CANCLED</span>
                                @break
                                @case(5)
                                    <span class="badge bg-secondary">TRANSFERRED</span>
                                @break
                            @endswitch
                        </td>
                        <td>{{ $access_plan->user->name }}</td>
                        @can('update', App\Models\AccessPlanes::class)
                            <td class="d-flex">
                                <button class="btn btn-warning btn-sm ms-1 btn_reset_plan" data-id={{ $access_plan->id }}><i class="bx bx-reset"></i></button>
                                <button class="btn btn-success btn-sm ms-1"><i class="bx bx-transfer-alt"></i></button>
                                <button class="btn btn-info btn-sm ms-1"><i class="bx bx-time"></i></button>
                            </td>
                        @endcan
                        @can('delete', App\Models\AccessPlanes::class)
                            <td>
                                <form action="" method="post">
                                    @csrf
                                    <input type="hidden" name="hide_access_plan_id" value="{{ $access_plan->id }}">
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="bx bx-trash"></i></button>
                                </form>
                            </td>
                        @endcan
                    </tr>
                @endforeach
            </table>
        </div>
    </div>

    @include('AccessPlans.plan_reset_modal')

    <script src="{{ asset('js/access_plans.js') }}"></script>
@endsection
