@extends('layouts.master')

@section('title', 'Edit Work Type')

@section('content')

<div class="card">
    <div class="card-header">
        <h4>Edit Work Type</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('worktype.update', $workType->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row align-items-end">

                <div class="col-md-4">
                    <label>Cause</label>
                    <input type="text" name="cause" class="form-control" 
                           value="{{ $workType->cause }}" required>
                </div>

                <div class="col-md-3">
                    <label>Amount</label>
                    <input type="number" step="0.01" name="amount" 
                           class="form-control" value="{{ $workType->amount }}" required>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary w-100" style="margin-top: 30px;">
                        Update
                    </button>
                </div>

                <div class="col-md-2">
                    <a href="{{ route('worktype.index') }}" 
                       class="btn btn-secondary w-100" style="margin-top: 30px;">
                        Cancel
                    </a>
                </div>

            </div>

        </form>
    </div>
</div>

@endsection
