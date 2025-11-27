@extends('layouts.master')

@section('title', 'Create Promotional Image')

@section('content')

<div class="container-fluid py-3">


    <div class="card">
        <div class="card-header">
            <h3>Create Promotional Images</h3>
        </div>

        <div class="card-body">

            <form action="{{ route('promotional.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <table class="table table-bordered" id="promo-table">
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
                            <th>URL</th>
                            <th>#</th>
                        </tr>
                    </thead>

                    <tbody id="promotional-body">
                        @include('components.single-row', ['index' => 0, 'showDocs' => false])
                    </tbody>

                </table>

                <button type="button" class="btn btn-primary btn-sm" id="add-promotional-row">+ Add Row</button>

                <button class="btn btn-success float-end">SAVE ALL</button>
            </form>
        </div>
    </div>
</div>

@endsection

@push('js')
@include('contents-script')

@endpush