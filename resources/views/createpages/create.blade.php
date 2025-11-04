@extends('layouts.master')

@section('title', __('Create Page Entry'))

@section('content')
<div class="main-container container-fluid">
    <div class="page-header">
        <h1 class="page-title">{{ __('Create Page Entry') }}</h1>
    </div>

    <form action="{{ route('createpages.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="card-title">Add New Entry</h4>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>

            <div class="card-body row">
                {{-- Select Category --}}
                <div class="form-group col-4">
                    <label>Select Category</label>
                    <select class="form-control" name="category_id" id="category_id" required>
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Select Sub-Category --}}
                <div class="form-group col-4">
                    <label>Select Sub-Category</label>
                    <select class="form-control" name="sub_category_id" id="sub_category_id">
                        <option value="">-- Select Sub-Category --</option>
                    </select>
                </div>

                {{-- Preferred Date --}}
                <div class="form-group col-2">
                    <label>Any Preferred Date?</label>
                    <select class="form-control" name="any_preferred_date" id="preferredDateSelect">
                        <option value="No">No</option>
                        <option value="Yes">Yes</option>
                    </select>
                </div>

                {{-- Date Field --}}
                <div class="form-group col-2" id="dateField" style="display:none;">
                    <label>Date</label>
                    <input type="date" class="form-control" name="date">
                </div>

                {{-- Upload --}}
                <div class="form-group col-4">
                    <label>Upload Attachment</label>
                    <input type="file" name="upload" class="form-control"
                           accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                </div>
            </div>
        </div>
    </form>
</div>

{{-- JS --}}
<script>
document.getElementById('preferredDateSelect').addEventListener('change', function() {
    document.getElementById('dateField').style.display = (this.value === 'Yes') ? 'block' : 'none';
});

document.getElementById('category_id').addEventListener('change', function() {
    let catId = this.value;
    let subs = @json($categories);
    let subSelect = document.getElementById('sub_category_id');
    subSelect.innerHTML = '<option value="">-- Select Sub-Category --</option>';
    subs.forEach(cat => {
        if (cat.id == catId && cat.children.length > 0) {
            cat.children.forEach(child => {
                subSelect.innerHTML += `<option value="${child.id}">${child.name}</option>`;
            });
        }
    });
});
</script>
@endsection
