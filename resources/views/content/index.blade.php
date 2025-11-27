@extends('layouts.master')
@section('title', __('Content Listing'))
@section('content')
<div class="card">
    <div class="card-header">
        <h3>Content List</h3>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Batch ID</th>
                    <th>Category</th>
                    <th>Sub-Category</th>
                    <th>Sub-Sub Category</th>
                    <th>Title</th>
                    <th>Brief Description</th>
                    <th>Preferred Date</th>
                    <th>Image</th>
                    <th>Document</th>
                    <th>URL</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th>Created Date</th>
                    <th>Created Time</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($contents as $key => $row)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $row->batch_id }}</td>
                    <td>{{ optional($row->categoryRelation)->name }}</td>
                    <td>{{ optional($row->subCategoryRelation)->name }}</td>
                    <td>{{ optional($row->subSubCategoryRelation)->name }}</td>

                    <td>{{ $row->title }}</td>
                    <td>{{ Str::limit($row->brief_description, 40) }}</td>
                    <td>{{ $row->preferred_date }}</td>

                    <td>
                        @if($row->attach_image)
                        <img src="{{ asset('storage/' . $row->attach_image) }}" width="50">
                        @endif
                    </td>

                    <td>
                        @if($row->attach_docs)
                        <a href="{{ asset('storage/' . $row->attach_docs) }}" target="_blank">View Doc</a>
                        @endif
                    </td>

                    <td>
                        @if($row->attach_url)
                        <a href="{{ $row->attach_url }}" target="_blank">{{ $row->attach_url }}</a>
                        @endif
                    </td>
                    <td>{{ $row->status}}</td>
                    <td>{{ optional($row->creator)->name }}</td>

                    <td>{{ $row->created_at->format('d-m-Y') }}</td>

                    <td>{{ $row->created_at->format('h:i A') }}</td>

                    <td>
                        <button class="btn btn-sm btn-primary"
                            onclick="openApproval('content', {{ $row->id }})">
                            Approve / Deny
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>
@include('components.approval-form')

@endsection


@push('js')
<script>
    function openApproval(type, id) {
        document.getElementById('itemType').value = type;
        document.getElementById('itemId').value = id;

        document.getElementById('approvalForm').action = "{{ route('approval.submit') }}";

        var approvalModal = new bootstrap.Modal(document.getElementById('approvalModal'));
        approvalModal.show();
    }
</script>

@endpush