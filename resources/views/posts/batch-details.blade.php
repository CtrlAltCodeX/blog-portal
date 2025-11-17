@extends('layouts.master')

@section('content')
<div class="container mt-4">

    <h3>Batch Details</h3>
    <hr>

    <div class="card p-4">

        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th width="30%">Batch ID</th>
                    <td>{{ $page->batch_id }}</td>
                </tr>

                <tr>
                    <th>User</th>
                    <td>{{ $page->user ? $page->user->name : 'N/A' }}</td>
                </tr>

                <tr>
                    <th>Category</th>
                    <td>{{ $page->category ? $page->category->name : 'N/A' }}</td>
                </tr>

                <tr>
                    <th>Sub Category</th>
                    <td>{{ $page->subCategory ? $page->subCategory->name : 'N/A' }}</td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>{{ $page->status }}</td>
                </tr>

                <tr>
                    <th>Preferred Date</th>
                    <td>{{ $page->any_preferred_date }}</td>
                </tr>

                <tr>
                    <th>Date</th>
                    <td>{{ $page->date }}</td>
                </tr>

                <tr>
                    <th>File</th>
                    <td>
                        @foreach ($downloadableUrls as $url)
                            <a class="btn btn-primary btn-sm" href="{{ $url }}" target="_blank">
                                Download File
                            </a>
                        @endforeach
                    </td>
                </tr>

                <tr>
                    <th>Official Remark</th>
                    <td>{{ $page->official_remark }}</td>
                </tr>

                <tr>
                    <th>Remarks By</th>
                    <td>{{ $page->remarks_user_id ? \App\Models\User::find($page->remarks_user_id)->name ?? '-' : '-' }}</td>
                </tr>

                <tr>
                    <th>Remarks Date</th>
                    <td>{{ $page->remarks_date ? \Carbon\Carbon::parse($page->remarks_date)->format('d M Y') : '-' }}</td>
                </tr>
            </tbody>
        </table>

        {{-- Attachments Section --}}
        @if($page->upload)
        <h5 class="mt-4">Attachments</h5>

        @php $files = explode(',', $page->upload); @endphp

        <div class="row mt-3">
            @foreach($files as $index => $file)
                @php $image = explode('/', $file); @endphp

                <div class="col-md-3 col-6 mb-4">
                    <div class="card shadow-sm p-2 text-center">

                        {{-- Image Preview --}}
                        @if(preg_match('/\.(jpg|jpeg|png|gif)$/i', $file))
                            <img src="{{ route('assets', $image[2]) }}"
                                class="img-fluid mb-2"
                                style="height:180px; width:100%; object-fit:contain; background:#f8f9fa; border:1px solid #ddd; padding:5px;">
                        @else
                            <img src="{{ asset('default-file.png') }}"
                                class="img-fluid mb-2"
                                style="height:180px; width:100%; object-fit:contain; background:#f8f9fa; border:1px solid #ddd; padding:5px;">
                        @endif

                        <p class="small text-muted mb-1">File {{ $index + 1 }}</p>

                        <a href="{{ route('assets', $image[2]) }}"
                            target="_blank"
                            class="btn btn-sm btn-warning w-100 mb-1">View</a>

                        <a href="{{ route('assets', $image[2]) }}"
                            download
                            class="btn btn-sm btn-info w-100">Download</a>
                    </div>
                </div>

            @endforeach
        </div>
        @endif

    </div>
</div>
@endsection
