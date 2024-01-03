@extends('layouts.master')

@section('title', __('Listing'))

@section('content')
  <!-- CONTAINER -->
  <div class="main-container container-fluid">

    <!-- PAGE-HEADER -->
    <div class="page-header">
        <h1 class="page-title">Listing</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Listing</a></li>
                <li class="breadcrumb-item active" aria-current="page">Index</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- Row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">
                        Listing
                    </h4>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-nowrap text-md-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('Post Id') }}</th>
                                    <th>{{ __('Blog Id') }}</th>
                                    <th>{{ __('Author Id') }}</th>
                                    <th>{{ __('Content') }}</th>
                                    <th>{{ __('Published At') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Url') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($googlePosts as $googlePost)
                                    <tr>
                                        <td>{{ $googlePost->id }}</td>
                                        <td>{{ $googlePost->blog->id }}</td>
                                        <td>{{ $googlePost->author->id }}</td>
                                        <td>{!! $googlePost->content !!}</td>
                                        <td>{{ $googlePost->published }}</td>
                                        <td>{{ $googlePost->title }}</td>
                                        <td>{{ $googlePost->url }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">{{ __('No records found.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Row -->
</div>
@endsection