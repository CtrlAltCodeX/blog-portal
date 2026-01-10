@extends('layouts.master')

@section('title', __('Updated Listings (MS)'))

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
        position: absolute;
        z-index: 1;
    }

    .tooltip:hover .tooltiptext {
        visibility: visible;
    }

    .user-label {
        display: flex;
        align-items: center;
        margin-left: 5px;
        margin-right: 5px;
        margin-top: 8px;
    }
    
    .tick {
        border: 1px solid green;
        background-color: green;
        color: white !important;
        padding: 5px;
     }
     
    .close {
        border: 1px solid red;
        background-color: red;
        color: white !important;
        padding: 5px;
    }

    .thumb {
        width: 60px;
        cursor: zoom-in;
    }

    .image-preview {
        position: fixed;
        display: none; /* hidden initially */
        top: 0;
        left: 0;
        width: 400px;
        border: 1px solid #ddd;
        background: #fff;
        z-index: 9999;
        box-shadow: 0 4px 8px rgba(0,0,0,0.3);
    }

    .image-preview img {
        width: 100%;
        height: auto;
        display: block;
    }
</style>
@endpush

@section('content')
<div>
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header justify-content-between">
                    <h3 class="card-title">Updated Listings (MS)</h3>

                    <div class="d-flex align-items-center justify-content-between" style="grid-gap: 10px;">

                        <form action="" method="get" id='form' style="margin-left: 10px;" class="d-flex align-items-center justify-content-end">
                            <div class='d-flex'>
                                <a href="{{ route('database-listing.index', ['status' => '', 'category' => 'Product', 'startIndex' => 1, 'user' => request()->user]) }}" class="btn btn-light position-relative me-2 mb-2 btn-sm"> All
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{$allCounts}}
                                        <span class="visually-hidden">unread messages</span>
                                    </span>
                                </a>
                                <a href="{{ route('database-listing.index', ['status' => 0, 'category' => 'Product', 'startIndex' => 1, 'user' => request()->user]) }}" class="btn btn-warning position-relative me-2 mb-2 btn-sm"> Pending
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{$pendingCounts}}
                                        <span class="visually-hidden">unread messages</span>
                                    </span>
                                </a>
                                <a href="{{ route('database-listing.index', ['status' => 2, 'category' => 'Product', 'startIndex' => 1, 'user' => request()->user]) }}" class="btn btn-danger position-relative mb-2 btn-sm"> Rejected
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{$rejectedCounts}}
                                        <span class="visually-hidden">unread messages</span>
                                    </span>
                                </a>
                            </div>

                            <!--<labeL class='user-label'>Stock Filter: </labeL>-->
                            <input type="hidden" value="{{ request()->startIndex ?? 1 }}" name='startIndex'>
                            <input type="hidden" value="{{ request()->status ?? 0 }}" name='status'>
                            <select class="form-control w-25 m-2" id='category' name="category">
                                <option value="">In Stock</option>
                                <option value="Stk_o" {{ request()->category == 'Stk_o' ? 'selected' : '' }}>Out of Stock (Stk_o)</option>
                                <option value="stock__out" {{ request()->category == 'stock__out' ? 'selected' : '' }}>Out of Stock (stock__out)</option>
                                <option value="Stk_d" {{ request()->category == 'Stk_d' ? 'selected' : '' }}>On Demand Stock (Stk_d)</option>
                                <option value="stock__demand" {{ request()->category == 'stock__demand' ? 'selected' : '' }}>On Demand Stock (stock__demand)</option>
                                <option value="Stk_l" {{ request()->category == 'Stk_l' ? 'selected' : '' }}>Low Stock (Stk_l)</option>
                                <option value="stock__low" {{ request()->category == 'stock__low' ? 'selected' : '' }}>Low Stock (stock__low)</option>
                            </select>

                            @if(auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Super Management'))
                            <!--<labeL class='user-label'>User Filter: </labeL>-->
                            <select class="form-control w-25 m-2" id='user' name="user">
                                <option value="all">All Users</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request()->user == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            
                            <select class="form-control w-25" id='age_filter' name='age_filter'>
                                <option value="">All - Update Age</option>
                                <option value=6_month {{ request()->age_filter == '6_month' ? 'selected' : '' }}>0 - 6 Months</option>
                                <option value=1_year {{ request()->age_filter == '1_year' ? 'selected' : '' }}>6 Months - 1 Year</option>
                                <option value=2_years {{ request()->age_filter == '2_years' ? 'selected' : '' }}>1 Year - 2 Year</option>
                                <option value=3_years {{ request()->age_filter == '3_years' ? 'selected' : '' }}>2 Year - 3 Year</option>
                                <option value=3_plus_years {{ request()->age_filter == '3_plus_years' ? 'selected' : '' }}>3 Year +</option>
                            </select>
                            @endif
                        </form>

                        <form action="" method="get" id='pagingform'>
                            <input type="hidden" value="{{ request()->startIndex ?? 1 }}" name='startIndex'>
                            <input type="hidden" value="{{ request()->status ?? 0 }}" name='status'>
                            
                            <input type="hidden" value="{{ request()->category ?? '' }}" name='category'>
                            <input type="hidden" value="{{ request()->user ?? 'all' }}" name='user'>
                            <select class="form-control w-100" id='paging' name="paging">
                                <option value="25" {{ request()->paging == '25' ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request()->paging == '50' ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request()->paging == '100' ? 'selected' : '' }}>100</option>
                            </select>
                        </form>

                        <select class="form-control w-25" id='issue_dropdown'>
                            <option value="">Select</option>
                            <option value=2>All</option>
                            <option value=1>Correct Listing</option>
                            <option value=3>Error Listing (Astric)</option>
                        </select>

                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatable" class="table table-bordered text-nowrap border-bottom">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" class="check-all" /></th>
                                    <!-- <th>{{ __('-') }}</th> -->
                                    <th>{{ __('Sl') }}</th>
                                    <th>{{ __('Similarity') }}</th>
                                    <th>{{ __('Change %') }}</th>
                                    <th>{{ __('Validator') }}</th>
                                    @if (auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Super Management'))
                                    <th>{{ __('Update Age') }}</th>
                                    @endif
                                    <th>{{ __('Error Msg') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Stock') }}</th>
                                    <th>{{ __('Previous Image') }}</th>
                                    <th>{{ __('New Image') }}</th>
                                    <th>{{ __('Product ID') }}</th>
                                    <th>{{ __('Product name') }}</th>
                                    <th>{{ __('Sell Price') }}</th>
                                    <th>{{ __('Dis.') }}</th>
                                    <th>{{ __('MRP') }}</th>
                                    <th>{{ __('Labels') }}</th>
                                    <th>{{ __('Created By') }}</th>
                                    <th>{{ __('Created at') }}</th>
                                    <th>{{ __('Updated at') }}</th>
                                    @if(request()->status != 2 && ( auth()->user()->can('Pending Listing ( DB ) -> Edit') || auth()->user()->can('Pending Listing ( DB ) -> Delete') ))
                                    <th>{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($googlePosts as $key => $googlePost)
                                <tr id='{{$googlePost->id}}' class="@if(abs($googlePost->selling_price - $googlePost->mrp) >= 0 && abs($googlePost->selling_price - $googlePost->mrp) <= 10) astric @else accurate @endif">
                                    <td>
                                        <input type="checkbox" name="ids" class="checkbox-update" value="{{$googlePost->id}}" />
                                        @if(abs($googlePost->selling_price - $googlePost->mrp) >= 0 && abs($googlePost->selling_price - $googlePost->mrp) <= 10)
                                            <span style="color: red;font-size:25px">*</span>
                                        @endif
                                    </td>
                                    <td>{{ ++$key }}</td>
                                    <td style="{{ $googlePost->similarity_percentage > 40 ? 'background-color: red; color: white;' : '' }}">
                                        {{ $googlePost->similarity_percentage }}
                                    </td>
                                    <td>{{ $googlePost->change_percentage	 }}</td>
                                    <td align='center'>
                                        @php
                                            $images = json_decode($googlePost->images, true);
                                            $imageUrl = $images[0] ?? null;
                                        @endphp

                                        @if($imageUrl && str_contains($imageUrl, 'blogger.googleusercontent.com'))
                                            <i class='fa fa-check text-success tick'></i>
                                        @else
                                            <i class='fa fa-close text-danger close'></i>
                                        @endif
                                    </td>
                                    @if (auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Super Management'))
                                    <td class='text-capitalize'>{{ $googlePost->last_updated_formatted??"-" }}</td>
                                    @endif
                                    <td class="status">{{substr($googlePost->error, 0, 20)}}</td>
                                    <!-- @if($googlePost->error) <span data-bs-placement="top" data-bs-toggle="tooltip" title="{{$googlePost->error}}">Error</span> @else - @endif </td> -->
                                    <td>
                                        @switch($googlePost->status)
                                        @case(0)
                                        <span class="text-success"><b>Pending</b></span>
                                        @break;
                                        @case(2)
                                        <span class="text-danger"><b>Rejected</b></span>
                                        @break;
                                        @endswitch
                                    </td>
                                    <td>
                                        @if(isset($googlePost->categories) && (in_array('Stk_o', $googlePost->categories) || in_array('stock__out', $googlePost->categories)))
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
                                    @php
                                    $images = json_decode($googlePost->images, true);

                                    $image = (is_array($images) && isset($images[0]))
                                        ? $images[0]
                                        : '/public/dummy.jpg';

                                    @endphp
                                    <td align="center">
                                        <img 
                                            class="thumb" 
                                            src="{{ $googlePost->backup_listing ? $googlePost->backup_listing->base_url :  '/dummy.jpg' }}" 
                                            data-full="{{ $googlePost->backup_listing ? $googlePost->backup_listing->base_url :  '/dummy.jpg' }}"
                                            onerror="this.onerror=null;this.src='/dummy.jpg';"
                                            alt="Product Image">
                                        {{-- <img class='zoomable' style='width:60px;' onerror="this.onerror=null;this.src='/public/dummy.jpg';" src="{{ $googlePost->backup_listing ? $googlePost->backup_listing->base_url :  '/public/dummy.jpg' }}" alt="Product Image" /> --}}
                                    </td>
                                    <td>
                                        <img 
                                            class="thumb" 
                                            src="{{ $image }}" 
                                            data-full="{{ $image }}"
                                            onerror="this.onerror=null;this.src='/dummy.jpg';"
                                            alt="Product Image">
                                        {{-- <img style='width:60px;' onerror="this.onerror=null;this.src='/public/dummy.jpg';" src="{{ $image }}" alt="Product Image" class='zoomable' /> --}}
                                    </td>
                                    <td><a href="{{ $googlePost->url ? $googlePost->url : '#' }}" target='_blank'>{{ $googlePost->product_id }}</a></td>
                                    <td>{{ $googlePost->title }} <br> <span class='text-primary'>{{ $googlePost->backup_listing ? $googlePost->backup_listing->title : 'N/A' }}</span></td>
                                    <td>{{ '₹'.$googlePost->selling_price }}</td>
                                    <td>
                                        @php
                                            $discount = $googlePost->mrp > 0 
                                                        ? round((($googlePost->mrp - $googlePost->selling_price) / $googlePost->mrp) * 100, 2)
                                                        : 0;
                                        @endphp
                                        @if($discount > 0)
                                            {{ $discount . '%' }}
                                        @else
                                            0%
                                        @endif
                                    </td>
                                    <td>{{ '₹'.$googlePost->mrp }}</td>
                                    @php
                                    $categories = collect($googlePost->categories??[])->toArray();
                                    @endphp
                                    <td>
                                        <span data-bs-placement="top" data-bs-toggle="tooltip" title="{{ implode(", ", $categories) }}">
                                            {{ count($categories ?? []) }}
                                        </span>
                                    </td>
                                    <td>{{ $googlePost->created_by_user->name }}</td>
                                    <td>{{ date("d-m-Y h:i A", strtotime($googlePost->created_at)) }}</td>
                                    <td>{{ date("d-m-Y h:i A", strtotime($googlePost->updated_at)) }}</td>
                                    @if(request()->status != 2 && ( auth()->user()->can('Pending Listing ( DB ) -> Edit') || auth()->user()->can('Pending Listing ( DB ) -> Delete') ))
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            @can('Pending Listing ( DB ) -> Edit')
                                            <a href="{{ route('publish.edit', $googlePost->id) }}?edit=true" class="btn btn-sm btn-primary">{{ __('Edit') }}</a>
                                            @endcan
                                            
                                            @can('Pending Listing ( DB ) -> Delete')
                                            <form action="{{ route('database-listing.destroy', $googlePost->id) }}" method="POST" class="ml-2">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this item?')">
                                                    {{ __('Delete') }}
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                    @endif
                                </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>

                        {!! $googlePosts->appends(request()->all())->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="imagePreview" class="image-preview">
  <img id="imagePreviewImg" src="" alt="Preview">
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

@include('database-listing.listting-script')

<script>
    $(document).ready(function() {
        // $("#issue_dropdown").change(function() {
        //     if($(this).val() == 1) {
        //         $(".astric").hide();
        //         $(".accurate").show();
        //     } else if($(this).val() == 2) {
        //         $(".accurate").show();
        //         $(".astric").show();
        //     } else if($(this).val() == 3) {
        //         $(".accurate").hide();
        //         $(".astric").show();
        //     }
        // });
        
        //______Basic Data Table
        // $('#basic-datatable').DataTable({
        //     "paging": false,
        //     "order": [[1, "asc"]]
        // });

        // $("#category").on("change", function() {
        //     $("#form").submit();
        // })
        
        // $("#paging").on("change", function() {
        //     $("#pagingform").submit();
        // });
        
        // $("#status").change(function() {
        //     $("#formStatus").submit();
        // });

        // $("#user").change(function() {
        //     $("#form").submit();
        // });
        
        $("#age_filter").change(function() {
            $("#form").submit();
        });

        $("#basic-datatable_wrapper .col-sm-12:first").html('@can("Pending Listing ( DB ) -> Publish to Website")<form id="update-status" action={{route("listing.status")}} method="GET"><div class="d-flex"><select class="form-control w-50" name="status" id="status"><option value="">Select</option><option value=0>Pending</option><option value=2>Reject</option><option value=5>Update to Website</option>@can("Update Listing ( M/S ) -> Bulk Delete")<option value=6>Delete</option>@endcan</select><button class="btn btn-primary update-status" style="margin-left:10px;">Update</button></div><span class="text-danger m-2">Note: Bulk Approve Listings must configure with Google Authenticator</span></form> @endcan');

        $("#basic-datatable_wrapper").on('click', '.update-status', function(e) {
            e.preventDefault();
            var formData = $('#update-status').serializeArray();
            formData.push(ids);

            if (!$('#status').val()) {
                alert('Please select the action')

                return true;
            }

            let selectedValue = $('#status').val();

            if (ids.length <= 0) {
                alert('Please select the listing')
                return true;
            }


            if (selectedValue == 6) {
                let confirmDelete = confirm("Are you sure you want to DELETE the selected listings?");
                if (!confirmDelete) {
                    return;
                }
            }


            $.ajax({
                type: "GET",
                url: "{{ route('listing.status') }}",
                data: {
                    formData: formData,
                    publish: $('#status').val(),
                    ids: ids
                },
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                    location.reload();
                    // ids.forEach(function(id) {
                    //     $("#" + id + " .status").html('Queued');
                    // })
                },
            });
        });

        // var ids = [];
        // $(".checkbox-update").click(function() {
        //     if ($(this).prop('checked')) {
        //         ids.push($(this).val());
        //     } else {
        //         var index = ids.indexOf($(this).val());
        //         if (index !== -1) {
        //             ids.splice(index, 1);
        //         }
        //     }
        // });

        // $('.check-all').click(function() {
        //     $(".checkbox-update").each(function() {
        //         if ($('.check-all').prop('checked') == true) {
        //             $(this).prop('checked', true);
        //             ids.push($(this).val());
        //         } else {
        //             $(this).prop('checked', false);
        //             var index = ids.indexOf($(this).val());
        //             if (index !== -1) {
        //                 ids.splice(index, 1);
        //             }
        //         }
        //     });
        // });

        // $(function() {
        //     $('.thumb').hover(
        //         function(e) {
        //             var full = $(this).data('full');
        //             if (!full) {
        //                 full = '/dummy.jpg';
        //             }
        //             console.log(full);
        //             $('#imagePreviewImg').attr('src', full);
        //             $('#imagePreview').show();
        //         },
        //         function(e) {
        //             $('#imagePreview').hide();
        //         }
        //     );

        //     $('.thumb').mousemove(function(e) {
        //         // follow mouse cursor
        //         $('#imagePreview').css({
        //             top: e.clientY - 250 + 'px',
        //             left: e.clientX + 20 + 'px'
        //         });
        //     });
        // });
    })
</script>

@endpush