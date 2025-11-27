@extends('layouts.master')

@section('title', 'Add Work Type')

@section('content')

<div class="card">
    <div class="card-header">
        <h4>Add Work Type</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('worktype.store') }}" method="POST">
            @csrf

            <div class="row align-items-end">

                <div class="col-md-4">
                    <label>Cause</label>
                    <input type="text" name="cause" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label>Amount</label>
                    <input type="number" step="0.01" name="amount" class="form-control" required>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-success w-100" style="margin-top: 30px;">
                        Save
                    </button>
                </div>

            </div>

        </form>
    </div>
</div>

@endsection
