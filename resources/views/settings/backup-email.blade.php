@extends('layouts.master')

@section('title', __('Backup Emails'))

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
            <form action="{{ route('settings.emails.save') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="name" class="form-label">{{ __('Name') }}<span class="text-danger">*</span></label>

                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"  autofocus placeholder="Name">
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">{{ __('Emails') }}<span class="text-danger">*</span></label>

                            <input type="hidden" name="new" id='status' />
                            <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" autofocus placeholder="Emails">
                        </div>
                        <div class="col-md-6 mt-5">
                            <div class="emails">
                                <table class="table table-bordered text-nowrap border-bottom">
                                    <thead>
                                        <tr>
                                            <td>Name</td>
                                            <td>Email</td>
                                            <td>Action</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($backupMails as $mail)
                                        <tr>
                                            <td>{{$mail->name}}</td>
                                            <td>{{$mail->email}}</td>
                                            <td>
                                                <i class="fa fa-edit edit" email='{{$mail->email}}' name='{{$mail->name}}' id='{{$mail->id}}'></i>
                                                <a href="{{ route('backup.emails.delete', $mail->id) }}">
                                                    <i class="fa fa-trash m-2"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <button class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $(".edit").click(function() {
            var email = $(this).attr('email');
            var name = $(this).attr('name');
            $("#name").val(name);
            $("#email").val(email);

            var id = $(this).attr('id');
            $("#status").attr('name', 'update');
            $("#status").val(id);
        })
    });
</script>

@endpush