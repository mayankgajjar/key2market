@extends('layouts.user')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Edit Connection</div>
                @if(Session::has('message'))
                    <div class="content pt0">
                        <div class="alert alert-success">
                            <a class="close" data-dismiss="alert">X</a>
                            <strong>{{ Session::get('message') }}</strong>
                        </div>
                    </div>
                @endif
                
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('connection.update',$id) }}" id="connection_form">
                        <input name="_method" type="hidden" value="PATCH">
                        {{ csrf_field() }}
                        <input type="hidden" name="client_id" value="{{$user_data->id}}" />
                        <input type="hidden" name="id" value="{{$id}}" />
                        
                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">Connection Type<span class="required">*</span></label>
                            <div class="col-md-6">
                                <select class="form-control" id="connection_type" name="connection_type">
                                    <option value="">Select Connection Type</option>
                                    <option value="postgres" <?php if($connection->connection_type == 'postgres'){ echo 'selected';} ?>>Postgres</option>
                                    <option value="redshift" <?php if($connection->connection_type == 'redshift'){ echo 'selected';} ?>>Redshift</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('connection_name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Connection Name <span class="required">*</span></label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="connection_name" autofocus value="{{$connection->connection_name}}">
                                @if ($errors->has('connection_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('connection_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('host') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Host <span class="required">*</span></label>
                            <div class="col-md-6">
                                <input id="host" type="text" class="form-control" name="host" value="{{$connection->host}}">
                                @if ($errors->has('host'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('host') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('port') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Port <span class="required">*</span></label>
                            <div class="col-md-6">
                                <input id="port" type="text" class="form-control" name="port" value="{{$connection->port}}" maxlength="5">
                                @if ($errors->has('port'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('port') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('database') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Database Name <span class="required">*</span></label>
                            <div class="col-md-6">
                                <input id="database" type="text" class="form-control" name="database" value="{{$connection->database}}">
                                @if ($errors->has('database'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('database') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('user_name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Username <span class="required">*</span></label>
                            <div class="col-md-6">
                                <input id="user_name" type="text" class="form-control" name="user_name" value="{{encryption_decryption_data($connection->user_name,'d')}}">
                                @if ($errors->has('user_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('user_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Password <span class="required">*</span></label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" id="password" value="{{encryption_decryption_data($connection->password,'d')}}">
                                @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        
                        
                        
                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button class="btn btn-primary " id="test_connection">Check Connection</button>
                                <button type="submit" class="btn btn-primary" disabled="disable" id="btnSubmit">Save Connection</button>
                                <a class="btn btn-primary" href="{{ route('connection.index',$user_data->id) }}">Back</a>
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
        $("#btnSubmit").click(function (event) {
            event.preventDefault();
            $('#btnSubmit').attr('disabled',true);
            var connection_name = $("#connection_name").val();
            var host = $("#host").val();
            var port = $("#port").val();
            var database = $("#database").val();
            var user_name = $("#user_name").val();
            var password = $("#password").val();
            var connection_type = $("#connection_type").val();
            
            if(connection_name != '' && host != '' && port != '' && database != '' && user_name != '' && password != '' && connection_type != ''){
            
                $.ajax({
                    url: '{{url('check_connection')}}',
                    type: 'POST',
                    data: {'host' : host,'port' : port,'database' : database,'password' : password,'connection_type': connection_type ,'username' : user_name ,'_token':$('input:hidden[name=_token]').val()},
                    success: function(data) {
                        if(data == 'Connection detail is wrong, Would you please verify it?'){
                            swal(data);
                            $('#btnSubmit').attr('disabled',true);
                            return false;
                        } else if(data == 'The system is not able to connect this database, please check connection details.'){
                            swal(data);
                            $('#btnSubmit').attr('disabled',true);
                            return false;
                        } else if(data == 'Connection details are perfect.we can connect this database.'){
                            $('#connection_form').submit();
                        }
                    },
                });
            } else {
                $('#save_pipe').removeAttr("disabled");
                swal("Please fill up all required fields!");
                return false;
            }
        });
        
        $("#port").on("keypress keyup blur",function (event) {    
            $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
            var len  = $(this).val().length
            if(len >= 5){
                return false;
            }
        });
        
        $(document).on('click', '#test_connection', function(event) {
            event.preventDefault();
            var connection_name = $("#connection_name").val();
            var host = $("#host").val();
            var port = $("#port").val();
            var database = $("#database").val();
            var user_name = $("#user_name").val();
            var password = $("#password").val();
            var connection_type = $("#connection_type").val();
            if(connection_name != '' && host != '' && port != '' && database != '' && user_name != '' && password != '' && connection_type != ''){
                $.ajax({
                    url: '{{url('check_connection')}}',
                    type: 'POST',
                    data: {'host':host,'port':port,'database':database,'password':password,'connection_type': connection_type,'username': user_name,'_token':$('input:hidden[name=_token]').val()},
                    timeout: 4000,
                    success: function(data) {
                        if(data == 'Connection details are perfect.we can connect this database.'){
                            $('#btnSubmit').attr('disabled',false);
                        }else{
                            $('#btnSubmit').attr('disabled',true);
                        }
                        swal(data);
                        return false;
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        if(textStatus==="timeout") {  
                            swal("Incorrect connection detail. Would you please verify it ?"); //Handle the timeout
                        }
                    }
                });  
            } else {
                swal("Please fill up all required fields!");
                return false;
            }
        });
        
    });
</script>
@endsection
