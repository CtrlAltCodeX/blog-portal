@extends('layouts.master')

@section('title', __('Uploaded CSV file'))
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
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">Listing</h3>
            <!-- <button class="btn btn-primary">Save</button> -->
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
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Product name') }}</th>
                            <th>{{ __('Sell Price') }}</th>
                            <th>{{ __('MRP') }}</th>
                            <th>{{__('Action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($listings as $key => $googlePost)
                        <tr>
                            <td>
                                <input type="checkbox" name="ids" class="checkbox-update" value="{{$googlePost['id']}}" />
                            </td>
                            
                            <td>{{ ++$key }}</td>
                            <td>
                                <span class="text-success"><b>Pending</b></span>
                            </td>
                            <td style="white-space: normal;"><textarea class="form-control" rows="4" readonly>{{ $googlePost['title'] }}</textarea></td>
                            <td>{{ '₹'.$googlePost['selling_price'] }}</td>
                            <td>{{ '₹'.$googlePost['mrp'] }}</td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="{{ route('bulklisting.edit', $googlePost->id) }}" class="btn btn-sm btn-primary padd">{{ __('Edit') }}</a>
                                    <form action="{{ route('database-listing.destroy', $googlePost['id']) }}" method="POST" class="ml-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger padd" onclick="return confirm('Are you sure you want to delete this?')">
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
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
         $("#update").html('<form id="update-status" action={{route("view.upload")}} method="GET"><div class="d-flex"><select class="form-control w-50" name="status"><option>Select</option> <option value=1>Delete</option></select><button class="btn btn-primary update-status" style="margin-left:10px;">Update</button></div></form>');

        $(".table-responsive").on('click', '.update-status', function(e) {
            e.preventDefault();
            var formData = $('#update-status').serializeArray();
            formData.push(ids);

            if (ids.length <= 0) {
                alert('Please select the row')
                return true;
            }

            $.ajax({
                type: "GET",
                url: "{{ route('delete.upload.data') }}",
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
@endpush