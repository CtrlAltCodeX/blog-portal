@extends('layouts.master')

@section('title', __('Manage Inventory'))

@push('css')
<style>
    ul {
        justify-content: end;
    }
</style>
@endpush

@section('content')
@can('Inventory access')
<div>
    <div class="row row-sm">
        <div class="col-lg-12">
            <form action="" method="get">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-4 filters">
                            <div class="col-md-12">
                                <h5>Filters</h5>
                            </div>

                            <input type="hidden" name="type" value="search">

                            <!-- <div class="col-md-3">
                                        <select class="form-control" id='status' name="status">
                                            <option value="">Status</option>
                                            <option value="live" {{ request('status') == 'live' ? 'selected' : '' }} >Live</option>
                                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                            <option value="draft" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                        </select>
                                    </div> -->

                            <div class="col-md-12">
                                <input class="form-control" id='q' name="q" placeholder="Search By Full Name" value="{{ request('q') }}" />
                            </div>

                            <!-- <div class="col-lg-3">
                                        <div class="wd-200 mg-b-30">
                                            <div class="input-group">
                                                <div class="input-group-text">
                                                    <span class="fa fa-calendar tx-16 lh-0 op-6"></span>
                                                </div><input class="form-control fc-datepicker" value="{{ request('startDate') }}" placeholder="Start Date" name="startDate" type="text">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="wd-200 mg-b-30">
                                            <div class="input-group">
                                                <div class="input-group-text">
                                                    <span class="fa fa-calendar tx-16 lh-0 op-6"></span>
                                                </div>
                                                <input class="form-control fc-datepicker" value="{{ request('endDate') }}" placeholder="End Date" name="endDate"  type="text">
                                            </div>
                                        </div>
                                    </div> -->
                        </div>
                    </div>

                    <div class="card-footer">
                        <button class="btn btn-primary">Filter</button>
                        <a href="{{ route('inventory.index') }}" class="btn btn-danger">Reset</a>
                    </div>
                </div>
            </form>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Manage Inventory</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatable" class="table table-bordered text-nowrap border-bottom">
                            <thead>
                                <tr>
                                    <th>{{ __('Sl') }}</th>
                                    <th>{{ __('Stock') }}</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Product name') }}</th>
                                    <th>{{ __('Product ID') }}</th>
                                    <th>{{ __('Labels') }}</th>
                                    <th>{{ __('MRP') }}</th>
                                    <th>{{ __('Sell Price') }}</th>
                                    <th>{{ __('Created at') }}</th>
                                    <th>{{ __('Updated at') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($googlePosts['paginator'] as $key => $googlePost)
                                @php
                                $images = [];
                                $doc = new \DOMDocument();
                                $doc->loadHTML($googlePost->content);
                                $selling = $doc->getElementById('selling')->textContent??'';
                                $mrp = $doc->getElementById('mrp')->textContent??'';
                                $image = $doc->getElementsByTagName("img")?->item(0)?->getAttribute('src');
                                for ($i = 0; $i < $doc->getElementsByTagName("img")->length; $i++) {
                                    $getImage = $doc->getElementsByTagName("img")->item($i);
                                    $images[] = $getImage->getAttribute('src');
                                }
                                $allImagesCount = count($images);
                                    @endphp
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>
                                            @if(isset($googlePost->labels) && in_array('Stk_o', $googlePost->labels))
                                            {{ 'Out of Stock' }}
                                            @elseif(isset($googlePost->labels) && in_array('Stk_d', $googlePost->labels))
                                            {{ 'On Demand' }}
                                            @elseif(isset($googlePost->labels) && in_array('Stk_b', $googlePost->labels))
                                            {{ 'Pre Booking' }}
                                            @elseif(isset($googlePost->labels) && in_array('Stk_l', $googlePost->labels))
                                            {{ 'Low Stock' }}
                                            @else {{ 'In Stock' }}
                                            @endif
                                        </td>
                                        <td><img onerror="this.onerror=null;this.src='/public/dummy.jpg';" src="{{ $image }}" alt="Product Image" /></td>
                                        <td><a href="{{ $googlePost->url }}" target="_blank">{{ $googlePost->id }}</a></td>
                                        <td><a href="{{ $googlePost->url }}" target="_blank">{{ \Illuminate\Support\Str::limit($googlePost->title, 20) }}</a></td>
                                        <td>{{ count($googlePost->labels??[]) }}</td>
                                        <td>₹{{ $mrp }}</td>
                                        <td>₹{{ $selling }}</td>
                                        <td>{{ date("d-m-Y h:i A", strtotime($googlePost->published)) }}</td>
                                        <td>{{ date("d-m-Y h:i A", strtotime($googlePost->updated)) }}</td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                @can('Inventory edit')
                                                <a href="{{ route('listing.edit', $googlePost->id) }}" class="btn btn-sm btn-primary">{{ __('Edit') }}</a>
                                                @endcan
                                                @can('Inventory delete')
                                                <form action="{{ route('listing.destroy', $googlePost->id) }}" method="POST" class="ml-2">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this item?')">
                                                        {{ __('Delete') }}
                                                    </button>
                                                </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                    @empty

                                    @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <li class="page-item"><a class="page-link" href="{{ route('inventory.index', ['pageToken' => $googlePosts['prevPageToken']]) }}">Previous</a></li>
                            <li class="page-item"><a class="page-link" href="{{ route('inventory.index', ['pageToken' => $googlePosts['nextPageToken']]) }}">Next</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
@endcan
@endsection

@push('js')

<script src="../assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="../assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>
<script src="../assets/plugins/datatable/js/dataTables.buttons.min.js"></script>
<script src="../assets/plugins/datatable/js/buttons.bootstrap5.min.js"></script>
<script src="../assets/plugins/datatable/dataTables.responsive.min.js"></script>
<script src="../assets/plugins/datatable/responsive.bootstrap5.min.js"></script>
<script src="../assets/js/table-data.js"></script>

<script src="../assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>

<!-- TIMEPICKER JS -->
<script src="../assets/plugins/time-picker/jquery.timepicker.js"></script>
<script src="../assets/plugins/time-picker/toggles.min.js"></script>

<!-- DATEPICKER JS -->
<script src="../assets/plugins/date-picker/date-picker.js"></script>
<script src="../assets/plugins/date-picker/jquery-ui.js"></script>

<!-- COLOR PICKER JS -->
<script src="../assets/plugins/pickr-master/pickr.es5.min.js"></script>

<!-- FORMELEMENTS JS -->
<script src="../assets/js/form-elements.js"></script>

<script>
    $(document).ready(function() {
        //______Basic Data Table
        $("#specification").on('change', function(e) {
            $('#field').on('input', function() {
                var field = $(this).val();
                var value = $('#specification').val();

                dataTable.column(value).search(field).draw();
            })

        });

        $("#status").on("change", function(e) {
            var status = $(this).val();
            $("#status").val(status);
            dataTable.column(1).search(status).draw();
        });
    })
</script>

@endpush