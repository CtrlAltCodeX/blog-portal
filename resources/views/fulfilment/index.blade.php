@extends('layouts.master')

@section('title', __('Fulfilment Types'))

@section('content')
<div class="card mt-5">
    <div class="card-header">
        <h3 class="card-title">{{ __('Manage Fulfilment Types') }}</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('fulfilment.store') }}" method="POST" class="mb-4">
            @csrf
            <div class="row align-items-end">
                <div class="col-md-5">
                    <label class="form-label">{{ __('Fulfilment Name') }}</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Fulfilled Supplier" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ __('Difference Amount') }}</label>
                    <input type="number" step="0.01" name="difference_amount" class="form-control" placeholder="e.g. 15" required>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">{{ __('Add New') }}</button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Difference Amount') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fulfilmentTypes as $type)
                        <tr>
                            <form action="{{ route('fulfilment.update', $type->id) }}" method="POST">
                                @csrf
                                <td>
                                    <input type="text" name="name" class="form-control" value="{{ $type->name }}" required>
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="difference_amount" class="form-control" value="{{ (float)$type->difference_amount }}" required>
                                </td>
                                <td>
                                    <button type="submit" class="btn btn-sm btn-info">{{ __('Update') }}</button>
                                    <a href="{{ route('fulfilment.delete', $type->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">{{ __('Delete') }}</a>
                                </td>
                            </form>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
