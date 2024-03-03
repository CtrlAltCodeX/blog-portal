@extends('layouts.master')

@section('title', __('Manage Inventory'))

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
                    <h3 class="card-title">Manage Inventory</h3>
                    <form action="" method="get" id='form'>
                        <input type="hidden" value="{{ request()->startIndex ?? 1 }}" name='startIndex'>
                        <select class="form-control w-100" id='category' name="category">
                            <option value="">In Stock</option>
                            <option value="Stk_o" {{ request()->category == 'Stk_o' ? 'selected' : '' }}>Out of Stock (Stk_o)</option>
                            <option value="stock__out" {{ request()->category == 'stock__out' ? 'selected' : '' }}>Out of Stock (stock__out)</option>
                            <option value="Stk_d" {{ request()->category == 'Stk_d' ? 'selected' : '' }}>On Demand Stock (Stk_d)</option>
                            <option value="stock__demand" {{ request()->category == 'stock__demand' ? 'selected' : '' }}>On Demand Stock (stock__demand)</option>
                            <option value="Stk_l" {{ request()->category == 'Stk_l' ? 'selected' : '' }}>Low Stock (Stk_l)</option>
                            <option value="stock__low" {{ request()->category == 'stock__low' ? 'selected' : '' }}>Low Stock (stock__low)</option>
                        </select>
                    </form>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatable" class="table table-bordered text-nowrap border-bottom">
                            <thead>
                                <tr>
                                    <th>{{ __('-') }}</th>
                                    <th>{{ __('Sl') }}</th>
                                    <th>{{ __('Stock') }}</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Product name') }}</th>
                                    <th>{{ __('Sell Price') }}</th>
                                    <th>{{ __('MRP') }}</th>
                                    <th>{{ __('Labels') }}</th>
                                    <th>{{ __('Created at') }}</th>
                                    <th>{{ __('Updated at') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($googlePosts as $key => $googlePost)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="ids" class="checkbox-update" value="{{$googlePost->id}}" />
                                    </td>
                                    <td>{{ $googlePost->id }}</td>
                                    <td>@if(isset($googlePost->categories) && (in_array('Stk_o', $googlePost->categories) || in_array('stock__out', $googlePost->categories)))
                                        {{ 'Out of Stock' }}
                                        @elseif(isset($googlePost->categories) && (in_array('Stk_d', $googlePost->categories) || in_array('stock__demand', $googlePost->categories)))
                                        {{ 'On Demand' }}
                                        @elseif(isset($googlePost->categories) && in_array('Stk_b', $googlePost->categories))
                                        {{ 'Pre Booking' }}
                                        @elseif(isset($googlePost->categories) && (in_array('Stk_l', $googlePost->categories) || in_array('stock__low', $googlePost->categories)))
                                        {{ 'Low Stock' }}
                                        @else {{ 'In Stock' }}
                                        @endif
                                    </td>
                                    <td><img onerror="this.onerror=null;this.src='/public/dummy.jpg';" src="{{ $googlePost->images }}" alt="Product Image" /></td>
                                    <td>{{ $googlePost->title }}</td>
                                    <td>{{ $googlePost->selling_price }}</td>
                                    <td>{{ $googlePost->mrp }}</td>
                                    @php
                                    $categories = collect($googlePost->categories??[])->toArray();
                                    @endphp
                                    <td>
                                        <span data-bs-placement="top" data-bs-toggle="tooltip" title="{{ implode(", ", $categories) }}">
                                            {{ count($categories ?? []) }}
                                            </button>
                                    </td>
                                    <td>{{ date("d-m-Y h:i A", strtotime($googlePost->created_at)) }}</td>
                                    <td>{{ date("d-m-Y h:i A", strtotime($googlePost->updated_at)) }}</td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <a href="{{ route('database-listing.edit', $googlePost->id) }}" class="btn btn-sm btn-primary">{{ __('Edit') }}</a>
                                            <form action="{{ route('database-listing.destroy', $googlePost->id) }}" method="POST" class="ml-2">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this item?')">
                                                    {{ __('Delete') }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>

                        {!! $googlePosts->links() !!}
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

        $("#basic-datatable_wrapper .col-sm-12:first").html('<form id="update-status" action={{route("listing.status")}} method="GET"><div class="d-flex"><select class="form-control w-50" name="status"><option>Select</option><option value=0>Pending</option><option value=1>Approved</option><option value=2>Reject</option></select><button class="btn btn-primary update-status" style="margin-left:10px;">Update</button></div></form>');

        $("#basic-datatable_wrapper").on('click', '.update-status', function(e) {
            e.preventDefault();
            var formData = $('#update-status').serializeArray();
            
            // Create a new FormData object
            var formDataToSend = new FormData();

            // Add each serialized field to the FormData object
            $.each(formData, function(index, field) {
                formDataToSend.append(field.name, field.value);
            });

            // Append any additional data you want to send
            formDataToSend.append('additionalData', ids);

            formData.push(ids);
            console.log(formDataToSend);

            // $("#update-status").submit();
        });

        var ids = [];
        $(".checkbox-update").click(function() {
            ids.push($(this).val());
        })
    })
</script>

@endpush