@extends('layouts.master')

@section('title', __('Term and Condition'))

@section('content')
<div class="card mt-5">
    <div class="card-body">
        <div>
            {!! $siteSettings->term_and_condition !!}
        </div>
    </div>
</div>
@endsection