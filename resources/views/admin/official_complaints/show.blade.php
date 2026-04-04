@extends('layouts.master')

@section('title', 'Official Complaint Details - ' . $complaint->complaint_id)

@section('content')
<div class="row mt-4">
    <div class="col-12">
        <!-- Complaint Main Details -->
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">OFFICIAL COMPLAINT DETAILS: <span class="text-primary">{{
                        $complaint->complaint_id }}</span></div>
                <div class="d-flex align-items-center gap-3">
                    @if($complaint->specific_tag)
                    {{-- <span class="badge bg-primary-transparent border border-primary text-primary px-3">
                        <i class="fe fe-user me-1"></i> SPECIFIC EMPLOYEE: {{ $complaint->employee_name }}
                    </span> --}}
                    @endif

                    @php
                    $badgeClass = match($complaint->status) {
                    'pending' => 'bg-warning text-dark',
                    'verification' => 'bg-info',
                    'solved' => 'bg-success',
                    'mercy' => 'bg-danger',
                    'recovered' => 'bg-secondary',
                    default => 'bg-light text-dark'
                    };
                    $statusLabel = match($complaint->status) {
                    'pending' => 'Response Needed',
                    'verification' => 'Waiting Verification',
                    'solved' => 'Solved',
                    'mercy' => 'Mercy',
                    'recovered' => 'Loss Recovered',
                    default => $complaint->status
                    };
                    @endphp
                    <span class="badge {{ $badgeClass }} px-3 py-2 fs-6">{{ $statusLabel }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-2">
                        <p class="text-muted mb-1">Created By:</p>
                        <h6 class="fw-bold mb-0">{{ $complaint->user->name }}</h6>
                        <small class="text-muted">{{ $complaint->user->email }}</small>
                    </div>
                    <div class="col-md-2">
                        <p class="text-muted mb-1">Created Date:</p>
                        <h6 class="fw-bold">{{ $complaint->created_at->format('d M, Y h:i A') }}</h6>
                    </div>
                    <div class="col-md-2">
                        <p class="text-muted mb-1">Department:</p>
                        <h6 class="fw-bold text-info">{{ $complaint->department->name }}</h6>
                    </div>
                    <div class="col-md-2">
                        <p class="text-muted mb-1">Issue Type:</p>
                        <h6 class="fw-bold text-info">{{ $complaint->issueType->name }}</h6>
                    </div>
                    <div class="col-md-2">
                        <p class="text-muted mb-1">Title:</p>
                        <h5 class="fw-bold">{{ $complaint->title }}</h5>
                    </div>
                </div>

                @if($complaint->specific_tag)
                {{-- <div
                    class="alert alert-primary light mb-4 border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-1">SPECIFIC EMPLOYEE INFORMATION</h6>
                        <p class="mb-0">Name: <strong>{{ $complaint->employee_name }}</strong> | Email: <strong>{{
                                $complaint->employee_email }}</strong> | Mobile: <strong>{{ $complaint->employee_mobile
                                }}</strong></p>
                    </div>
                </div> --}}
                @endif


                <div class="mb-4">
                    <p class="text-muted mb-1">Description:</p>
                    <div class="p-3 bg-light rounded-4 border">
                        {{ $complaint->description }}
                    </div>
                </div>

                @if($complaint->attachments->count() > 0)
                <div class="mb-4">
                    <p class="text-muted mb-1">Attachments:</p>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($complaint->attachments as $attachment)
                        <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank"
                            class="btn btn-sm btn-outline-primary rounded-pill">
                            <i class="fa fa-file-download me-1"></i> View Attachment
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Order Details Table -->
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">ORDER DETAILS</div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap mb-0">
                        <thead class="bg-light text-center">
                            <tr>
                                <th>Order ID</th>
                                <th>Ref No</th>
                                <th>Tracking ID</th>
                                <th>Customer Name</th>
                                <th>Customer Phone</th>
                                <th>Loss Value</th>
                                <th>Self Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($complaint->orders as $order)
                            <tr class="text-center">
                                <td class="fw-bold text-primary">{{ $order->order_id }}</td>
                                <td>{{ $order->ref_no }}</td>
                                <td>{{ $order->tracking_id }}</td>
                                <td>{{ $order->cx_name }}</td>
                                <td>{{ $order->cx_phone }}</td>
                                <td class="text-danger fw-bold">₹{{ number_format($order->loss_value, 2) }}</td>
                                <td>{{ $order->self_note }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Response Logs -->
        <div class="card custom-card">
            <div class="card-header bg-light">
                <div class="card-title">RESPONSE DETAILS / LOGS</div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered mb-0">
                        <thead class="bg-white text-center">
                            <tr>
                                <th style="width: 80px;">SL. No.</th>
                                <th>Response Details</th>
                                <th>Attachment</th>
                                <th>Status</th>
                                <th>Reply Date & Time</th>
                                <th>Reply By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($complaint->replies as $index => $reply)
                            <tr>
                                <td class="text-center align-middle">{{ $index + 1 }}</td>
                                <td>
                                    <div class="p-2">
                                        <p class="mb-2">{{ $reply->message }}</p>
                                    </div>
                                </td>
                                <td>
                                    <div class="mt-2 pt-2 text-center">
                                        @foreach($reply->attachments as $att)
                                        @php
                                        $filename = explode('/', $att->file_path)[2];
                                        @endphp
                                        <a href="{{ route('assets', $filename) }}" target="_blank"
                                            style='font-size:20px;'><i class="fa fa-download me-1"></i></a>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="text-center align-middle">
                                    @if($reply->status)
                                    @php
                                    $badgeClassLog = match($reply->status) {
                                    'pending' => 'bg-warning text-dark',
                                    'verification' => 'bg-info',
                                    'solved' => 'bg-success',
                                    'mercy' => 'bg-danger',
                                    'recovered' => 'bg-secondary',
                                    default => 'bg-light text-dark'
                                    };
                                    $statusLabelLog = match($reply->status) {
                                    'pending' => 'Response Needed',
                                    'verification' => 'Waiting Verification',
                                    'solved' => 'Solved',
                                    'mercy' => 'Mercy',
                                    'recovered' => 'Loss Recovered',
                                    default => $reply->status
                                    };
                                    @endphp
                                    <span class="badge {{ $badgeClassLog }}">{{ $statusLabelLog }}</span>
                                    @else
                                    -
                                    @endif
                                </td>
                                <td class="text-center align-middle">{{ $reply->created_at->format('d M, Y')
                                    }}<br><small class="text-muted">{{ $reply->created_at->format('h:i A') }}</small>
                                </td>
                                <td class="text-center align-middle text-primary fw-bold">{{ $reply->user->name }}
                                    <br /><small class="text-muted">{{ $reply->user->email }}</small></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center p-5 text-muted">
                                    <i class="fe fe-info fs-30 mb-3 d-block"></i>
                                    No responses or logs found for this complaint yet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="text-end mb-4">
            <a href="{{ route('admin.official-complaints.index') }}" class="btn btn-secondary px-5 rounded-pill">
                <i class="fe fe-arrow-left me-1"></i> BACK TO DASHBOARD
            </a>
        </div>
    </div>
</div>
@endsection