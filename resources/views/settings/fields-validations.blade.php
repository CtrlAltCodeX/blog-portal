@extends('layouts.master')

@section('title', __('Fields Validation'))

@push('css')

<style>
    .emails {
        height: 50vh;
        overflow: scroll;
    }

    .fa {
        font-size: 16px;
    }
</style>

@endpush

@section('content')
<div class="card mt-5">
    <div class="card-body">
        <div>

            <div class="row">
                <div class="col-md-6">
                    <form action="{{ route('settings.keywords.notallowed') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="d-flex flex-column" style="grid-gap: 10px;">
                            <label for="name" class="form-label">{{ __('Name') }}<span class="text-danger">*</span></label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" autofocus placeholder="Name">

                            <div class="emails">
                                <table class="table table-bordered text-nowrap border-bottom">
                                    <thead>
                                        <tr>
                                            <td>Name</td>
                                            <td>Action</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($notAllowedNames as $name)
                                        <tr>
                                            <td>{{$name->name}}</td>
                                            <td><i class="fa fa-edit name-edit" name='{{$name->name}}' id='{{$name->id}}'></i>
                                                <a href="{{ route('backup.emails.delete', $name->id) }}">
                                                    <i class="fa fa-trash m-2"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan=2 align='center'>No Data Available</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <button class="btn btn-primary">Save</button>
                    </form>
                </div>
                <div class="col-md-6">
                    <form action="{{ route('settings.keywords.notallowed') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="d-flex flex-column" style="grid-gap: 10px;">
                            <label for="name" class="form-label">{{ __('Link') }}<span class="text-danger">*</span></label>
                            <input id="link" type="text" class="form-control @error('link') is-invalid @enderror mb-2" name="link" autofocus placeholder="Link">

                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="allow">
                                <span class="custom-control-label">Allow</span>
                            </label>

                            <div class="emails">
                                <table class="table table-bordered text-nowrap border-bottom">
                                    <thead>
                                        <tr>
                                            <td>Name</td>
                                            <td>Action</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($notAllowedlinks as $link)
                                        <tr>
                                            <td>{{$link->links}}</td>
                                            <td>
                                                <i class="fa fa-edit link-edit" name='{{$link->links}}' id='{{$name->id}}'></i>
                                                <a href="{{ route('settings.keywords.delete', $link->id) }}">
                                                    <i class="fa fa-trash m-2"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan=3 align='center'>No Data Available</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <button class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $(".link-edit").click(function() {
            var name = $(this).attr('name');
            $("#link").val(name);
        })

        $(".name-edit").click(function() {
            var name = $(this).attr('name');
            $("#name").val(name);
        })
    });
</script>
@endpush