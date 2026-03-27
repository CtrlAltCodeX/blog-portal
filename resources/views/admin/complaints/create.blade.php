@extends('layouts.master')

@section('title', 'Create Complaints')

@section('content')
<div class="row mt-4">
    <div class="col-12">
        <div class="card overflow-hidden shadow-sm">

            <!-- Header -->
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">CREATE DETAILS</h5>
                    <small>Raise your issue with detailed order information</small>
                </div>
                <span class="badge bg-light text-dark">Complaint ID: AUTO-GENERATED</span>
            </div>

            <!-- Top Info -->
            <div class="p-3 border-bottom d-flex justify-content-between flex-wrap">
                <div><strong>Created Date:</strong> 26 Mar, 2026</div>
                <div><strong>Created By:</strong> Test</div>
                {{-- <div class='d-flex'>
                    <strong>Delivery Timeline:</strong> 
                    
                </div> --}}
                <div><strong>Verified User:</strong> admin@example.com</div>
            </div>

            <!-- Body -->
            <div class="card-body">
                <form action="{{ route('admin.complaints.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <!-- Left -->
                        <div class="col-md-4">
                            <label class="form-label">Issue Type *</label>
                            <select class="form-control" name="issue_type_id">

                                <option>Select Issue Type</option>
                                @foreach ($issueTypes as $issueType)
                                    <option value="{{ $issueType->id }}">{{ $issueType->name }}</option>
                                @endforeach
                            </select>
                        </div>
    
                        <div class="col-md-4">
                            <label class="form-label">Department *</label>
                            <select class="form-control" name="department_id">
                                <option>Select Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Delivery Timeline: </label>
                            <select name="delivery_timeline" class="form-control">
                                @for($i = 1; $i <= 7; $i++)
                                    <option value="{{ $i }}" {{ $i == 3 ? 'selected' : '' }} class="bg-white text-dark">{{ $i }} {{ $i == 1 ? 'Day' : 'Days' }}</option>
                                @endfor
                            </select>
                        </div>
    
                        <div class="col-md-6">
                            <label class="form-label">File Attach</label>
                            <input type="file" class="form-control" multiple name="files[]">
                            <small class="text-muted">Image + PDF + Excel allowed</small>
                        </div>
    
                        <div class="col-md-6">
                            <label class="form-label">Title *</label>
                            <input type="text" class="form-control" placeholder="Brief subject/title" name="title">
                        </div>
    
                        <div class="col-md-6">
                            <label class="form-label">Detailed Description *</label>
                            <textarea class="form-control" rows="3" placeholder="Describe your issue in detail..." name="description"></textarea>
                        </div>
                    </div>
    
                    <div class='row'>
                        <!-- Radio Sections -->
                        <div class="col-4 mt-3">
                            <label class="form-label">Specific Tag *</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="specific_tag" value=1>
                                <label class="form-check-label">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="specific_tag" value=0>
                                <label class="form-check-label">No</label>
                            </div>
                        </div>
    
                        {{-- <div class="col-4">
                            <label class="form-label">Managed By *</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="managed" checked>
                                <label class="form-check-label">Self with Admin</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="managed">
                                <label class="form-check-label">Admin</label>
                            </div>
                        </div> --}}
    
                        <div class="col-4">
                            <label class="form-label">Send Record via Email? *</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="send_mail" value=1>
                                <label class="form-check-label">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="send_mail" value=0 checked>
                                <label class="form-check-label">No</label>
                            </div>
                        </div>
                    </div>
    
                    <!-- Order Details -->
                    <div class="mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6>ORDER DETAILS</h6>
                            <button class="btn btn-dark btn-sm" id='addOrderBtn'>+ Add Order</button>
                        </div>
    
                        <div class="table-responsive">
                            <table class="table table-bordered text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Ref No.</th>
                                        <th>Tracking ID</th>
                                        <th>Customer Name</th>
                                        <th>Customer Phone</th>
                                        <th>Loss Value</th>
                                    </tr>
                                </thead>
                                <tbody id="orderTableBody">
                                    <tr>
                                        <td>
                                            <input type="text" name="orders[1][order_id]" class="form-control" placeholder="Order ID">
                                        </td>
                                        <td>
                                            <input type="text" name="orders[1][ref_no]" class="form-control" placeholder="Ref No">
                                        </td>
                                        <td>
                                            <input type="text" name="orders[1][tracking_id]" class="form-control" placeholder="Tracking ID">
                                        </td>
                                        <td>
                                            <input type="text" name="orders[1][cx_name]" class="form-control" placeholder="Customer Name">
                                        </td>
                                        <td>
                                            <input type="text" name="orders[1][cx_phone]" class="form-control" placeholder="Phone">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="orders[1][loss_value]" class="form-control" placeholder="0.00">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm removeRow">X</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
    
                    <!-- Footer Buttons -->
                    <div class="d-flex justify-content-end mt-4">
                        <button class="btn btn-outline-secondary me-2">Back</button>
                        <button class="btn btn-primary">Submit Complaint</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

        
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            let rowCount = 1;

            // Add Order Row
            $('#addOrderBtn').on('click', function () {

                rowCount++;

                let row = `
                    <tr>
                        <td>
                            <input type="text" name="orders[${rowCount}][order_id]" class="form-control" placeholder="Order ID">
                        </td>
                        <td>
                            <input type="text" name="orders[${rowCount}][ref_no]" class="form-control" placeholder="Ref No">
                        </td>
                        <td>
                            <input type="text" name="orders[${rowCount}][tracking_id]" class="form-control" placeholder="Tracking ID">
                        </td>
                        <td>
                            <input type="text" name="orders[${rowCount}][customer_name]" class="form-control" placeholder="Customer Name">
                        </td>
                        <td>
                            <input type="text" name="orders[${rowCount}][customer_phone]" class="form-control" placeholder="Phone">
                        </td>
                        <td>
                            <input type="number" step="0.01" name="orders[${rowCount}][loss_value]" class="form-control" placeholder="0.00">
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm removeRow">X</button>
                        </td>
                    </tr>
                `;

                $('#orderTableBody').append(row);
            });

            // Remove Row
            $(document).on('click', '.removeRow', function () {
                $(this).closest('tr').remove();
            });

        });
    </script>
@endpush