@extends('layouts.master')

@section('title', __('Upload CSV file'))

@section('content')
<form action="{{ route('import.data') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">Listing</h3>
    
            <button class="btn btn-primary">Save</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="basic-datatable" class="table table-bordered text-nowrap border-bottom">
                    <thead>
                        <tr>
                            <th><input type="checkbox" class="check-all" /></th>
                            <th>{{ __('Sl') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Product name') }}</th>
                            <th>{{ __('Sell Price') }}</th>
                            <th>{{ __('MRP') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($googlePosts as $key => $googlePost)
                        <tr>
                            <td>
                                <input type="checkbox" name="ids[]" class="checkbox-update" value="{{json_encode($googlePost)}}"/>
                            </td>
                            <td>{{ ++$key }}</td>
                            <td>
                                <span class="text-success"><b>Pending</b></span>
                            </td>
    
                            <td style="white-space: normal;">{{ $googlePost['title'] }}</td>
                            <td>{{ '₹'.$googlePost['selling_price'] }}</td>
                            <td>{{ '₹'.$googlePost['mrp'] }}</td>
                        </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
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
    })
</script>
@endpush