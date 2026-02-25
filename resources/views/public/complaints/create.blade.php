@extends('layouts.public_complaint')

@section('content')
<div class="main-content" style="background: #f8f9fa; min-height: 100vh; padding-top: 50px; padding-bottom: 80px;">
    <div class="container-fluid px-md-5">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-11">
                <div class="card shadow-lg border-0" style="border-radius: 20px; overflow: hidden;">
                    <div class="card-header border-0 p-4 text-white" style="background: linear-gradient(135deg, #1e3c72, #2a5298);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="fw-bold mb-0">CREATE DETAILS</h3>
                                <p class="mb-0 small opacity-75">Raise your issue with detailed order information</p>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-white text-primary px-3 py-2" style="font-size: 0.9rem; border-radius: 10px;">
                                    Complaint ID: AUTO-GENERATED
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-5">
                        <form action="{{ route('public.complaints.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- Header Info -->
                            <div class="row g-4 mb-5 pb-4 border-bottom">
                                <div class="col-6 col-md-3">
                                    <div class="p-3 bg-white rounded-4 text-center shadow-sm h-100">
                                        <label class="form-label small text-muted text-uppercase fw-bold d-block mb-1">Created Date</label>
                                        <span class="fw-bold text-dark text-nowrap">{{ date('d M, Y') }}</span>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="p-3 bg-white rounded-4 text-center shadow-sm h-100">
                                        <label class="form-label small text-muted text-uppercase fw-bold d-block mb-1">Created By</label>
                                        <span class="fw-bold text-dark">{{ $verifiedUser->name ?? 'Visitor' }}</span>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="p-3 bg-white rounded-4 text-center shadow-sm h-100">
                                        <label class="form-label small text-muted text-uppercase fw-bold d-block mb-1">Delivery Timeline</label>
                                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill text-wrap">By Default 3 Days</span>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3 text-center">
                                    <div class="p-3 bg-white border rounded-4 d-flex align-items-center justify-content-center h-100 shadow-sm">
                                        <div class="text-center">
                                            <p class="mb-0 small text-muted">Verified User:</p>
                                            <p class="mb-0 fw-bold text-primary small text-break">{{ \App\Models\ComplaintUser::find(session('complaint_user_id'))->email }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-4 mb-5">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Issue Type <span class="text-danger">*</span></label>
                                        <select name="issue_type_id" class="form-select bg-light border-0 py-3 rounded-4" required>
                                            <option value="">Select Issue Type</option>
                                            @foreach($issueTypes as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Department <span class="text-danger">*</span></label>
                                        <select name="department_id" class="form-select bg-light border-0 py-3 rounded-4" required>
                                            <option value="">Select Department</option>
                                            @foreach($departments as $dept)
                                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Title <span class="text-danger">*</span> <small class="text-muted fw-normal">(Max 1000 chars)</small></label>
                                        <input type="text" name="title" class="form-control bg-light border-0 py-3 rounded-4" placeholder="Brief subject/title" required maxlength="1000">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Detailed Description <span class="text-danger">*</span></label>
                                        <textarea name="description" class="form-control bg-light border-0 py-3 rounded-4" rows="5" placeholder="Describe your issue in detail..." required style="height: 155px;"></textarea>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">File Attach (Multiple Attachment - Image + PDF + Excel)</label>
                                        <input type="file" name="attachments[]" class="form-control bg-light border-0 py-2 rounded-4" multiple>
                                    </div>
                                </div>
                            </div>

                            <!-- Tag Logic -->
                            <div class="card bg-light border-0 rounded-4 mb-5">
                                <div class="card-body p-4">
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Specific Tag? <span class="text-danger">*</span></label>
                                            <div class="d-flex gap-4 mt-2">
                                                <div class="form-check custom-radio">
                                                    <input class="form-check-input" type="radio" name="specific_tag" id="tag_yes" value="1" onclick="toggleEmployee(true)">
                                                    <label class="form-check-label" for="tag_yes">Yes</label>
                                                </div>
                                                <div class="form-check custom-radio">
                                                    <input class="form-check-input" type="radio" name="specific_tag" id="tag_no" value="0" checked onclick="toggleEmployee(false)">
                                                    <label class="form-check-label" for="tag_no">No</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8" id="employee_area" style="display: none;">
                                            <div class="row g-2">
                                                <div class="col-md-4">
                                                    <input type="text" name="employee_name" class="form-control rounded-pill border-0 shadow-sm" placeholder="Employee Name">
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="email" name="employee_email" class="form-control rounded-pill border-0 shadow-sm" placeholder="Employee Email">
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="employee_mobile" class="form-control rounded-pill border-0 shadow-sm" placeholder="Employee Mobile No.">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4 pt-3 border-top">
                                        <label class="form-label fw-bold small text-muted">Do You Want to Send This Records through E-mail to The Employee? <span class="text-danger">*</span></label>
                                        <div class="d-flex gap-4 mt-1">
                                            <div class="form-check custom-radio">
                                                <input class="form-check-input" type="radio" name="send_mail" id="mail_yes" value="1">
                                                <label class="form-check-label" for="mail_yes">Yes</label>
                                            </div>
                                            <div class="form-check custom-radio">
                                                <input class="form-check-input" type="radio" name="send_mail" id="mail_no" value="0" checked>
                                                <label class="form-check-label" for="mail_no">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Details Table -->
                            <div class="mb-5">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="fw-bold text-dark mb-0">ORDER DETAILS</h5>
                                    <button type="button" class="btn btn-dark btn-sm rounded-pill px-4" onclick="addRow()">
                                        <i class="fas fa-plus me-1"></i> ADD ORDER
                                    </button>
                                </div>
                                <div class="table-responsive bg-light rounded-4 overflow-hidden shadow-sm">
                                    <table class="table table-borderless align-middle mb-0" id="order-table">
                                        <thead style="background: rgba(0,0,0,0.03);">
                                            <tr>
                                                <th class="ps-4">Order ID</th>
                                                <th>Ref. No.</th>
                                                <th>Tracking ID.</th>
                                                <th>Cx. Name.</th>
                                                <th>Cx. Phone</th>
                                                <th>Loss Value</th>
                                                <th style="width: 50px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white">
                                            <tr>
                                                <td class="ps-4"><input type="text" name="orders[0][order_id]" class="form-control border-0" required placeholder="..."></td>
                                                <td><input type="text" name="orders[0][ref_no]" class="form-control border-0" required placeholder="..."></td>
                                                <td><input type="text" name="orders[0][tracking_id]" class="form-control border-0" required placeholder="..."></td>
                                                <td><input type="text" name="orders[0][cx_name]" class="form-control border-0" required placeholder="..."></td>
                                                <td><input type="text" name="orders[0][cx_phone]" class="form-control border-0" required placeholder="..."></td>
                                                <td><input type="number" step="0.01" name="orders[0][loss_value]" class="form-control border-0" required placeholder="0.00"></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-3 mt-5">
                                <a href="{{ url('/') }}" class="btn btn-light px-5 py-3 rounded-pill fw-bold">CANCEL</a>
                                <button type="submit" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-lg" style="background: linear-gradient(135deg, #667eea, #764ba2); border: none;">
                                    SUBMIT COMPLAINT <i class="fas fa-check-circle ms-2"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let rowCount = 1;

    function toggleEmployee(show) {
        const area = document.getElementById('employee_area');
        area.style.display = show ? 'block' : 'none';
        area.querySelectorAll('input').forEach(i => i.required = show);
    }

    function addRow() {
        const table = document.getElementById('order-table').getElementsByTagName('tbody')[0];
        const newRow = table.insertRow();
        newRow.className = "border-top";
        newRow.innerHTML = `
            <td class="ps-4"><input type="text" name="orders[${rowCount}][order_id]" class="form-control border-0" required placeholder="..."></td>
            <td><input type="text" name="orders[${rowCount}][ref_no]" class="form-control border-0" required placeholder="..."></td>
            <td><input type="text" name="orders[${rowCount}][tracking_id]" class="form-control border-0" required placeholder="..."></td>
            <td><input type="text" name="orders[${rowCount}][cx_name]" class="form-control border-0" required placeholder="..."></td>
            <td><input type="text" name="orders[${rowCount}][cx_phone]" class="form-control border-0" required placeholder="..."></td>
            <td><input type="number" step="0.01" name="orders[${rowCount}][loss_value]" class="form-control border-0" required placeholder="0.00"></td>
            <td class="pe-3"><button type="button" class="btn btn-link text-danger p-0" onclick="removeRow(this)"><i class="fas fa-times-circle fs-5"></i></button></td>
        `;
        rowCount++;
    }

    function removeRow(btn) {
        const row = btn.parentNode.parentNode;
        row.parentNode.removeChild(row);
    }
</script>

<style>
    .form-control:focus, .form-select:focus {
        background-color: #fff !important;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1) !important;
    }
    .custom-radio .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }
    table input {
        font-size: 0.9rem;
    }
    table th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        padding: 15px !important;
        white-space: nowrap;
    }
    .text-nowrap { white-space: nowrap; }
    .form-label { white-space: nowrap; }
</style>
@endsection
