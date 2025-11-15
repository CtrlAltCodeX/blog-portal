@extends('layouts.master')

@section('content')
<div class="container mt-4">

    <h3>Batch Details</h3>
    <hr>

    <div class="card p-4">

        <p><strong>Batch ID:</strong> {{ $page->batch_id }}</p>
        <p><strong>User:</strong> {{ $page->user ? $page->user->name : 'N/A' }}</p>
        <p><strong>Category:</strong> {{ $page->category ? $page->category->name : 'N/A' }}</p>
        <p><strong>Sub Category:</strong> {{ $page->subCategory ? $page->subCategory->name : 'N/A' }}</p>

        <p><strong>Status:</strong> {{ $page->status }}</p>

        <p><strong>Preferred Date:</strong> {{ $page->any_preferred_date }}</p>
        <p><strong>Date:</strong> {{ $page->date }}</p>
        <p><strong>URL:</strong> <a href="{{ $page->url }}" target="_blank">{{ $page->url }}</a> </p>

      @if($page->upload)
    <p><strong>Attachments:</strong></p>

    @php
        $files = explode(',', $page->upload);
    @endphp

    <div class="row">
        @foreach($files as $index => $file)
            <div class="col-md-3 col-6 mb-4">

                <div class="card shadow-sm p-2 text-center">

                    {{-- Image Preview (FULL image, NO CROP) --}}
                    @if(preg_match('/\.(jpg|jpeg|png|gif)$/i', $file))
                        <img src="{{ asset('storage/' . $file) }}"
                             class="img-fluid  mb-2"
                             style="
                                height: 180px; 
                                width: 100%;
                                object-fit: contain;
                                background: #f8f9fa;
                                border: 1px solid #ddd;
                                padding: 5px;
                             ">
                    @else
                        <img src="{{ asset('default-file.png') }}" 
                             class="img-fluid mb-2"
                             style="
                                height: 180px; 
                                width: 100%;
                                object-fit: contain;
                                background: #f8f9fa;
                                border: 1px solid #ddd;
                                padding: 5px;
                             ">
                    @endif

                    <p class="small text-muted mb-1">File {{ $index + 1 }}</p>

                    <a href="{{ asset('storage/' . $file) }}" 
                       target="_blank"
                       class="btn btn-sm btn-warning w-100 mb-1">
                       View
                    </a>

                    <a href="{{ asset('storage/' . $file) }}" 
                       download 
                       class="btn btn-sm btn-info w-100">
                       Download
                    </a>

                </div>

            </div>
        @endforeach
    </div>
@endif


        <p><strong>Official Remark:</strong> {{ $page->official_remark }}</p>
        <p><strong>Remarks By:</strong>  {{ $page->remarks_user_id ? \App\Models\User::find($page->remarks_user_id)->name ?? '-' : '-' }}</p>
        <p><strong>Remarks Date:</strong>  {{ $page->remarks_date ? \Carbon\Carbon::parse($page->remarks_date)->format('d M Y') : '-' }}</p>

    </div>

</div>
@endsection
