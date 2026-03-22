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
                                    <th>Created By</th>
                                    <th>Category</th>
                                    <th>Count of Images</th>
                                    <th>Created Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($requestLogs as $log)
                                <tr>
                                    <td>{{ $log->requestedBy->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge {{ $log->category == 'Create' ? 'bg-success' : 'bg-primary' }}">
                                            {{ $log->category }}
                                        </span>
                                    </td>
                                    <td><strong>{{ $log->count }}</strong></td>
                                    <td>{{ $log->created_at ? $log->created_at->format('d M Y, h:i A') : 'N/A' }}</td>
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
