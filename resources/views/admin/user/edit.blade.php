@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Edit Register</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('user.update',$id) }}">
                        <input name="_method" type="hidden" value="PATCH">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('client') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Client Name<span class="required">*</span></label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="client" autofocus value="{{$appuser->client}}">
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('client') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">Active</label>

                            <div class="col-md-6">
                                <label class="radio-inline">
                                    <input type="radio" name="active" value="true" @if($appuser->active == '1'){{'checked'}}@endif > Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="active" value="false" @if($appuser->active == '0'){{'checked'}}@endif> No
                                </label>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('notification_emails') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Notification Email <span class="required">*</span></label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="notification_emails" required value="{{$appuser->notification_emails}}">

                                @if ($errors->has('notification_emails'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('notification_emails') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('client') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Password</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" autofocus id="password">

                                @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('client') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Confirm Password</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" id="cpassword">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary" id="btnSubmit">
                                    Update
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
            
            var name = $("#name").val();
            var email = $("#email").val();
            var password = $("#password").val();
            var confirmPassword = $("#password-confirm").val();
            
            if(name != '' && email != '' ){
                if(password != '' || confirmPassword != ''){
                    if (password != confirmPassword) {
                        //alert("Password and confirm password is not matched please try again.");
                        swal("The password fields do not match.");
                        return false;
                    }
                }
                return true;
            } else {
                //alert('Please fillup all required fields!');
                swal("Please fill in all required fields.");
                return false;
            }
        });
    });
</script>

@endsection
