@extends('layouts.master')

@section('title', __('MP Calculation Settings'))

@section('content')
<div class="card mt-5">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">{{ __('Market Place Calculation Settings (Uploader)') }}</h3>
        <a href="{{ route('marketplace.settings.sample') }}" class="btn btn-info btn-sm">
            <i class="fa fa-download"></i> Download Sample File
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('marketplace.settings.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="settings_file" class="form-label">{{ __('Upload Excel Chart') }}</label>
                <div class="row">
                    <div class="col-lg-4">
                        <input type="file" class="dropify" data-bs-height="180" id="settings_file" name="settings_file" required />
                    </div>
                </div>
            </div>
            <button class="btn btn-primary mt-3" type="submit">Upload and Update</button>
        </form>

        <hr class="my-5">

        <h4 class="mb-3">Current Settings Data</h4>
        <div class="table-responsive">
            <table class="table table-bordered text-center">
                <thead class="bg-primary text-white">
                    <tr>
                        <th class="text-white">Min Price</th>
                        <th class="text-white">Max Price</th>
                        <th class="text-white">Weight (gms)</th>
                        <th class="text-white">Courier Rate</th>
                        <th class="text-white">Packing Charge</th>
                        <th class="text-white">Min Profit</th>
                        <th class="text-white">Max Profit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($settings as $setting)
                        <tr>
                            <td>{{ $setting->min }}</td>
                            <td>{{ $setting->max }}</td>
                            <td>{{ $setting->weight }}</td>
                            <td>{{ $setting->courier_rate }}</td>
                            <td>{{ $setting->packing_charge }}</td>
                            <td>{{ $setting->our_min_profit }}</td>
                            <td>{{ $setting->max_profit }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">No data found. Please upload the excel file.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
<script src="{{ asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>
@endpush
