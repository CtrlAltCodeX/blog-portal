@extends('layouts.master')

@section('title', 'Create Content')

@section('content')
<style>
    .table th,
    .table td {
        width: fit-content;
    }

    select.form-control {
        width: 120px;
    }

    input.form-control {
        width: 150px;
    }

    textarea.form-control {
        width: 200px;
    }
</style>

<div class="container-fluid py-3">
    <div class="card">
        <div class="card-header">
            <h3>Create Content</h3>
        </div>

        <div class="card-body">
            <form action="{{ route('content.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <table class="table table-bordered table-responsive" id="content-table">
                    <thead>
                        <tr>
                            <th>Category*</th>
                            <th>Sub Category*</th>
                            <th>Sub Sub Category*</th>
                            <th>Title*</th>
                            <th>Description</th>
                            <th>Preferred?</th>
                            <th>Date</th>
                            <th>Image</th>
                            <th>Docs</th>
                            <th>URL</th>
                            <th>#</th>
                        </tr>
                    </thead>

                    <tbody id="content-body">
                        @include('components.single-row', ['index' => 0, 'showDocs' => true])
                    </tbody>
                </table>
                @if(empty($disableAddMore))
                <button type="button" class="btn btn-sm btn-primary" id="add-row">+ Add Row</button>
                @endif
                @if(!empty($page))
                <input type="hidden" name="page_batch_id" value="{{ $page->batch_id }}">
                <input type="hidden" name="page_id" value="{{ $page->id }}">
                @endif

                <button class="btn btn-success float-end">SAVE ALL</button>
            </form>

        </div>
    </div>

</div>

@endsection

@push('js')
@include('contents-script')

@endpush