@extends('layouts.master')

@section('title', __('Create Page List'))

@section('content')
<div class="main-container container-fluid">
    <div class="page-header d-flex justify-content-between align-items-center">
        <h1 class="page-title">Create Page List</h1>
        <a href="{{ route('createpages.create') }}" class="btn btn-primary">+ New Entry</a>
    </div>

    {{-- STATUS SUMMARY --}}
    <div class="row mb-4">
        @foreach($stats as $label => $data)
        <div class="col-md-2 col-sm-6 mb-2">
            <div class="card text-center shadow-sm">
                <div class="card-body p-3">
                    <h6 class="fw-bold mb-1">{{ $label }}</h6>
                    <p class="mb-0">{{ $data['count'] }} ({{ $data['percent'] }}%)</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- LISTING TABLE --}}
    <div class="card">
        <div class="card-body table-responsive">
            <div class="mb-3">
                <button type="button" id="bulkEditBtn" class="btn btn-warning btn-sm">Bulk Edit</button>
                <button type="button" id="bulkDeleteBtn" class="btn btn-danger btn-sm">Bulk Delete</button>
            </div>

            <form id="bulkDeleteForm">
                <table class="table table-bordered table-striped align-middle">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>SL No.</th>
                            <th>Batch ID</th>
                            <th>Created Date</th>
                            <th>SLA</th>
                            <th>Created By</th>
                            <th>Category</th>
                            <th>Sub-Category</th>
                            <th>Preferred Date</th>
                            <th>Date</th>
                            <th>Attachment</th>
                            <th>Current Status</th>
                            <th>Official Remarks</th>
                            <th>Remarks Given By</th>
                            <th>Remarks Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pages as $page)
                        @php
                        $created = \Carbon\Carbon::parse($page->created_at);
                        $diffDays = $created->diffInDays(now());
                        $status = ucfirst($page->status);

                        // Dynamic status logic
                        if ($page->status == 'pending') {
                        if ($diffDays >= 7) {
                        $status = 'No Actions Taken (Auto Rejected)';
                        } elseif ($diffDays >= 3) {
                        $status = 'Last Day Action';
                        } else {
                        $status = 'Pending';
                        }
                        }

                        // SLA Countdown
                        $remaining = max(0, 3 - $diffDays);
                        @endphp
                        <tr>
                            <td><input type="checkbox" name="ids[]" value="{{ $page->id }}"></td>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $page->batch_id }}</td>
                            <td>{{ $created->format('d M Y') }}</td>
                            <td>
                                <span
                                    class="sla-timer text-success"
                                    data-created="{{ $page->created_at }}"
                                    data-id="{{ $page->id }}">
                                    Loading...
                                </span>
                            </td>
                            <td>{{ $page->user->name ?? 'N/A' }}</td>
                            <td>{{ $page->category->name ?? 'N/A' }}</td>
                            <td>{{ $page->subCategory->name ?? 'N/A' }}</td>
                            <td>{{ $page->any_preferred_date ?? '-' }}</td>
                            <td>{{ $page->date ?? '-' }}</td>
                            <td>
                                @if($page->upload)
                                <a href="{{ asset('storage/' . $page->upload) }}" target="_blank">View</a>
                                @else
                                -
                                @endif
                            </td>
                            <td>{{ $status }}</td>
                            <td>{{ $page->official_remark ?? '-' }}</td>
                            <td>{{ $page->remarks_user_id ? \App\Models\User::find($page->remarks_user_id)->name ?? '-' : '-' }}</td>
                            <td>{{ $page->remarks_date ? \Carbon\Carbon::parse($page->remarks_date)->format('d M Y') : '-' }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning single-edit" data-id="{{ $page->id }}">Edit</button>
                                <button type="button" class="btn btn-sm btn-danger single-delete" data-id="{{ $page->id }}">Delete</button>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-3">
                {!! $pages->links('pagination::bootstrap-5') !!}
            </div>
        </div>
    </div>
</div>



<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Update Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="editId">

                    <div class="mb-3">
                        <label class="form-label">Official Remark</label>
                        <textarea class="form-control" id="official_remark" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select id="status" class="form-select">
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="denied">Denied</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
@push('js')
<script>
    $(document).ready(function() {

        //Select all checkboxes
        $('#selectAll').on('change', function() {
            $('input[name="ids[]"]').prop('checked', this.checked);
        });


        $(document).on('click', '.single-edit', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            $('#editId').val(id);
            $('#official_remark').val('');
            $('#status').val('pending');
            $('#editModal').modal('show');
        });


        $(document).on('click', '.single-delete', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            if (!confirm('Are you sure you want to delete this record?')) return;

            $.ajax({
                url: `/admin/createpages/${id}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: res => {
                    if (res.success) {
                        alert(res.message);
                        location.reload();
                    } else {
                        alert('Failed to delete record.');
                    }
                },
                error: err => {
                    console.error(err);
                    alert('Error deleting record.');
                }
            });
        });


        $('#bulkDeleteBtn').click(function() {
            const ids = $('input[name="ids[]"]:checked').map(function() {
                return this.value;
            }).get();

            if (!ids.length) return alert('Select at least one record.');
            if (!confirm('Are you sure you want to delete selected records?')) return;

            $.ajax({
                url: '{{ route("createpages.deleteMultiple") }}',
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}',
                    ids
                },
                success: res => {
                    alert(res.message);
                    location.reload();
                }
            });
        });


        $('#bulkEditBtn').click(function() {
            const ids = $('input[name="ids[]"]:checked').map(function() {
                return this.value;
            }).get();

            if (!ids.length) return alert('Select at least one record.');
            $('#editId').val('bulk');
            $('#official_remark').val('');
            $('#status').val('pending');
            $('#editModal').modal('show');
        });


        $('#editForm').submit(function(e) {
            e.preventDefault();

            const ids = $('input[name="ids[]"]:checked').map(function() {
                return this.value;
            }).get();

            const editId = $('#editId').val();
            const isBulk = editId === 'bulk';

            const data = {
                _token: '{{ csrf_token() }}',
                ids: ids,
                official_remark: $('#official_remark').val(),
                status: $('#status').val(),
            };

            const url = isBulk ?
                '{{ route("createpages.updateMultiple") }}' :
                `/admin/createpages/${editId}/single-update`;

            $.post(url, data, function(res) {
                if (res.success) {
                    alert(res.message);
                    location.reload();
                } else {
                    alert('Failed to update.');
                }
            });
        });



        // SLA  Timer (3 days = 72 hours)
        function startSLACountdown() {
            const timers = document.querySelectorAll('.sla-timer');
            timers.forEach(el => {
                const createdAt = new Date(el.dataset.created);
                const deadline = new Date(createdAt.getTime() + (3 * 24 * 60 * 60 * 1000)); // +3 days

                function updateTimer() {
                    const now = new Date();
                    const diff = deadline - now;

                    if (diff <= 0) {
                        el.textContent = "Expired";
                        el.classList.remove("text-success");
                        el.classList.add("text-danger");
                        return;
                    }

                    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                    let display = '';
                    if (days > 0) display += `${days}d `;
                    if (hours > 0 || days > 0) display += `${hours}h `;
                    display += `${minutes}m ${seconds}s`;

                    el.textContent = display + " left";
                }

                updateTimer();
                setInterval(updateTimer, 1000);
            });
        }

        startSLACountdown();
    });
</script>
@endpush