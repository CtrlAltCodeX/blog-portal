@extends('layouts.master')

@section('title', __('Gallery List Page ( DB )'))

@push('css')
<style>
    ul {
        justify-content: end;
    }

    #basic-datatable_info {
        display: none;
    }

    .tooltip {
        position: relative;
        display: inline-block;
        border-bottom: 1px dotted black;
    }

    .tooltip .tooltiptext {
        visibility: hidden;
        width: 120px;
        background-color: black;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px 0;

        /* Position the tooltip */
        position: absolute;
        z-index: 1;
    }

    .tooltip:hover .tooltiptext {
        visibility: visible;
    }
</style>
@endpush

@section('content')

<div>
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header justify-content-between">
                    <h3 class="card-title">Gallery List Page ( DB )</h3>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <div class="row mb-2">
                            <div class="col-md-5" id='update'></div>
                        </div>
                        <table id="basic-datatable" class="table table-bordered text-nowrap border-bottom">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" class="check-all" /></th>
                                    <th>{{ __('Sl') }}</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Image Size') }}</th>
                                    <th>{{ __('Url') }}</th>
                                    <th>{{ __('Created On') }}</th>
                                    <!-- <th>{{ __('Created By') }}</th> -->
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 0; @endphp
                                @forelse ($files as $key => $file)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="ids" class="checkbox-update" value="{{$file['name']}}" />
                                    </td>
                                    <td>{{++$key}}</td>
                                    <td><img src="/storage/uploads/{{ $file['name'] }}" alt="Product Image" width="50" /></td>
                                    <td>{{ $file['name'] }}</td>
                                    <td>{{ round($file['size']/1000, 1) }} kb</td>
                                    <td><p style="font-size: 9px;">{{ url('/') }}/storage/uploads/{{ $file['name'] }}</p></td>
                                    <td>{{ date("d-m-Y h:i A", strtotime($file['datetime'])) }}</td>
                                    <!-- <td>{{ date("d-m-Y h:i A", strtotime($file['datetime'])) }}</td> -->
                                    <td style="vertical-align: middle;">
                                        <div class="d-flex justify-content-between" style='grid-gap:10px;'>
                                            <a href="{{ url('/') }}/storage/uploads/{{ $file['name'] }}" id='downloadMultipleImage' download="product-image.jpg">
                                                <i class="fa fa-download" style="font-size: 20px;"></i>
                                            </a>
                                            <a href='#' class="copy" id="{{ route('assets', $file['name']) }}">
                                                <i class=" fa fa-copy" style="font-size: 20px;"></i>
                                            </a>
                                            <a href="{{ route('assets', $file['name']) }}" target="_blank" id='downloadMultipleImage'>
                                                <i class="fa fa-eye" style="font-size: 20px;"></i>
                                            </a>
                                            @can('Image Creation -> Gallery ( DB ) -> Delete')
                                            <a href="{{ route('image.gallery.delete', ['name' => $file['name']]) }}">
                                                <i class="fa fa-trash" style="font-size: 20px;"></i>
                                            </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{ $files->links() }}
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
            'paging':false
        });

        $("#category").on("change", function() {
            $("#form").submit();
        });
        
        $("#basic-datatable_wrapper .col-sm-12:first").html('<form id="count" action={{route("image.gallery")}} method="GET"><input type="hidden" name="page" value="{{ request()->page }}" /><select class="form-control w-25 count" name="count"><option value="15" {{ request()->count == 15 ? "selected" : "" }}>15</option><option value="25" {{ request()->count == 25 ? "selected" : "" }}>25</option><option value="50"  {{ request()->count == 50 ? "selected" : "" }}>50</option><option value="100"  {{ request()->count == 100 ? "selected" : "" }}>100</option></select></form>');
        
        $('#basic-datatable_wrapper').on('change', '.count', function(){
                $('#count').submit();
        });

        $("#update").html('<form id="update-status" action={{route("listing.status")}} method="GET"><div class="d-flex"><select class="form-control w-50" name="status"><option>Select</option>@can("Image Creation -> Gallery ( DB ) -> Delete") <option value=1>Delete</option>@endcan</select><button class="btn btn-primary update-status" style="margin-left:10px;">Update</button></div></form>');

        $(".table-responsive").on('click', '.update-status', function(e) {
            e.preventDefault();
            var formData = $('#update-status').serializeArray();
            formData.push(ids);

            if (ids.length <= 0) {
                alert('Please select the Image')
                return true;
            }

            $.ajax({
                type: "GET",
                url: "{{ route('image.gallery.delete') }}",
                data: {
                    formData: formData
                },
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                    if (result) {
                        window.location.href = location.href;
                    }
                },
            });
        });

        var ids = [];
        $(".checkbox-update").click(function() {
            if ($(this).prop('checked')) {
                ids.push($(this).val());
            } else {
                var index = ids.indexOf($(this).val());
                if (index !== -1) {
                    ids.splice(index, 1);
                }
            }
        });

        $('.check-all').click(function() {
            $(".checkbox-update").each(function() {
                if ($('.check-all').prop('checked') == true) {
                    $(this).prop('checked', true);
                    ids.push($(this).val());
                } else {
                    $(this).prop('checked', false);
                    var index = ids.indexOf($(this).val());
                    if (index !== -1) {
                        ids.splice(index, 1);
                    }
                }
            });
        });
    })
</script>
@include('image-creation.script')

@endpush