@extends('layouts.master')

@section('title', 'Manage Complaints')

@section('content')
<div class="row mt-4">
    <div class="col-12">
        <div class="card overflow-hidden">
            <div class="card-header">
                <h3 class="card-title">COMPLAINT MANAGEMENT</h3>
            </div>
            
            <div class="card-body p-0">
                <div class="bg-light border-bottom">
                    <ul class="nav nav-tabs nav-fill border-0" id="complaintTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link py-3 fw-bold {{ $status == 'pending' ? 'active bg-primary text-white' : '' }}" href="{{ route('admin.complaints.index', ['status' => 'pending']) }}" style="background: {{ $status == 'pending' ? '#ffc107' : '#f8f9fa' }}; color: {{ $status == 'pending' ? '#000' : '#333' }};">
                                RESPONSE NEEDED ({{ $counts['pending'] }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link py-3 fw-bold {{ $status == 'verification' ? 'active bg-info text-white' : '' }}" href="{{ route('admin.complaints.index', ['status' => 'verification']) }}" style="background: {{ $status == 'verification' ? '#00bcd4' : '#f8f9fa' }}; color: {{ $status == 'verification' ? '#fff' : '#333' }};">
                                WAITING FOR VERIFICATION ({{ $counts['verification'] }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link py-3 fw-bold {{ $status == 'solved' ? 'active bg-success text-white' : '' }}" href="{{ route('admin.complaints.index', ['status' => 'solved']) }}" style="background: {{ $status == 'solved' ? '#4caf50' : '#f8f9fa' }}; color: {{ $status == 'solved' ? '#fff' : '#333' }};">
                                CASE SOLVED SUCCESSFUL ({{ $counts['solved'] }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link py-3 fw-bold {{ $status == 'mercy' ? 'active bg-warning text-dark' : '' }}" href="{{ route('admin.complaints.index', ['status' => 'mercy']) }}" style="background: {{ $status == 'mercy' ? '#f44336' : '#f8f9fa' }}; color: {{ $status == 'mercy' ? '#fff' : '#333' }};">
                                MERCY ({{ $counts['mercy'] }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link py-3 fw-bold {{ $status == 'recovered' ? 'active bg-danger text-white' : '' }}" href="{{ route('admin.complaints.index', ['status' => 'recovered']) }}" style="background: {{ $status == 'recovered' ? '#795548' : '#f8f9fa' }}; color: {{ $status == 'recovered' ? '#fff' : '#333' }};">
                                LOSS RECOVERED ({{ $counts['recovered'] }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link py-3 fw-bold {{ $status == 'all' ? 'active bg-dark text-white' : '' }}" href="{{ route('admin.complaints.index', ['status' => 'all']) }}" style="background: {{ $status == 'all' ? '#9e8d5e' : '#f8f9fa' }}; color: #fff;">
                                ALL ({{ $counts['all'] }})
                            </a>
                        </li>
                    </ul>
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
                                <th>Status</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($complaints as $index => $c)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><a href="{{ route('admin.complaints.show', $c->id) }}" class="text-primary fw-bold">{{ $c->complaint_id }}</a></td>
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
                                            data-bs-toggle="modal" 
                                            data-bs-target="#replyModal"
                                            data-id="{{ $c->id }}"
                                            data-ticket="{{ $c->complaint_id }}"
                                            data-status="{{ $c->status }}">
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
                <h5 class="modal-title fw-bold" id="replyModalLabel">TAKE ACTION / REPLY - <span id="modalTicketId"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="replyForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Response Message <span class="text-danger">*</span></label>
                        <textarea name="message" class="form-control" rows="5" placeholder="Enter reply message..." required></textarea>
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
                    <button type="button" class="btn btn-secondary px-4 rounded-pill" data-bs-dismiss="modal">CANCEL</button>
                    <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold">SUBMIT RESPONSE</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('.verify-btn').on('click', function() {
            var id = $(this).data('id');
            var ticket = $(this).data('ticket');
            var status = $(this).data('status');

            $('#modalTicketId').text(ticket);
            $('#modalStatusSelect').val(status);
            
            // Generate exact reply URL dynamically
            var replyUrl = "{{ route('admin.complaints.reply', ':id') }}";
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
