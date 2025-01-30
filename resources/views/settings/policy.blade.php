@extends('layouts.master')

@section('title', __('Policies'))

@section('content')
<div class="card mt-5">
    <div class="card-body">
        <div>
            {!! $siteSettings->policy !!}
        </div>
    </div>
</div>
@endsection