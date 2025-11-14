@extends('layouts.master')

@section('title', __('Create Page List'))

@section('content')
<style>
    .fit {
        width: max-content;        
    }

      .status-tabs {
        background: #fff;
        border-radius: 8px;
        padding: 0.75rem;
    }

    .status-tabs .nav-link {
        border: none;
        margin: 4px 6px;
        border-radius: 6px;
        padding: 10px 18px;
        color: #495057;
        background-color: #f8f9fa;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-weight: 500;
    }

    .status-tabs .nav-link:hover {
        background-color: #e9ecef;
        transform: translateY(-1px);
    }

    .status-tabs .nav-link.active {
        background-color: #0d6efd;
        color: #fff;
        box-shadow: 0 3px 6px rgba(13, 110, 253, 0.3);
    }

    .status-tabs .nav-link.active .badge {
        background: rgba(255, 255, 255, 0.25);
    }

    .status-tabs .badge {
        font-size: 0.8rem;
    }

    .status-tabs small {
        font-size: 0.75rem;
        opacity: 0.85;
    }
</style>

<div class="main-container container-fluid">
    <div class="card">
        <div class='card-header'>
            <div class="page-header d-flex justify-content-between align-items-center my-0 w-100">
                <h1 class="page-title">Post List</h1>
                <a href="{{ route('posts.create') }}" class="btn btn-primary">Create New</a>
            </div>
        </div>

        <div class="card-body table-responsive">
            <div class="status-tabs">
                <ul class="nav nav-pills justify-content-around flex-wrap">
                    @foreach($stats as $key => $data)
                        <li class="nav-item">
                            <a class="nav-link {{ $statusFilter === $key ? 'active' : '' }}"
                            href="{{ route('posts.index', ['status' => $key]) }}">
                                <span>{{ $data['label'] }}</span>
                                <span class="badge bg-secondary">{{ $data['count'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            
            <div class='row'>
                <div class='col-md-4'>
                    <div class="mb-3">
                        <div class='d-flex' style="grid-gap: 10px;">
                            <select id='dropdown' class='form-control'>
                                <option value="">Select</option>
                                <option value=1>Bulk Edit</option>
                                <option value=2>Bulk Delete</option>
                            </select>
                            <button class='btn btn-primary w-25' id='submit_btn' >Submit</button>
                        </div>
                    </div>
                </div>
            </div>

            <form id="bulkDeleteForm">
                <table class="table table-bordered table-striped align-middle table-responsive">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>
                                <div class='fit'>
                                    SL No. / Batch ID    
                                </div>
                            </th>
                            <th>
                                <div class='fit'>
                                    Created At / By    
                                </div>
                            </th>
                            <!--<th>Created Date</th>-->
                            <th>SLA</th>
                            <!--<th>Created By</th>-->
                            <th>
                                <div class='fit'>
                                    Category / Sub-Category    
                                </div>
                            </th>
                            <!--<th></th>-->
                            <th>
                                <div class='fit'>
                                    Preferred / Date
                                </div>
                            </th>
                            <!--<th>Date</th>-->
                            <!--<th>Attachment</th>-->
                            <th>
                                <div class='fit'>
                                    Current Status    
                                </div>
                            </th>
                            <th>Official Remarks</th>
                            <th>
                                <div class='fit'>
                                    Remarks By / Date        
                                </div>
                            </th>
                            <!--<th>Remarks Date</th>-->
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pages as $page)
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
                            <td>
                                <div class='fit'>
                                    {{ $loop->iteration }} / {{ $page->batch_id }}
                                </div>
                            </td>
                            <td>
                                <div class='fit'>
                                    {{ $created->format('d M Y') }} /
                                    {{ $page->user->name ?? 'N/A' }}
                                </div>
                            </td>
                            <!--<td>{{ $created->format('d M Y') }}</td>-->
                            <td>
                                <span
                                    class="sla-timer text-success"
                                    data-created="{{ $page->created_at }}"
                                    data-id="{{ $page->id }}"
                                    style='display:block; width:130px;'>
                                    Loading...
                                </span>
                            </td>
                            <!--<td>{{ $page->user->name ?? 'N/A' }}</td>-->
                            <td>
                                <div class='fit'>
                                    {{ $page->category->name ?? 'N/A' }} / {{ $page->subCategory->name ?? 'N/A' }}    
                                </div>
                            </td>
                            <!--<td></td>-->
                            <td>
                                <div class='fit'>
                                    {{ $page->any_preferred_date ?? '-' }} / {{ $page->date ?? '-' }}    
                                </div>
                            </td>
                            <!--<td>{{ $page->date ?? '-' }}</td>-->
                            <!--<td>-->
                            <!--    @if($page->upload)-->
                            <!--    <a href="{{ asset('storage/' . $page->upload) }}" target="_blank">View</a>-->
                            <!--    @else-->
                            <!--    --->
                            <!--    @endif-->
                            <!--</td>-->
                            <td>{{ $status }}</td>
                            <td>
                                <div class='fit'>
                                    @php
                                        $shortText = Str::words($page->official_remark, 10, '...');
                                    @endphp
                                    <span data-bs-placement="top" data-bs-toggle="tooltip" title="{{ $page->official_remark }}">
                                        {{ $shortText ?? '-' }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class='fit'>
                                    {{ $page->remarks_user_id ? \App\Models\User::find($page->remarks_user_id)->name ?? '-' : '-' }} / 
                                    {{ $page->remarks_date ? \Carbon\Carbon::parse($page->remarks_date)->format('d M Y') : '-' }}
                                </div>
                            </td>
                            <!--<td>-->
                            <!--    <div class='fit'>-->
                            <!--        {{ $page->remarks_date ? \Carbon\Carbon::parse($page->remarks_date)->format('d M Y') : '-' }}    -->
                            <!--    </div>-->
                            <!--</td>-->
                            <td class='d-flex' style='grid-gap: 10px;'>
                                @if($page->upload)
                                <a href="{{ asset('storage/' . $page->upload) }}" target="_blank" class='btn btn-sm btn-warning'>View attachment</a>
                                @endif
                                <button type="button" class="btn btn-sm btn-warning single-edit" data-id="{{ $page->id }}">Edit</button>
                                <button type="button" class="btn btn-sm btn-danger single-delete" data-id="{{ $page->id }}">Delete</button>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="16" class="text-center">{{ __('No records found.') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </form>

            <div class="d-flex justify-content-center mt-3">
                {!! $pages->links('pagination::bootstrap-5') !!}
            </div>
        </div>
    </div>
</div>


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
                url: `/admin/posts/${id}`,
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

        $('#submit_btn').click(function () {
            var bulkEdit = $("#dropdown").val();
            if (bulkEdit == 2) {
                const ids = $('input[name="ids[]"]:checked').map(function() {
                    return this.value;
                }).get();
    
                if (!ids.length) return alert('Select at least one record.');
                if (!confirm('Are you sure you want to delete selected records?')) return;
    
                $.ajax({
                    url: '{{ route("posts.bulk.delete") }}',
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
            } else if (bulkEdit == 1) {
                const ids = $('input[name="ids[]"]:checked').map(function() {
                    return this.value;
                }).get();
    
                if (!ids.length) return alert('Select at least one record.');
                $('#editId').val('bulk');
                $('#official_remark').val('');
                $('#status').val('pending');
                $('#editModal').modal('show');
            }
        })

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
                '{{ route("posts.bulk.update") }}' :
                `/admin/posts/${editId}/single-update`;

            $.post(url, data, function(res) {
                if (res.success) {
                    alert(res.message);
                    location.reload();
                } else {
                    alert('Failed to update.');
                }
            });
        });

        function startSLACountdown() {
            const timers = document.querySelectorAll('.sla-timer');
            timers.forEach(el => {
                const createdAt = new Date(el.dataset.created);
                const deadline = new Date(createdAt.getTime() + (3 * 24 * 60 * 60 * 1000)); // +3 days

                function updateTimer() {
                    const now = new Date();
                    const diff = deadline - now;

                    if (diff <= 0) {
                        el.textContent = "SLA Breached";
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