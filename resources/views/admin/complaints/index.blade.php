@extends('layouts.master')

@section('title', 'Manage Complaints')

@push('css')
<style>
    .nav-item {
        margin-right: 10px;
    }

    .nav-tabs .nav-link {
        background-color: lightgrey;
        color: black;
    }

    #candidateEnquiriesTable thead tr {
        background: #ccc;
    }

    .table {
        color: #9a9da1;
    }

    .table td {
        color: black !important;
    }

    .nav-tabs {
        padding-left: 12px;
        border-bottom: 0px;
    }

    .pagination {
        justify-content: end;
    }
</style>
@endpush

@section('content')
<div class="row mt-4">
    <div class="col-12">
        <div class="card overflow-hidden">
            <div class="card-header">
                <div class='d-flex justify-content-between w-100'>
                    <h3 class="card-title">COMPLAINT MANAGEMENT</h3>

                    <a class='btn btn-primary' href="{{ route('admin.complaints.create') }}">Create Complaint</a>
                </div>
            </div>

            <div class="card-body">
                <div class="row mb-4 px-3">
                    <div class="col-md-12">
                        <form action="{{ route('admin.complaints.index') }}" method="GET"
                            class="row g-3 align-items-center">
                            <input type="hidden" name="status" value="{{ $status }}">
                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-muted text-uppercase">Search
                                    Complaints</label>
                                <div class="input-group">

                                    <input type="text" name="search" class="form-control border-start-0"
                                        placeholder="Search by ID, Title, Description..."
                                        value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-muted text-uppercase">Filter by User</label>
                                <select name="complaint_user_id" class="form-control select2">
                                    <option value="">All Users</option>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('complaint_user_id')==$user->id ?
                                        'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary px-4"><i class="fas fa-filter me-1"></i>
                                    APPLY FILTER</button>
                                @if(request()->filled('search') || request()->filled('complaint_user_id'))
                                <a href="{{ route('admin.complaints.index', ['status' => $status]) }}"
                                    class="btn btn-outline-secondary px-4">CLEAR</a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <ul class="nav nav-tabs mb-4" id="complaintTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link py-3 fw-bold {{ $status == 'pending' ? 'active bg-primary text-white' : '' }}"
                            href="{{ route('admin.complaints.index', array_merge(request()->all(), ['status' => 'pending'])) }}">
                            RESPONSE NEEDED ({{ $counts['pending'] }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-3 fw-bold {{ $status == 'verification' ? 'active bg-info text-white' : '' }}"
                            href="{{ route('admin.complaints.index', array_merge(request()->all(), ['status' => 'verification'])) }}">
                            WAITING FOR VERIFICATION ({{ $counts['verification'] }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-3 fw-bold {{ $status == 'solved' ? 'active bg-success text-white' : '' }}"
                            href="{{ route('admin.complaints.index', array_merge(request()->all(), ['status' => 'solved'])) }}">
                            CASE SOLVED SUCCESSFUL ({{ $counts['solved'] }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-3 fw-bold {{ $status == 'mercy' ? 'active bg-warning text-dark' : '' }}"
                            href="{{ route('admin.complaints.index', array_merge(request()->all(), ['status' => 'mercy'])) }}">
                            MERCY ({{ $counts['mercy'] }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-3 fw-bold {{ $status == 'recovered' ? 'active bg-danger text-white' : '' }}"
                            href="{{ route('admin.complaints.index', array_merge(request()->all(), ['status' => 'recovered'])) }}">
                            LOSS RECOVERED ({{ $counts['recovered'] }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-3 fw-bold {{ $status == 'all' ? 'active bg-dark text-white' : '' }}"
                            href="{{ route('admin.complaints.index', array_merge(request()->all(), ['status' => 'all'])) }}">
                            ALL ({{ $counts['all'] }})
                        </a>
                    </li>
                </ul>

                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap border-bottom" id="basic-datatable">
                        <thead style="background: #e9ecef;">
                            <tr>
                                <th class="wd-5p">S.L.</th>
                                <th class="wd-15p">Batch ID</th>
                                <th>Created By</th>
                                <th>Created Date</th>
                                <th>Department</th>
                                <th>Issue Type</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($complaints as $index => $c)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><a href="{{ route('admin.complaints.show', $c->id) }}"
                                        class="text-primary fw-bold">{{ $c->complaint_id }}</a></td>
                                <td>{{ $c->user->name ?? 'N/A' }}</td>
                                <td>{{ $c->created_at->format('d M, Y') }}</td>
                                <td>{{ $c->department->name }}</td>
                                <td>{{ $c->issueType->name }}</td>
                                <td>
                                    <span data-bs-toggle="tooltip" title="{{ $c->title }}">
                                        {{ Str::limit($c->title, 20) }}
                                    </span>
                                </td>
                                <td>
                                    <span data-bs-toggle="tooltip" title="{{ $c->description }}">
                                        {{ Str::limit($c->description, 20) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                    $badgeClass = match($c->status) {
                                    'pending' => 'bg-warning text-dark',
                                    'verification' => 'bg-info',
                                    'solved' => 'bg-success',
                                    'mercy' => 'bg-danger',
                                    'recovered' => 'bg-secondary',
                                    default => 'bg-light text-dark'
                                    };
                                    $statusLabel = match($c->status) {
                                    'pending' => 'Response Needed',
                                    'verification' => 'Waiting Verification',
                                    'solved' => 'Solved',
                                    'mercy' => 'Mercy',
                                    'recovered' => 'Loss Recovered',
                                    default => $c->status
                                    };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary verify-btn"
                                        data-bs-toggle="modal" data-bs-target="#replyModal" data-id="{{ $c->id }}"
                                        data-ticket="{{ $c->complaint_id }}" data-status="{{ $c->status }}">
                                        <i class="fa fa-edit"></i> Verify
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reply Modal -->
<div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="replyModalLabel">TAKE ACTION / REPLY - <span
                        id="modalTicketId"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form id="replyForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Response Message <span class="text-danger">*</span></label>
                        <textarea name="message" class="form-control" rows="5" placeholder="Enter reply message..."
                            required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Update Status <span class="text-danger">*</span></label>
                            <select name="status" id="modalStatusSelect" class="form-control" required>
                                <option value="pending">Pending / Response Needed</option>
                                <option value="verification">Waiting For Verification</option>
                                <option value="solved">Case Solved Successful</option>
                                <option value="mercy">Mercy</option>
                                <option value="recovered">Loss Recovered</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Attach Files (Optional)</label>
                            <input type="file" name="attachments[]" class="form-control" multiple>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary px-4 rounded-pill"
                        data-bs-dismiss="modal">CANCEL</button>
                    <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold">SUBMIT RESPONSE</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function () {
        $('.verify-btn').on('click', function () {
            var id = $(this).data('id');
            var ticket = $(this).data('ticket');
            var status = $(this).data('status');

            $('#modalTicketId').text(ticket);

            var $statusSelect = $('#modalStatusSelect');
            var $statusDiv = $statusSelect.closest('.col-md-6'); // Find the column wrapper

            // Reset options to full list for a clean start
            $statusSelect.html(`
                <option value="pending">Pending / Response Needed</option>
                <option value="verification">Waiting For Verification</option>
                <option value="solved">Case Solved Successful</option>
                <option value="mercy">Mercy</option>
                <option value="recovered">Loss Recovered</option>
            `);

            if (status === 'pending') {
                // If Response Needed, hide status dropdown and set to Verification
                $statusDiv.hide();
                $statusSelect.val('verification');
            } else if (status === 'verification') {
                // If Waiting For Verification, show all options
                $statusDiv.show();
                $statusSelect.val(status);
            } else {
                // If other, restrict to Verification and Pending only
                $statusDiv.show();
                $statusSelect.html(`
                    <option value="verification">Waiting For Verification</option>
                    <option value="pending">Pending / Response Needed</option>
                `);
                $statusSelect.val('verification'); // Default to verification
            }

            // Generate exact reply URL dynamically
            var replyUrl = "{{ route('admin.complaints.reply', ':id') }}";
            replyUrl = replyUrl.replace(':id', id);
            $('#replyForm').attr('action', replyUrl);
        });
    });
</script>
@endpush

@push('css')
{{-- <style>
    .nav-tabs .nav-link {
        border-radius: 0;
        border: none;
        margin-bottom: 0;
        font-size: 13px;
        text-transform: uppercase;
        border-right: 1px solid #dee2e6;
    }

    .nav-tabs .nav-link:last-child {
        border-right: none;
    }

    .nav-tabs .nav-link.active {
        box-shadow: inset 0 -3px 0 0 #333;
    }

    #basic-datatable td {
        vertical-align: middle;
    }
</style> --}}
@endpush