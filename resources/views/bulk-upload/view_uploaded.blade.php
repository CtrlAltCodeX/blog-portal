@extends('layouts.master')

@section('title', __('Uploaded CSV file'))

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">Listing</h3>
            <!-- <button class="btn btn-primary">Save</button> -->
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="basic-datatable" class="table table-bordered text-nowrap border-bottom">
                    <thead>
                        <tr>
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
                            
                            <td>{{ ++$key }}</td>
                            <td>
                                <span class="text-success"><b>Pending</b></span>
                            </td>
                            <td style="white-space: normal;"><textarea class="form-control" rows="4" readonly>{{ $googlePost['title'] }}</textarea></td>
                            <td>{{ '₹'.$googlePost['selling_price'] }}</td>
                            <td>{{ '₹'.$googlePost['mrp'] }}</td>
                            <td><a href="{{ route('bulklisting.edit', $googlePost->id) }}" class="btn btn-sm btn-primary padd">{{ __('Edit') }}</a></td>
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
    })
</script>
@endpush