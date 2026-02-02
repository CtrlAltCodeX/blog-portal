@extends('layouts.master')

@section('title', 'Candidate Enquiries')

@push('css')
<style>
    .nav-item {
        margin-right: 10px;
    }
    
    .nav-tabs .nav-link {
        background-color: lightgrey;
        color:black;
    }
    
    #candidateEnquiriesTable thead tr {
        background: #ccc;
    }
    
    .table {
        color: #9a9da1;
    }
    
    .table td {
        color:black !important;
    }
    
    .nav-tabs {
        padding-left: 12px;
        border-bottom: 0px;
    }
    
    .pagination {
        justify-content: end;
    }
</style>
@endpush

@section('content')
<div>
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class='d-flex justify-content-between w-100 align-items-center'>
                        <h2 class="card-title">Job Application Enquiry</h2>
                        
                        <form action='' id='basic_filter'>
                            <input type='hidden' name='preference' value='{{ request()->preference }}' />
                            <input type='hidden' name='start_date' value='{{ request()->start_date }}' />
                            <input type='hidden' name='end_date' value='{{ request()->end_date }}' />
                            
                            <div class='d-flex'>
                                <select class="form-control w-100" id='order_by' name="order_by">
                                    <option value="desc" {{ request()->order_by == 'desc' ? 'selected' : '' }}>New to Old</option>
                                    <option value="asc" {{ request()->order_by == 'asc' ? 'selected' : '' }}>Old to New</option>
                                </select>
                                
                                <select class="form-control w-100 ml-2" id='paging' name="paging" style="margin-left: 10px;">
                                    <option value="25" {{ request()->paging == '25' ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request()->paging == '50' ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request()->paging == '100' ? 'selected' : '' }}>100</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                <div class='card-body'>
                    <form action='' method='GET'>
                        <div class='w-100 d-flex justify-content-between mb-4 align-items-center' style='grid-gap:10px;'>
                            <input type='text' name='search' class='form-control' placeholder='Search...' value='{{ request()->search }}' />
                            
                            <select class="form-control" id="jobTypeFilter" name='preference' style="width: 30%;">
                                <option value="">All</option>
                                <option {{ request()->preference == 'Work From Office' ? 'selected' : '' }}>Work From Office</option>
                                <option {{ request()->preference == 'Work From Home' ? 'selected' : '' }}>Work From Home</option>
                                <option {{ request()->preference == 'Others' ? 'selected' : '' }}>Others</option>
                            </select>
                            
                            <div>
                                <input type="date"
                                    class="form-control"
                                    name="created_start_date"
                                    value="{{ request()->start_date }}">
                            </div>

                            <div>
                                <input type="date"
                                    class="form-control"
                                    name="created_end_date"
                                    value="{{ request()->end_date }}">
                            </div>
                            
                            <div>
                                <input type="date"
                                    class="form-control"
                                    name="updated_start_date"
                                    value="{{ request()->start_date }}">
                            </div>

                            <div>
                                <input type="date"
                                    class="form-control"
                                    name="updated_end_date"
                                    value="{{ request()->end_date }}">
                            </div>

                            <div class="col-md-2 d-flex gap-1">
                                <button class="btn btn-primary w-100">Filter</button>

                                <!-- Export -->
                                <a href="{{ route('candidates.enquiries.export', request()->query()) }}"
                                class="btn btn-success w-100">
                                    Export
                                </a>
                            </div>
                        </div>
                    </form>
                    <ul class="nav nav-tabs mb-4" id="enquiryTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="new-tab" data-bs-toggle="tab"
                                data-bs-target="#new" type="button" role="tab">
                                {{ $newEnquiries->total() }} | New Enquiry
                            </button>
                        </li>
                        
                        @can('Job Enquiry List -> Applied Successful')
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="active-tab" data-bs-toggle="tab"
                                data-bs-target="#active" type="button" role="tab">
                                {{ $activeEnquiries->total() }} | Applied Successful
                            </button>
                        </li>
                        @endcan
                        
                        @can('Job Enquiry List -> Un-successful')
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="inactive-tab" data-bs-toggle="tab"
                                data-bs-target="#inactive" type="button" role="tab">
                                {{ $inactiveEnquiries->total() }} | Un-successful 
                            </button>
                        </li>
                        @endcan
                        
                        @can('Job Enquiry List -> All Enquiry List')
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="all-tab" data-bs-toggle="tab"
                                data-bs-target="#all" type="button" role="tab">
                                {{ $allEnquiries->total() }} | All Enquiry List
                            </button>
                        </li>
                        @endcan
                    </ul>
                    <div class="tab-content" id="enquiryTabsContent">
                        <!-- ALL -->
                        <div class="tab-pane fade show active" id="new" role="tabpanel">
                            @include('admin.enquiries.table', ['enquiries' => $newEnquiries])
                        </div>

                        <!-- ACTIVE -->
                        <div class="tab-pane fade" id="active" role="tabpanel">
                            @include('admin.enquiries.table', ['enquiries' => $activeEnquiries])
                        </div>

                        <!-- INACTIVE -->
                        <div class="tab-pane fade" id="inactive" role="tabpanel">
                            @include('admin.enquiries.table', ['enquiries' => $inactiveEnquiries])
                        </div>

                        <!-- All -->
                        <div class="tab-pane fade" id="all" role="tabpanel">
                            @include('admin.enquiries.table', ['enquiries' => $allEnquiries])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('.save_note').on('click', function() {
            var id = $(this).data('id');
            var note = $('#note-' + id).val();

            $.ajax({
                url: '/admin/candidates/enquiries/' + id + '/save-note',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    note: note
                },
                success: function(response) {
                    alert('Note saved successfully!');
                },
                error: function(xhr) {
                    alert('An error occurred while saving the note.');
                }
            });
        });

        $(document).on('click', '.edit-enquiry', function () {
            $('#edit_id').val($(this).data('id'));
            $('#edit_status').val($(this).data('status'));

            $('#editEnquiryModal').modal('show');
        });

        $('#editEnquiryForm').submit(function (e) {
            e.preventDefault();
            var id = $("#edit_id").val();

            $.ajax({
                url: "/admin/candidates/enquiries/" + id + "/update",
                method: "POST",
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "status": $("#edit_status").val(),
                },
                success: function (result) {
                    location.reload();
                },
                error: function () {
                    alert('Something went wrong');
                }
            });
        });
        
        $('#basic_filter SELECT').on('change',function() {
            $("#basic_filter").submit(); 
        });
        
        $(".add_note_btn").click(function(){
            $(this).parent().find('.add_notes').removeClass('d-none');
            $(this).parent().find('.add_note_btn').addClass('d-none');
        });
    });
</script>

@endpush