@extends('layouts.master')

@section('title', __('Upload CSV file'))

@section('content')
<form action="{{ route('import.data') }}" method="POST" id='listing_form'>
    @csrf
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">Listing</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="basic-datatable" class="table table-bordered text-nowrap border-bottom">
                    <thead>
                        <tr>
                            <th><input type="checkbox" class="check-all" /></th>
                            <th>{{ __('Sl') }}</th>
                            <!-- <th>{{ __('Status') }}</th> -->
                            <th>{{ __('Product ID') }}</th>
                            <th>{{ __('Product name') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Sell Price') }}</th>
                            <th>{{ __('MRP') }}</th>
                            <th>{{ __('Author Name') }}</th>
                            <th>{{ __('Language') }}</th>
                            <th>{{ __('Edition') }}</th>
                            <th>{{ __('Publisher') }}</th>
                            <th>{{ __('No of Pages') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($googlePosts as $key => $googlePost)
                        <tr>
                            <td>
                                <input type="checkbox" name="ids[]" class="checkbox-update" value="{{json_encode($googlePost)}}" />
                            </td>
                            <td>{{ ++$key }}</td>
                            <!-- <td>
                                <span class="text-success"><b>Pending</b></span>
                            </td> -->
                            <td style="white-space: normal;">
                                @if(isset($googlePost['p_id']))
                                {{ sprintf("%.0f", $googlePost['p_id']) }}
                                @endif
                            </td>
                            <td style="white-space: normal;">{{ $googlePost['title'] }}</td>
                            <td style="white-space: normal;">
                                {{ substr($googlePost['description'], 0, 50)."..." }}
                            </td>
                            <td>{{ '₹'.$googlePost['selling_price'] }}</td>
                            <td>{{ '₹'.$googlePost['mrp'] }}</td>
                            <td>{{ $googlePost['author_name'] }}</td>
                            <td>{{ $googlePost['language'] }}</td>
                            <td>{{ $googlePost['edition'] }}</td>
                            <td>{{ $googlePost['publisher'] }}</td>
                            <td>{{ $googlePost['no_of_pages'] }}</td>
                        </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
                <div style="text-align: right;">
                    <button class="btn btn-success" id='final_upload'>Final Upload</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        let ids = [];
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
        
        $('.checkbox-update').click(function() {
            let val = $(this).val();
            
            if ($(this).prop('checked')) {
                if (!ids.includes(val)) {
                    ids.push(val);
                }
            } else {
                let index = ids.indexOf(val);
                if (index !== -1) {
                    ids.splice(index, 1);
                }
                // Also uncheck the "check-all" if any individual is unchecked
                $('.check-all').prop('checked', false);
            }
        
            // If all checkboxes are checked individually, check "check-all"
            if ($('.checkbox-update:checked').length === $('.checkbox-update').length) {
                $('.check-all').prop('checked', true);
            }
        });
        
        $('#final_upload').click(function(e) {
             e.preventDefault();
             
             if (ids.length > 0) {
                //  console.log(ids);
                $('#listing_form').submit();
             } else {
                 alert('Please select atleast one checkbox');
             }
        });
    })
</script>
@endpush