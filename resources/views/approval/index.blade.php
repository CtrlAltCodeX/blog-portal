@extends('layouts.master')

@section('title', 'Approval List')

@section('content')

<div class="container-fluid py-3">

    <!-- Tabs Header -->
    <ul class="nav nav-tabs mb-3" id="approvalTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active"
                id="content-tab"
                data-bs-toggle="tab"
                data-bs-target="#contentTab"
                type="button"
                role="tab">
                Content
            </button>
        </li>

        <li class="nav-item" role="presentation">
            <button class="nav-link"
                id="promo-tab"
                data-bs-toggle="tab"
                data-bs-target="#promoTab"
                type="button"
                role="tab">
                Promotional
            </button>
        </li>
    </ul>

    <!-- Tabs Content -->
    <div class="tab-content" id="approvalTabsContent">

        <!-- CONTENT TAB -->
        <div class="tab-pane fade show active" id="contentTab" role="tabpanel">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h4>Content Approval List</h4>
                </div>

                <div class="card-body">
                    <table class="table table-bordered table-striped">
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
                            @foreach($contents as $row)
                            <tr>
                                <td><span class="badge bg-primary">Content</span></td>

                                <td> {{ $row->batch_id }}</td>

                                <td>{{ $row->verifiedUser->name ?? '—' }}</td>

                                <td>{{ $row->verified_date ?? '—' }}</td>
                                <td>{{ $row->verified_time ?? '—' }}</td>

                                <td>{{ $row->title }}</td>

                                <td>{{ $row->rejection_cause ?? '—' }}</td>

                                <td>
                                    {{ $row->workType->cause ?? '' }}
                                    @if(isset($row->expected_amount))
                                    (₹{{ $row->expected_amount }})
                                    @endif
                                </td>

                                <td>{{ $row->expected_amount ?? '—' }}</td>
                                <td>{{ $row->content_report_note ?? '—' }}</td>
                                <td>{{ $row->host_record_note ?? '—' }}</td>
                                <td>
                                    @if($row->status == 'approved')
                                    <span class="badge bg-success">{{ ucfirst($row->status) }}</span>
                                    @elseif($row->status == 'denied')
                                    <span class="badge bg-danger">{{ ucfirst($row->status) }}</span>
                                    @else
                                    <span class="badge bg-secondary">{{ ucfirst($row->status ?? '—') }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <!-- PROMOTIONAL TAB -->
        <div class="tab-pane fade" id="promoTab" role="tabpanel">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h4>Promotional Approval List</h4>
                </div>

                <div class="card-body">
                    <table class="table table-bordered table-striped">
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
                            @foreach($promos as $row)
                            <tr>
                                <td><span class="badge bg-success">Promotional</span></td>
                                <td> {{ $row->batch_id }}</td>
                                <td>{{ $row->verifiedUser->name ?? '—' }}</td>

                                <td>{{ $row->verified_date ?? '—' }}</td>
                                <td>{{ $row->verified_time ?? '—' }}</td>
                                <td>{{ $row->title }}</td>
                                <td>{{ $row->rejection_cause ?? '—' }}</td>

                                <td>
                                    {{ $row->workType->cause ?? '' }}
                                    @if(isset($row->expected_amount))
                                    (₹{{ $row->expected_amount }})
                                    @endif
                                </td>

                                <td>{{ $row->expected_amount ?? '—' }}</td>
                                <td>{{ $row->content_report_note ?? '—' }}</td>
                                <td>{{ $row->host_record_note ?? '—' }}</td>
                                <td>
                                    @if($row->status == 'approved')
                                    <span class="badge bg-success">{{ ucfirst($row->status) }}</span>
                                    @elseif($row->status == 'denied')
                                    <span class="badge bg-danger">{{ ucfirst($row->status) }}</span>
                                    @else
                                    <span class="badge bg-secondary">{{ ucfirst($row->status ?? '—') }}</span>
                                    @endif
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

@endsection