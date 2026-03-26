@extends('layouts.master')

@section('title', __('Logs of Request Images'))

@section('content')
<div class="main-container container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1 class="page-title">{{ __('Logs of Request Images') }}</h1>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom bg-white">
                            <thead class="bg-light">
                                <tr>
                                    <th>Sr.no.</th>
                                    <th>Requested Date and Timestamp</th>
                                    <th>Created By ( Users )</th>
                                    <th>Type of Requests</th>
                                    <th>Number of Images Uploaded</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($requestLogs as $key => $log)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $log->created_at ? $log->created_at->format('d M Y, h:i A') : 'N/A' }}</td>
                                    <td>{{ $log->requestedBy->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge {{ $log->category == 'Create' ? 'bg-success' : 'bg-primary' }}">
                                            {{ $log->category }}
                                        </span>
                                    </td>
                                    <td><strong>{{ $log->count }}</strong></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No Logs Found</td>
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
