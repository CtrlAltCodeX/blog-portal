@extends('layouts.master')

@section('title', __('Backup Logs'))

@section('content')
<div class="main-container container-fluid">
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-9">
                        <div class="d-flex align-items-center" style="grid-gap:15px;">
                            <input type="text" disabled class="form-control" value="{{ url('/') }}/storage/merchant-file.xlsx" />
                            <img src="/copy.png" width="25" title="Copy URL" class="copy" id="{{ url('/') }}/storage/merchant-file.xlsx" />
                            <a href="{{ route('backup.export', ['file' => 1, 'type' => 'google']) }}" class="btn btn-primary w-25">Merchant File</a>
                            <a href="{{ route('backup.export', ['file' => 1, 'type' => 'facebook']) }}" class="btn btn-primary w-25">Facebook File</a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('backup.export', ['file' => 1, 'type' => 'facebook']) }}" class="mr-2 btn btn-primary">Export File (DB)</a>
                        <!-- <a href="{{ route('manually.backup') }}" class="mr-2 btn btn-primary">Manual Backup</a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-5">
        <div class="card-body">
            {!! nl2br($logContent) !!}
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
    $(document).ready(function() {
        $(".copy").click(function() {
            navigator.clipboard.writeText($(this).attr('id'));
            alert('Copied');
        })
    })
</script>
@endpush