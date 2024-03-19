@extends('layouts.master')

@section('title', __('Listing'))

@section('content')
<div>
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header justify-content-between">
                    <h3 class="card-title">Listing</h3>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatable" class="table table-bordered text-nowrap border-bottom">
                            <thead>
                                <tr>
                                    <th>{{ __('Sl') }}</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Created by') }}</th>
                                    <th>{{ __('Created at') }}</th>
                                    <th>{{ __('Approved by') }}</th>
                                    <th>{{ __('Approved at') }}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($userListings as $key => $userListing)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td><img onerror="this.onerror=null;this.src='/public/dummy.jpg';" src="{{ $userListing->image }}" alt="Product Image"  width="100"/></td>
                                    <td>{{ $userListing->title }}</td>
                                    <td>{{ $userListing->create_user->name }}</td>
                                    <td>{{ date("d-m-Y h:i A", strtotime($userListing->created_at)) }}</td>
                                    <td>
                                        @if($userListing->approved_by)
                                        {{ $userListing?->approve?->name }}
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td>
                                        @if($userListing->approved_at)
                                        {{ date("d-m-Y h:i A", strtotime($userListing->approved_at)) }}
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td>
                                        @if($userListing->status == "0")
                                        Pending
                                        @elseif($userListing->status == 1)
                                        Approved
                                        @elseif($userListing->status == 2)
                                        Rejected
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')

<script src="/assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="/assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>
<script src="/assets/plugins/datatable/js/dataTables.buttons.min.js"></script>
<script src="/assets/plugins/datatable/js/buttons.bootstrap5.min.js"></script>
<script src="/assets/plugins/datatable/dataTables.responsive.min.js"></script>
<script src="/assets/plugins/datatable/responsive.bootstrap5.min.js"></script>
<script src="/assets/js/table-data.js"></script>

<script src="/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>

<!-- TIMEPICKER JS -->
<script src="/assets/plugins/time-picker/jquery.timepicker.js"></script>
<script src="/assets/plugins/time-picker/toggles.min.js"></script>

<!-- DATEPICKER JS -->
<script src="/assets/plugins/date-picker/date-picker.js"></script>
<script src="/assets/plugins/date-picker/jquery-ui.js"></script>

<!-- COLOR PICKER JS -->
<script src="/assets/plugins/pickr-master/pickr.es5.min.js"></script>

<!-- FORMELEMENTS JS -->
<script src="/assets/js/form-elements.js"></script>

<script>
    $(document).ready(function() {
        //______Basic Data Table
        $('#basic-datatable').DataTable({
            "paging": false
        });

        $("#category").on("change", function() {
            $("#form").submit();
        })
    })
</script>

@endpush