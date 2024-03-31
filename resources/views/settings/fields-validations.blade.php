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
    <div class="card">
        <div class="card-header justify-content-between">
            <h3 class="card-title">Fields Validation</h3>
        </div>
    </div>
    <div class="card-body">
        <div>
            <div class="row">
                <div class="col-md-6">
                    <form action="{{ route('settings.keywords.notallowed') }}" method="POST" enctype="multipart/form-data" id='name-validation'>
                        @csrf
                        <div class="d-flex flex-column" style="grid-gap: 10px;">
                            <label for="name" class="form-label">{{ __('Disallowed Name') }}<span class="text-danger">*</span></label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" autofocus placeholder="Disallowed Name">

                            <div class="emails">
                                <h5><b>List of Disallowed Names</b></h5>
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
                                            <td>
                                                <i class="fa fa-edit name-edit" name='{{$name->name}}' id='{{$name->id}}'></i>
                                                <a href="{{ route('settings.keywords.delete', $name->id) }}">
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
                        <button class="btn btn-primary button">Save</button>
                    </form>
                </div>
                <div class="col-md-6">
                    <form action="{{ route('settings.keywords.notallowed') }}" method="POST" enctype="multipart/form-data" id='link-validation'>
                        @csrf
                        <div class="d-flex flex-column" style="grid-gap: 10px;">
                            <label for="name" class="form-label">{{ __('Allowed Links') }}<span class="text-danger">*</span></label>
                            <input id="link" type="text" class="form-control @error('link') is-invalid @enderror mb-2" name="link" autofocus placeholder="Allowed Links">

                            <!-- <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="allow">
                                <span class="custom-control-label">Allow</span>
                            </label> -->

                            <div class="emails">
                                <h5><b>List of Allowed Links</b></h5>
                                <table class="table table-bordered text-nowrap border-bottom">
                                    <thead>
                                        <tr>
                                            <td>Link</td>
                                            <td>Action</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($notAllowedlinks as $link)
                                        <tr>
                                            <td>{{$link->links}}</td>
                                            <td>
                                                <i class="fa fa-edit link-edit" name='{{$link->links}}' id='{{$link->id}}'></i>
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
                        <button class="btn btn-primary button">Save</button>
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

            $("#link-validation .button").html("Update");
            $("#link-validation .button").attr("id", "update");
            $("#link-validation .button").attr('id-no', $(this).attr('id'))
        })

        $(".name-edit").click(function() {
            var name = $(this).attr('name');
            $("#name").val(name);

            $("#name-validation .button").html("Update");
            $("#name-validation .button").attr("id", "update");
        });

        $("#name-validation").on("click", "#update", function(e) {
            e.preventDefault();
            var id = $('.name-edit').attr('id');
            $("#name-validation").attr('action', "/admin/settings/keywords/update/" + id + "")
            $("#name-validation").attr('method', "post")
            $("#name-validation").submit();
        });

        $("#link-validation").on("click", "#update", function(e) {
            e.preventDefault();
            var id = $(this).attr('id-no');
            $("#link-validation").attr('action', "/admin/settings/keywords/update/" + id + "")
            $("#link-validation").attr('method', "post")
            $("#link-validation").submit();
        });
    });
</script>
@endpush