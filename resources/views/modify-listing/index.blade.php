@extends('layouts.master')

@section('title', __('Request to Modify'))

@push('css')
<style>
    .table-responsive {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
    }
    .fetch-btn {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 4px;
    }
    .add-row-btn {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 50%;
        font-size: 20px;
        margin-top: 20px;
    }
    .save-btn {
        background-color: #ffc107;
        color: black;
        font-weight: bold;
        padding: 10px 30px;
        border: none;
        border-radius: 4px;
        margin-top: 20px;
    }
    .top-actions {
        /*margin-bottom: 20px;*/
        display: flex;
        gap: 10px;
    }
</style>
@endpush

@section('content')
<div class="main-container container-fluid">
    <!--<div class="page-header">-->
    <!--    <h1 class="page-title">{{ __('Modify Listing') }}</h1>-->
    <!--</div>-->


    <div class="card">
        <div class="card-header justify-content-between">
            <h1 class="page-title">{{ __('Request to Modify ( Exchange or Update )') }}</h1>
            
            <div class="top-actions">
                <a href="{{ route('modify-listing.sample') }}" class="btn btn-primary"><i class="fa fa-download"></i> Download Sample File</a>
                <input type="file" id="bulkUpload" style="display: none;" accept=".csv,.xlsx">
                <button class="btn btn-success" onclick="$('#bulkUpload').click()"><i class="fa fa-upload"></i> Upload Excel</button>
            </div>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="modifyTable">
                    <thead>
                        <tr class="bg-light">
                            <th>SL.</th>
                            <th style="width: 200px;">PRODUCT ID</th>
                            <th>IMAGE</th>
                            <th>Request type <span class='text-danger'>*</span></th>
                            <th>PUBLISHER</th>
                            <th>BOOK NAME</th>
                            <th>MRP.</th>
                            <th>SELLING</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="data-row">
                            <td class="sl-no">1</td>
                            <td>
                                <div class="input-group">
                                    <input type="text" class="form-control product-id" placeholder="ID">
                                    <button class="btn btn-primary fetch-btn" type="button">Fetch</button>
                                </div>
                            </td>
                            <td class="image-val">
                                <a href="#" target="_blank" class="img-link">
                                    <img src="{{ asset('assets/images/no-image.png') }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;" class="prod-img">
                                </a>
                            </td>
                            <td>
                                <select class="form-control category-select" required>
                                    <option value="" selected>Select Category</option>
                                    <option value="Exchange with Others">Exchange with Others</option>
                                    <option value="Update To Latest" >Update To Latest</option>
                                </select>
                            </td>
                            <td class="publisher-val">-</td>
                            <td class="book-name-val">-</td>
                            <td class="mrp-val">-</td>
                            <td class="selling-val">-</td>
                            <td>
                                <button class="btn btn-danger delete-row" type="button"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <div class="d-flex justify-content-between align-items-center">
                    <button class="add-row-btn" id="addRow" type="button"><i class="fa fa-plus"></i></button>
                    <button class="save-btn" id="saveAll" type="button">SAVE & REQUEST TO UPDATE</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    // Add Row
    $('#addRow').click(function() {
        let rowCount = $('#modifyTable tbody tr').length + 1;
        let newRow = `
            <tr class="data-row">
                <td class="sl-no">${rowCount}</td>
                <td>
                    <div class="input-group">
                        <input type="text" class="form-control product-id" placeholder="ID">
                        <button class="btn btn-primary fetch-btn" type="button">Fetch</button>
                    </div>
                </td>
                <td class="image-val">
                    <a href="#" target="_blank" class="img-link">
                        <img src="{{ asset('assets/images/no-image.png') }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;" class="prod-img">
                    </a>
                </td>
                <td>
                    <select class="form-control category-select">
                        <option value="Exchange with Others">Exchange with Others</option>
                        <option value="Update To Latest" selected>Update To Latest</option>
                    </select>
                </td>
                <td class="publisher-val">-</td>
                <td class="book-name-val">-</td>
                <td class="mrp-val">-</td>
                <td class="selling-val">-</td>
                <td>
                    <button class="btn btn-danger delete-row" type="button"><i class="fa fa-trash"></i></button>
                </td>
            </tr>`;
        $('#modifyTable tbody').append(newRow);
    });

    // Delete Row
    $(document).on('click', '.delete-row', function() {
        if ($('#modifyTable tbody tr').length > 1) {
            $(this).closest('tr').remove();
            reindexRows();
        } else {
            alert('At least one row is required.');
        }
    });

    function reindexRows() {
        $('#modifyTable tbody tr').each(function(index) {
            $(this).find('.sl-no').text(index + 1);
        });
    }

    // Fetch Product Data
    $(document).on('click', '.fetch-btn', function() {
        let row = $(this).closest('tr');
        let productId = row.find('.product-id').val();
        if(!productId) return alert('Enter Product ID');

        let btn = $(this);
        btn.html('<i class="fa fa-spinner fa-spin"></i>').prop('disabled', true);

        $.get("{{ route('modify-listing.fetch') }}", { product_id: productId }, function(response) {
            btn.html('Fetch').prop('disabled', false);
            if(response.success) {
                row.find('.publisher-val').text(response.data.publisher || 'N/A');
                row.find('.book-name-val').text(response.data.book_name || 'N/A');
                row.find('.mrp-val').text(response.data.mrp || '0');
                row.find('.selling-val').text(response.data.selling_price || '0');
                if(response.data.image) {
                    row.find('.prod-img').attr('src', response.data.image);
                    row.find('.img-link').attr('href', response.data.image);
                }
            } else {
                alert(response.message);
            }
        });
    });

    // Bulk Upload
    $('#bulkUpload').change(function() {
        let file = this.files[0];
        if(!file) return;

        let formData = new FormData();
        formData.append('file', file);
        formData.append('_token', "{{ csrf_token() }}");

        $.ajax({
            url: "{{ route('modify-listing.upload') }}",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#modifyTable tbody').empty();
                response.forEach((item, index) => {
                    let row = `
                        <tr class="data-row">
                            <td class="sl-no">${index + 1}</td>
                            <td>
                                <div class="input-group">
                                    <input type="text" class="form-control product-id" value="${item.product_id}">
                                    <button class="btn btn-primary fetch-btn" type="button">Fetch</button>
                                </div>
                            </td>
                            <td class="image-val">
                                <a href="${item.image || '#'}" target="_blank" class="img-link">
                                    <img src="${item.image || '{{ asset("assets/images/no-image.png") }}'}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;" class="prod-img">
                                </a>
                            </td>
                            <td>
                                <select class="form-control category-select">
                                    <option value="Exchange with Others" ${item.category == 'Exchange with Others' ? 'selected' : ''}>Exchange with Others</option>
                                    <option value="Update To Latest" ${item.category == 'Update To Latest' ? 'selected' : ''}>Update To Latest</option>
                                </select>
                            </td>
                            <td class="publisher-val">${item.publisher}</td>
                            <td class="book-name-val">${item.book_name}</td>
                            <td class="mrp-val">${item.mrp}</td>
                            <td class="selling-val">${item.selling}</td>
                            <td>
                                <button class="btn btn-danger delete-row" type="button"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>`;
                    $('#modifyTable tbody').append(row);
                });
            }
        });
    });

    // Save All
    $('#saveAll').click(function() {
        let rows = [];
        $('.data-row').each(function () {
            let pid = $(this).find('.product-id').val();
            let category = $(this).find('.category-select').val();
        
            if (pid) {
        
                if (!category) {
                    hasError = true;
        
                    $(this).find('.category-select')
                        .addClass('border-red-500'); // highlight error
        
                    alert('Category is required for selected product.');
                    return false; // break .each loop
                }
        
                rows.push({
                    product_id: pid,
                    category: category
                });
            }
        });

        if(rows.length == 0) return alert('No valid records to save.');

        $(this).html('<i class="fa fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

        $.post("{{ route('modify-listing.store') }}", {
            _token: "{{ csrf_token() }}",
            rows: rows
        }, function(response) {
            $('#saveAll').html('SAVE ALL').prop('disabled', false);
            if (response.success) {
                alert(response.message);
                window.location.href = "{{ route('modify-listing.requested') }}";
            }
        });
    });
});
</script>
@endpush
