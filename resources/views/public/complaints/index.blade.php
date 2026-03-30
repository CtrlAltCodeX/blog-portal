@extends('layouts.public_complaint')

@section('title', 'My Complaints')

@section('content')
<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">COMPLAINT MANAGEMENT</h3>
        <a href="{{ route('public.complaints.dashboard') }}" class="btn btn-secondary rounded-pill px-4">
            <i class="fas fa-arrow-left me-1"></i> BACK TO DASHBOARD
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card overflow-hidden shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-body p-0">
                    <div class="bg-light border-bottom p-3">
                        <!-- Search Bar Row -->
                        <div class="row mb-3 justify-content-end">
                            <div class="col-md-4">
                                <form action="{{ route('public.complaints.index') }}" method="GET"
                                    class="d-flex gap-2 justify-content-end">
                                    <input type="hidden" name="status" value="{{ $status }}">
                                    <div class="input-group shadow-sm" style="max-width: 400px;min-height: 50px;">
                                        <input type="text" name="search" class="form-control border-0"
                                            placeholder="Search by ID, Title..." value="{{ request('search') }}">
                                        <button class="btn btn-primary border-0" type="submit"><i
                                                class="fas fa-search"></i></button>
                                        @if(request()->filled('search'))
                                        <a href="{{ route('public.complaints.index', ['status' => $status]) }}"
                                            class="btn btn-light border-0"><i class="fas fa-times text-danger"></i></a>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Tabs Row -->
                        <div class="row">
                            <div class="col-12">
                                <ul class="nav nav-tabs nav-fill border-0" id="complaintTabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link py-3 fw-bold {{ $status == 'pending' ? 'active bg-primary text-white' : '' }}"
                                            href="{{ route('public.complaints.index', array_merge(request()->all(), ['status' => 'pending'])) }}"
                                            style="background: {{ $status == 'pending' ? '#ffc107' : '#f8f9fa' }}; color: {{ $status == 'pending' ? '#000' : '#333' }};">
                                            RESPONSE NEEDED ({{ $counts['pending'] }})
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link py-3 fw-bold {{ $status == 'verification' ? 'active bg-info text-white' : '' }}"
                                            href="{{ route('public.complaints.index', array_merge(request()->all(), ['status' => 'verification'])) }}"
                                            style="background: {{ $status == 'verification' ? '#00bcd4' : '#f8f9fa' }}; color: {{ $status == 'verification' ? '#fff' : '#333' }};">
                                            WAITING FOR VERIFICATION ({{ $counts['verification'] }})
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link py-3 fw-bold {{ $status == 'solved' ? 'active bg-success text-white' : '' }}"
                                            href="{{ route('public.complaints.index', array_merge(request()->all(), ['status' => 'solved'])) }}"
                                            style="background: {{ $status == 'solved' ? '#4caf50' : '#f8f9fa' }}; color: {{ $status == 'solved' ? '#fff' : '#333' }};">
                                            CASE SOLVED SUCCESSFUL ({{ $counts['solved'] }})
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link py-3 fw-bold {{ $status == 'mercy' ? 'active bg-warning text-dark' : '' }}"
                                            href="{{ route('public.complaints.index', array_merge(request()->all(), ['status' => 'mercy'])) }}"
                                            style="background: {{ $status == 'mercy' ? '#f44336' : '#f8f9fa' }}; color: {{ $status == 'mercy' ? '#fff' : '#333' }};">
                                            MERCY ({{ $counts['mercy'] }})
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link py-3 fw-bold {{ $status == 'recovered' ? 'active bg-danger text-white' : '' }}"
                                            href="{{ route('public.complaints.index', array_merge(request()->all(), ['status' => 'recovered'])) }}"
                                            style="background: {{ $status == 'recovered' ? '#795548' : '#f8f9fa' }}; color: {{ $status == 'recovered' ? '#fff' : '#333' }};">
                                            LOSS RECOVERED ({{ $counts['recovered'] }})
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive p-4">
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
                                    <th>Timeline</th>
                                    <th>Managed By</th>
                                    <th>Specific Tag</th>
                                    <th>Employee Details</th>
                                    <th>Mail Sent</th>
                                    <th>Status</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($complaints as $index => $c)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><a href="{{ route('public.complaints.show', $c->id) }}"
                                            class="text-primary fw-bold">{{ $c->complaint_id }}</a></td>
                                    <td>
                                        <div class="fw-bold">{{ $c->complaint_user->name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $c->complaint_user->email ?? '' }}</small>
                                    </td>
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
                                    <td>{{ $c->delivery_timeline }}</td>
                                    <td class="text-uppercase small fw-bold">{{ $c->managed_by }}</td>
                                    <td>
                                        @if($c->specific_tag)
                                        <span class="badge bg-primary">Yes</span>
                                        @else
                                        <span class="badge bg-secondary">No</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($c->specific_tag)
                                        <div class="small">
                                            <strong>{{ $c->employee_name }}</strong><br>
                                            {{ $c->employee_email }}<br>
                                            {{ $c->employee_mobile }}
                                        </div>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td>
                                        @if($c->send_mail)
                                        <span class="text-success"><i class="fas fa-check-circle"></i> Yes</span>
                                        @else
                                        <span class="text-muted">No</span>
                                        @endif
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
</div>

<!-- Reply Modal -->
<div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="replyModalLabel">REPLY TO COMPLAINT - <span
                        id="modalTicketId"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form id="replyForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Your Response <span class="text-danger">*</span></label>
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
            var replyUrl = "{{ route('public.complaints.reply', ':id') }}";
            replyUrl = replyUrl.replace(':id', id);
            $('#replyForm').attr('action', replyUrl);
        });
    });
</script>
@endpush

@push('css')
<style>
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
</style>
@endpush