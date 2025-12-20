@extends('layouts.master')

@section('title', 'Approval List')

@section('content')

<style>
    .table th,
    .table td {
        width: fit-content;
    }

    .max-content {
        width: max-content;
    }
</style>

<div class="card">
    <div class="card-header">
        <h4>Approval List</h4>
    </div>
    <div class="card-body">
        <form method="GET" class="mb-3">
            <input type="hidden" name="tab" id="activeTabInput" value="{{ $activeTab }}">

            <select name="status" onchange="this.form.submit()" class="form-select" style="width:400px;height:50px">
                <option value="">Filter By Status</option>
                <option value="all" {{ ($status=='all') ? 'selected' : '' }}>All</option>
                <option value="approved" {{ ($status=='approved') ? 'selected' : '' }}>Approved</option>
                <option value="denied" {{ ($status=='denied') ? 'selected' : '' }}>Denied</option>
            </select>
        </form>

        <div class="container-fluid py-3">


            <!-- Tabs Header -->
            <ul class="nav nav-tabs mb-3" id="approvalTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab == 'content-tab' ? 'active' : 'active' }}"
                        id="content-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#contentTab"
                        type="button"
                        role="tab">
                        Content
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab == 'promo-tab' ? 'active' : '' }}"
                        <button class="nav-link {{ $activeTab == 'promo-tab' ? 'active' : 'active' }}"
                        id="promo-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#promoTab"
                        type="button"
                        role="tab">
                        Promotional
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="approvalTabsContent">
                <div class="tab-pane fade show {{ $activeTab == 'content-tab' ? 'show active' : 'show active' }}" id="contentTab" role="tabpanel">
                    <table class="table table-bordered table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Batch ID</th>
                                <th>Verified By</th>
                                <th>Verified Date</th>
                                <th>Verified Time</th>
                                <th>Title</th>
                                <th>Cause of Rejection</th>
                                <th>Work Type & Value</th>
                                <th>Expected Amount</th>
                                <th>Content Report Note</th>
                                <th>Host Record Note</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($contents as $row)
                            <tr>
                                <td>
                                    <div class="max-content">
                                        <span class="badge bg-primary">Content</span>
                                    </div>
                                </td>

                                <td>
                                    <div class="max-content">
                                        {{ $row->batch_id }}
                                    </div>
                                </td>

                                <td>
                                    <div class="max-content">
                                        {{ $row->verifiedUser->name ?? '—' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="max-content">
                                        {{ $row->verified_date ?? '—' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="max-content">
                                        {{ $row->verified_time ?? '—' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="max-content">
                                        {{ $row->title }}
                                    </div>
                                </td>

                                <td>
                                    <div class="max-content">
                                        {{ $row->rejection_cause ?? '—' }}
                                    </div>
                                </td>

                                <td>
                                    <select class="form-select worktype-select"
                                        data-id="{{ $row->id }}"
                                        data-type="content"
                                        style="width:150px">
                                        <option value="">Select</option>
                                        @foreach($worktypes as $w)
                                        <option value="{{ $w->id }}"
                                            data-amount="{{ $w->amount }}"
                                            {{ $row->worktype_id == $w->id ? 'selected' : '' }}>
                                            {{ $w->cause }}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>

                                <td>
                                    <input type="number"
                                        class="form-control expected-amount"
                                        data-id="{{ $row->id }}"
                                        data-type="content"
                                        value="{{ $row->expected_amount }}"
                                        readonly
                                        style="width:120px">
                                </td>

                                <td>
                                    <input type="text"
                                        class="form-control content-note"
                                        data-id="{{ $row->id }}"
                                        data-type="content"
                                        value="{{ $row->content_report_note }}"
                                        style="width:200px">
                                    <button class="btn btn-sm btn-success mt-1 save-content-note"
                                        data-id="{{ $row->id }}">Save</button>
                                </td>

                                <td>
                                    <input type="text"
                                        class="form-control host-note"
                                        data-id="{{ $row->id }}"
                                        data-type="content"
                                        value="{{ $row->host_record_note }}"
                                        style="width:200px">
                                    <button class="btn btn-sm btn-success mt-1 save-host-note"
                                        data-id="{{ $row->id }}">Save</button>
                                </td>

                                <td>
                                    <div class="max-content">
                                        @if($row->status == 'approved')
                                        <span class="badge bg-success">{{ ucfirst($row->status) }}</span>
                                        @elseif($row->status == 'denied')
                                        <span class="badge bg-danger">{{ ucfirst($row->status) }}</span>
                                        @else
                                        <span class="badge bg-secondary">{{ ucfirst($row->status ?? '—') }}</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="12" class="text-center">No content approvals found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="tab-pane fade {{ $activeTab == 'promo-tab' ? 'show active' : '' }}" id="promoTab" role="tabpanel">
                    <table class="table table-bordered table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Batch ID</th>
                                <th>Verified By</th>
                                <th>Verified Date</th>
                                <th>Verified Time</th>
                                <th>Title</th>
                                <th>Cause of Rejection</th>
                                <th>Work Type & Value</th>
                                <th>Expected Amount</th>
                                <th>Content Report Note</th>
                                <th>Host Record Note</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($promos as $row)
                            <tr>
                                <td>
                                    <div class="max-content">
                                        <span class="badge bg-success">Promotional</span>
                                    </div>
                                </td>

                                <td>
                                    <div class="max-content">
                                        {{ $row->batch_id }}
                                    </div>
                                </td>

                                <td>
                                    <div class="max-content">
                                        {{ $row->verifiedUser->name ?? '—' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="max-content">
                                        {{ $row->verified_date ?? '—' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="max-content">
                                        {{ $row->verified_time ?? '—' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="max-content">
                                        {{ $row->title }}
                                    </div>
                                </td>

                                <td>
                                    <div class="max-content">
                                        {{ $row->rejection_cause ?? '—' }}
                                    </div>
                                </td>

                                <td>
                                    <select class="form-select worktype-select"
                                        data-id="{{ $row->id }}"
                                        data-type="promotional"
                                        style="width:150px">
                                        <option value="">Select</option>
                                        @foreach($worktypes as $w)
                                        <option value="{{ $w->id }}"
                                            data-amount="{{ $w->amount }}"
                                            {{ $row->worktype_id == $w->id ? 'selected' : '' }}>
                                            {{ $w->cause }}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>

                                <td>
                                    <input type="number"
                                        class="form-control expected-amount"
                                        data-id="{{ $row->id }}"
                                        data-type="promotional"
                                        value="{{ $row->expected_amount }}"
                                        readonly
                                        style="width:120px">
                                </td>

                                <td>
                                    <input type="text"
                                        class="form-control content-note"
                                        data-id="{{ $row->id }}"
                                        data-type="promotional"
                                        value="{{ $row->content_report_note }}"
                                        style="width:200px">
                                    <button class="btn btn-sm btn-success mt-1 save-content-note"
                                        data-id="{{ $row->id }}">Save</button>
                                </td>

                                <td>
                                    <input type="text"
                                        class="form-control host-note"
                                        data-id="{{ $row->id }}"
                                        data-type="promotional"
                                        value="{{ $row->host_record_note }}"
                                        style="width:200px">
                                    <button class="btn btn-sm btn-success mt-1 save-host-note"
                                        data-id="{{ $row->id }}">Save</button>
                                </td>

                                <td>
                                    <div class="max-content">
                                        @if($row->status == 'approved')
                                        <span class="badge bg-success">{{ ucfirst($row->status) }}</span>
                                        @elseif($row->status == 'denied')
                                        <span class="badge bg-danger">{{ ucfirst($row->status) }}</span>
                                        @else
                                        <span class="badge bg-secondary">{{ ucfirst($row->status ?? '—') }}</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="12" class="text-center">No promotional approvals found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
    function showSuccess(msg) {
        let div = document.createElement("div");
        div.className = "alert alert-success";
        div.style.position = "fixed";
        div.style.top = "20px";
        div.style.left = "50%";
        div.style.transform = "translateX(-50%)";
        div.style.padding = "8px 20px";
        div.style.fontSize = "15px";
        div.style.borderRadius = "6px";
        div.style.boxShadow = "0 2px 8px rgba(0,0,0,0.2)";
        div.style.zIndex = "9999";
        div.style.opacity = "0";
        div.style.transition = "opacity 0.4s ease";
        div.innerText = msg;

        document.body.appendChild(div);

        // Fade in
        setTimeout(() => {
            div.style.opacity = "1";
        }, 50);

        // Fade out + remove
        setTimeout(() => {
            div.style.opacity = "0";
            setTimeout(() => div.remove(), 400);
        }, 2000);
    }



    document.querySelectorAll('#approvalTabs button').forEach(btn => {
        btn.addEventListener('shown.bs.tab', function() {
            document.getElementById('activeTabInput').value = this.id;
        });
    });

    // Work Type change → update expected amount + save
    $(document).on('change', '.worktype-select', function() {
        let id = $(this).data('id');
        let type = $(this).data('type');
        let amount = $(this).find(':selected').data('amount');

        // update expected amount input
        $(this).closest('tr').find('.expected-amount').val(amount);

        // save worktype
        $.post("{{ route('approval.quick.update') }}", {
            id: id,
            type: type,
            field: 'worktype_id',
            value: $(this).val(),
            _token: "{{ csrf_token() }}"
        }, function() {
            showSuccess("Work type updated!");
        });

        // save expected amount
        $.post("{{ route('approval.quick.update') }}", {
            id: id,
            type: type,
            field: 'expected_amount',
            value: amount,
            _token: "{{ csrf_token() }}"
        });
    });

    // Save Content Note
    $(document).on('click', '.save-content-note', function() {
        let id = $(this).data('id');
        let type = $(this).closest('tr').find('.content-note').data('type');
        let val = $(this).closest('td').find('.content-note').val();

        $.post("{{ route('approval.quick.update') }}", {
            id: id,
            type: type,
            field: 'content_report_note',
            value: val,
            _token: "{{ csrf_token() }}"
        }, function() {
            showSuccess("Content note updated!");
        });
    });

    // Save Host Note
    $(document).on('click', '.save-host-note', function() {
        let id = $(this).data('id');

        let type = $(this).closest('tr').find('.content-note').data('type');
        let val = $(this).closest('td').find('.host-note').val();

        $.post("{{ route('approval.quick.update') }}", {
            id: id,
            type: type,
            field: 'host_record_note',
            value: val,
            _token: "{{ csrf_token() }}"
        }, function() {
            showSuccess("Host note updated!");
        });
    });
</script>
@endpush