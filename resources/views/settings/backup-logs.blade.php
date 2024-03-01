@extends('layouts.master')

@section('title', __('Backup Logs'))

@section('content')

<div class="card mt-5">
    <div class="card-body">
        {!! nl2br($logContent) !!}
    </div>
</div>

@endsection