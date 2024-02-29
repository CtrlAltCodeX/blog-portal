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
                    <div class="d-flex">
                        <form action="" method="get" id='form' style="margin-right: 10px;">
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

                        <a href="{{ route('backup.export') }}" class="mr-2 btn btn-primary">Export</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatable" class="table table-bordered text-nowrap border-bottom">
                            <thead>
                                <tr>
                                    <th>{{ __('Sl') }}</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('MRP') }}</th>
                                    <th>{{ __('Selling Price') }}</th>
                                    <th>{{ __('Category (Labels)') }}</th>
                                    <th>{{ __('No of Pages') }}</th>
                                    <th>{{ __('Publisher') }}</th>
                                    <th>{{ __('Author Name') }}</th>
                                    <th>{{ __('Edition') }}</th>
                                    <th>{{ __('SKU') }}</th>
                                    <th>{{ __('Language') }}</th>
                                    <th>{{ __('Condition') }}</th>
                                    <th>{{ __('Binding Type') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($getAllListings as $key => $listings)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td><img onerror="this.onerror=null;this.src='/public/dummy.jpg';" src="{{ $listings->base_url }}" alt="Product Image" /></td>
                                    <td style="white-space: normal;">{{ $listings->title }}</td>
                                    <td>{{ '₹'.$listings->mrp }}</td>
                                    <td>{{ '₹'.$listings->selling_price }}</td>
                                    <td style="white-space: normal;">{{ $listings->categories }}</td>
                                    <td>{{ $listings->no_of_pages }}</td>
                                    <td>{{ $listings->publisher }}</td>
                                    <td>{{ $listings->author_name }}</td>
                                    <td>{{ $listings->edition }}</td>
                                    <td>{{ $listings->sku }}</td>
                                    <td>{{ $listings->language }}</td>
                                    <td>{{ $listings->condition }}</td>
                                    <td>{{ $listings->binding_type }}</td>
                                    <td>
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