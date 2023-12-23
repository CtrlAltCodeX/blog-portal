@extends('layouts.master')

@section('title', __('Settings'))

@section('content')
<div>
    <a href="{{ route('auth.google') }}" class="btn btn-primary mt-5" id="liveToastBtn">{{ !session('success') ? 'Connect with Google' : 'Account Connected' }}</a>
</div>

@endsection