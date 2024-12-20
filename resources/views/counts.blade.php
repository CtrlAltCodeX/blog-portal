@extends('layouts.master')

@section('title', __('Manage Inventory | Published ( M/S )'))

@push('css')
<style>
    ul {
        justify-content: end;
    }

    #basic-datatable_info {
        display: none;
    }

    .tooltip {
        position: relative;
        display: inline-block;
        border-bottom: 1px dotted black;
    }

    .tooltip .tooltiptext {
        visibility: hidden;
        width: 120px;
        background-color: black;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px 0;

        /* Position the tooltip */
        position: absolute;
        z-index: 1;
    }

    .tooltip:hover .tooltiptext {
        visibility: visible;
    }
</style>
@endpush

@section('content')
<div>
    <div class="row row-sm">
        <div class="col-lg-12">
            <h5 align=center class='text-primary'><strong>NOTE: </strong> By Default This Page Will Show Current Month Records If Date Range Not Selected.</h5>
            <div class="card">
                <div class="card-header justify-content-between">
                    
                    <h3 class="card-title">Record 1 ( Create New Listing )</h3>
                    
                    <form action="{{ route('users.count') }}" method="GET" id='filter'>
                        <div class="d-flex" style="grid-gap:10px;">
                            <div class="d-flex align-items-center" style="grid-gap:10px;">
                                <span>Start:</span>
                                <input type="date" name="start_date" class="form-control " value="{{ request()->start_date }}" />
                                <span>End:</span>
                                <input type="date" name="end_date" class="form-control " value="{{ request()->end_date }}" />
                            </div>
                            @if(auth()->user()->hasRole('Super Admin'))
                            <select class="form-control" id='user' name="user_id">
                                <option value="">All User</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request()->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @endif
                            
                            <button type='submit' class='btn btn-primary w-25'>Filter</button>
                        </div>
                    </form>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatable" class="table table-bordered text-nowrap border-bottom">
                            <thead>
                                <tr>
                                    <th>{{ __('Sl. No.') }}</th>
                                    <th>{{ __('New Created') }}</th>
                                    <th>{{ __('Approved') }}</th>
                                    <th>{{ __('Rejected') }}</th>
                                    <th>{{ __('Delete') }}</th>
                                    <th>{{ __('View Details') }}</th>
                                    <th>{{ __('User Name') }}</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                @forelse($countCreated as $key => $data)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $data->total_created }}</td>
                                    <td>{{ $data->total_approved }}</td>
                                    <td>{{ $data->total_rejected }}</td>
                                    <td>{{ $data->total_deleted }}</td>
                                    <td>
                                        <a href="{{ route('profile.listing', ['user' => 'all', 'status' => 0]) }}" target='_blank'>View</a>
                                    </td>
                                    <td>{{ $data->user->name }}</td>
                                </tr>
                                @empty
                                <tr align=center>
                                    <td colspan="8">No Result</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header justify-content-between">
                    <h3 class="card-title">Record 2 ( Update Old Listing )</h3>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatable" class="table table-bordered text-nowrap border-bottom">
                            <thead>
                                <tr>
                                    <th>{{ __('Sl. No.') }}</th>
                                    <th>{{ __('Updated / Created') }}</th>
                                    <th>{{ __('Approved') }}</th>
                                    <th>{{ __('Rejected') }}</th>
                                    <th>{{ __('Delete') }}</th>
                                    <th>{{ __('View Details') }}</th>
                                    <th>{{ __('User Name') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($countEdited as $key => $data)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $data->total_created }}</td>
                                    <td>{{ $data->total_approved }}</td>
                                    <td>{{ $data->total_rejected }}</td>
                                    <td>{{ $data->total_deleted }}</td>
                                    <td>
                                        <a href="{{ route('profile.listing', ['user' => 'all', 'status' => 0]) }}" target='_blank'>View</a>
                                    </td>
                                    <td>{{ $data->user->name }}</td>
                                </tr>
                                @empty
                                <tr align=center>
                                    <td colspan="8">No Result</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
