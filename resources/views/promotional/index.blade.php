@extends('layouts.master')

@section('title', __('Promotional Image Listing'))

@section('content')

<style>
    .max-content {
        width: max-content;
    }
</style>

<div class="card">
    <div class="card-header">
        <h3>Promotional Image Listing</h3>
    </div>

    <div class="card-body">

        <div class='row'>
            @include('components.filters')

            {{-- <form method="GET" action="" id="filterForm">
                <input type='hidden' name='status' value='{{ request()->status }}' />
                <div class="row mb-3">
                    <div class="col-md-2">
                        <label>Category</label>
                        <select class="form-control" name="category_id" id='category_id'>
                            <option value="">All</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $category_id==$cat->id?'selected':'' }}>
                                {{ $cat->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label>Sub Category</label>
                        <select class="form-control" name="sub_category_id" id='sub_category_id'>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label>Sub Sub Category</label>
                        <select class="form-control" name="sub_sub_category_id" id='sub_sub_category_id'>
                        </select>
                    </div>

                    <div class="col-md-2 d-flex align-items-end mt-2">
                        <button type="submit" class="btn btn-primary btn-block">Filter</button>
                    </div>

                </div>
            </form> --}}

        </div>

        <table class="table table-bordered table-striped table-responsive">
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
                    <th>URL</th>
                    <th>Created By</th>
                    <th>Created Date</th>
                    <th>Created Time</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($promotionalImage as $key => $row)
                <tr>
                    <td>
                        <div class="max-content">{{ $key + 1 }}</div>
                    </td>

                    <td>
                        <div class="max-content">{{ $row->batch_id }}</div>
                    </td>

                    <td>
                        <div class="max-content">{{ optional($row->category)->name }}</div>
                    </td>
                    <td>
                        <div class="max-content">{{ optional($row->subCategory)->name }}</div>
                    </td>
                    <td>
                        <div class="max-content">{{ optional($row->subSubCategory)->name }}</div>
                    </td>

                    <td>
                        <div class="max-content">{{ $row->title }}</div>
                    </td>

                    <td>
                        <div class="max-content">
                            @php
                                $shortText = Str::words($row->brief_description, 10, '...');
                            @endphp
                            <span data-bs-placement="top" data-bs-toggle="tooltip"
                                title="{{ $row->brief_description }}">
                                {{ $shortText ?? '-' }}
                            </span>
                        </div>
                    </td>

                    <td><div class="max-content">{{ $row->preferred_date ?? 'N/A' }}</div></td>

                    <td>
                        <div class="max-content">
                            @if($row->attach_image)
                                <img src="{{ asset('storage/' . $row->attach_image) }}" width="60">
                            @endif
                        </div>
                    </td>

                    <td>
                        <div class="max-content">
                            @if($row->attach_url)
                                <button class="btn btn-sm btn-primary"
                                    onclick="window.open('{{ $row->attach_url }}', '_blank')">
                                    View URL
                                </button>
                            @endif
                        </div>
                    </td>

                    <td>
                        <div class="max-content">{{ optional($row->creator)->name }}</div>
                    </td>

                    <td>
                        <div class="max-content">{{ $row->created_at->format('d-m-Y') }}</div>
                    </td>
                    <td>
                        <div class="max-content">{{ $row->created_at->format('h:i A') }}</div>
                    </td>

                    <td>
                        <div class="max-content">
                            <button class="btn btn-sm btn-primary"
                                onclick="openApproval('promotional', {{ $row->id }})">
                                Approve / Deny
                            </button>
                        </div>
                    </td>
                </tr>

                @empty
                <tr>
                    <td align="center" colspan="14">No Promotional Images Found.</td>
                </tr>
                @endforelse
            </tbody>

        </table>

         <div class="d-flex justify-content-end mt-3">
            {!! $promotionalImage->links('pagination::bootstrap-5') !!}

        <div class="d-flex justify-content-end mt-3">
            {!! $promotionalImage->links('pagination::bootstrap-5') !!}
        </div>
    </div>
</div>

@include('components.approval-form')

@endsection

@push('js')
@include('posts-script');


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