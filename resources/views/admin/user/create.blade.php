@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">User Register</div>

                <div class="panel-body">
                    @if(Session::has('message'))
                        <div class="content pt0">
                            <div class="alert alert-danger">
                                <a class="close" data-dismiss="alert">X</a>
                                <strong>{{ Session::get('message') }}</strong>
                            </div>
                        </div>
                    @endif
                    <form class="form-horizontal" method="POST" action="{{ route('user.store') }}" id="user_form" >
                        {{ csrf_field() }}

                        <!--<div class="form-group{{ $errors->has('id') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Client ID <span class="required">*</span></label>
                            
                            <div class="col-md-6">
                                <input id="client_id" type="text" class="form-control" name="id" value="{{ old('id') }}" autofocus placeholder="c64e52bd-0705-43b3-a517-7bc8a37c70b1">
                                @if ($errors->has('id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('id') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>-->

                        <div class="form-group{{ $errors->has('client') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Client Name <span class="required">*</span></label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control valid_input" name="client" value="{{ old('client') }}" placeholder="Kirill Andriychuk">

                                @if ($errors->has('client'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('client') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Client Email <span class="required">*</span></label>
                            <div class="col-md-6">
                                <input id="client_email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Kirill@key2market.com">

                                @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Password <span class="required">*</span></label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" id="password">

                                @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">Confirm Password <span class="required">*</span></label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" id="cpassword">
                            </div>
                        </div>
                        
                        

                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">Active</label>

                            <div class="col-md-6">
                                <label class="radio-inline">
                                    <input type="radio" checked="checked" name="active" value="true">Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="active" value="false">No
                                </label>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('notification_emails') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Notification Email <span class="required">*</span></label>

                            <div class="col-md-6">
                                <input id="notification_emails" type="email" class="form-control" name="notification_emails" value="{{ old('notification_emails') }}" placeholder="Kirill@key2market.com">

                                @if ($errors->has('notification_emails'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('notification_emails') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary " id="btnSubmit">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                    <label><span class="required_note">Note : * fields are required.</span></label>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
        
    $(function () {
        $("#btnSubmit").click(function () {
            //var id = $("#client_id").val();
            var name = $("#name").val();
            var client_email = $("#client_email").val();
            var password = $("#password").val();
            var confirmPassword = $("#password-confirm").val();
            var notification_emails = $("#notification_emails").val();
            
            if(name != '' && client_email != '' && password != '' && confirmPassword != '' && notification_emails != ''){
                if (password != confirmPassword) {
                    swal("The password fields do not match.");
                    //alert("Password and confirm password is not matched please try again.");
                    return false;
                }
                return true;
            } else {
                swal("Please fill in all required fields.");
                //alert('Please fillup all required fields!');
                return false;
            }
        });
    });
</script>
@endsection
