@extends('layouts.user')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Edit Pipe</div>
                <!--<a href="{{ route('pipe.index',$cid) }}" class="btn btn-info">Back</a>-->
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('pipe.update',$pid) }}" id="pipe_form">
                        <input name="_method" type="hidden" value="PATCH">
                        <input type="hidden" value="<?php if($pipe_data->email_to != ''){ $emaillist = explode(",",$pipe_data->email_to); echo sizeof($emaillist); } else { echo '2';} ?>" id="hid_count" name="hid_count" />
                        
                        {{ csrf_field() }}
                        <input type="hidden" name="client_id" value="{{$cid}}"/>
                        <div id="verify_access_div">
                            <div class="form-group{{ $errors->has('source_bucket') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-3 control-label">Data Source Bucket <span class="required">*</span></label>

                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="source_bucket" id='source_bucket'  autofocus value="{{$pipe_data->data_source_bucket}}">
                                    @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('source_bucket') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="form-group{{ $errors->has('access_key') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-3 control-label">Access Key <span class="required">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="access_key" id='access_key' value="{{$pipe_data->access_key}}">
                                    @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('access_key') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="form-group{{ $errors->has('access_secret') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-3 control-label">Access Secret Key <span class="required">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="access_secret" id='access_secret' value="{{$pipe_data->access_secret}}">
                                    @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('access_secret') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="name" class="col-md-3 control-label">&nbsp;</label>
                                <div class="col-md-8">
                                      <button type="button" class="btn btn-primary" id='verify_access'>Verify Access</button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-10" Id="message" style="margin-left:80px;font-style:italic;"></label>
                            </div>
                        </div>
                        
                        <div id="get_data_div">
                            
                            <div class="form-group{{ $errors->has('source_region') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-3 control-label">Data Source Region</label>

                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="source_region" id='source_region'  readonly="readonly">
                                    @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('source_region') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="form-group{{ $errors->has('source_key') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-3 control-label">Data Source Key <span class="required">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="source_key" id='source_key'  value="{{$pipe_data->data_source_key}}">
                                    @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('source_key') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="form-group{{ $errors->has('delimiter') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-3 control-label">Delimiter <span class="required">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="delimiter" id='delimiter' value="," MaxLength="1">
                                    @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('delimiter') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="form-group{{ $errors->has('headers') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-3 control-label">Headers</label>
                                <div class="col-md-8">
                                    <input type="checkbox" value="true" id="headers" name="headers" @if($pipe_data->headers == '1') {{'checked'}} @endif>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="name" class="col-md-3 control-label">&nbsp;</label>
                                <div class="col-md-8">
                                      <button type="button" class="btn btn-primary" id='get_data_file'>Get Data File</button>
                                      <!--<a href="{{ route('pipe.index',$cid) }}" class="btn btn-info">Back</a>-->
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-10" Id="message_file" style="margin-left:80px;font-style:italic;"></label>
                            </div>
                        </div>
                        
                        <div id="pipe_data_div">
                            <div class="form-group{{ $errors->has('pipe_name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-3 control-label">Pipe Name <span class="required">*</span></label>

                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="pipe_name" id='pipe_name'  value="{{$pipe_data->pipe_name}}">
                                    @if ($errors->has('pipe_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('pipe_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="form-group{{ $errors->has('pipe_description') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-3 control-label">Pipe Description <span class="required">*</span></label>

                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="pipe_description" id='pipe_description' value="{{$pipe_data->pipe_desc}}">
                                    @if ($errors->has('pipe_description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('pipe_description') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="form-group{{ $errors->has('notifications_email') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-3 control-label">Notifications Email </label>
                                    <div class="col-md-8" id="TextBoxesGroup">
                                    <?php
                                        $emaillist = explode(",",$pipe_data->email_to);
                                        $key = 1;
                                        foreach($emaillist as $email){ ?>
                                        <div id="TextBoxDiv{{$key}}">
                                            <div class="col-md-10">
                                                <input type="email" class="form-control nemail" name="notifications_email[]" id='notifications_email{{$key}}'  required value="{{$email}}" <?php if($key > 1) { echo 'style="margin-top: 15px;"'; } ?>>
                                            </div>
                                            <?php if($key > 1) { ?>
                                                <div class="col-md-2">
                                                    <i class="fa fa-trash delete" title = "Remove Email" data-custom-value="{{$key}}"  <?php if($key > 1) { echo 'style="cursor:pointer;margin-top:15px;font-size:30px;"'; } else { echo 'style="cursor:pointer;font-size:30px;"'; } ?> ></i>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    <?php $key++ ; } ?>
                                    </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="name" class="col-md-3 control-label">&nbsp;</label>
                                <div class="col-md-8">
                                    <button type="button" class="btn btn-secondary" id='addButton'>Add New Email</button> &nbsp;
                                    <button type="button" class="btn btn-secondary" id='removeButton'>Remove Email</button>
                                </div>
                            </div>
                            
                            <div class="form-group{{ $errors->has('preprocessing') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-3 control-label">Preprocessing of data</label>

                                <div class="col-md-8">
                                    <select class="preprocessing form-control" name="preprocessing">
                                        <option value="none" @if($pipe_data->preprocessing == 'none') {{'selected'}} @endif>None</option>
                                        <option value="normalization" @if($pipe_data->preprocessing == 'normalization') {{'selected'}} @endif>Normalization</option>
                                        <option value="detrend" @if($pipe_data->preprocessing == 'detrend') {{'selected'}} @endif>Detrend</option>
                                    </select>
                                    @if ($errors->has('preprocessing'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('preprocessing') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="form-group{{ $errors->has('col_date') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-3 control-label">Column to represent dates</label>

                                <div class="col-md-8">
                                    <select name="col_date" id="dates_column" required class="form-control">
                                    </select>
                                    @if ($errors->has('col_date'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('col_date') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="form-group{{ $errors->has('col_val') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-3 control-label">Column to represent values</label>

                                <div class="col-md-8">
                                    <select name="col_val" id="values_column" required class="form-control">
                                    </select>
                                    @if ($errors->has('col_val'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('col_val') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-md-3 control-label">Select the columns to be included:</label>
                                <div class="col-md-9"><table id="result_table" style="width:  100%;"></table></div>
                            </div>
                            
                            <div class="form-group">
                                <label for="name" class="col-md-3 control-label">&nbsp;</label>
                                <div class="col-md-8">
                                    <input type="submit" class="btn btn-primary" name="save_pipe" value="Save Pipe Date" id="save_pipe"/>
                                    <!--<a href="{{ route('pipe.index',$cid) }}" class="btn btn-info">Back</a>-->
                                </div>
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
$('#get_data_div').hide();
$('#pipe_data_div').hide();

/*$(document).on("keypress","input",function(e) {
    var regex = new RegExp("^[-!@#$%*?]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
       event.preventDefault();
       return false;
    }
});*/

$(document).on("click","#verify_access",function(e) {
    e.preventDefault();
    
    if($('#source_bucket').val() == ''){
        swal('Please enter Data Source Bucket');
    } else if($('#access_key').val() == '') {
        swal('Please enter Access key');
    } else if($('#access_secret').val() == '') {
        swal('Please enter Access secret key');
    } else {
        var source_bucket = $('#source_bucket').val();
        var access_key = $('#access_key').val();
        var access_secret = $('#access_secret').val();
        $('#message').html('');
        $.ajax({
            url: '{{url('verify_bucket')}}',
            type: 'POST',
            data: {'bucket' : source_bucket,'accSecret' : access_secret,'accKey' : access_key,'_token':$('input:hidden[name=_token]').val()},
            success: function(data) {
                var output =  $.trim(data);
                var output_lower = output.toLowerCase()
                if(output.length < 3 || output_lower.indexOf("error") !== -1 || output_lower.indexOf("cannot") !== -1 || output_lower.indexOf("false") !== -1 || output_lower.indexOf("invalid") !== -1 || output_lower.indexOf("action") !== -1) {
                    $('#get_data_div').hide();
                    $("#message").css("color", "red");
                    $('#message').html(data);
                } else {
                    $('#verify_access').attr('disabled',true);
                    $("#source_bucket").attr('readonly', true);
                    $("#access_key").attr('readonly', true);
                    $("#access_secret").attr('readonly', true);
                    $('#source_region').val(data);
                    $('#get_data_div').show();
                    $("#message").css("color", "black");
                    $('#message').html('Success! Bucket access enabled :- ' + data);
                }
            },
        });
    }
});

$(document).on("click","#get_data_file",function(e) {
    e.preventDefault();
    
    if($('#source_key').val() == ''){
        swal('Please enter Data Source Key');
    } else if($('#delimiter').val() == ''){
        swal('Please enter Delimiter');
    } else {
        var dataSource = $('#source_bucket').val();
        var access_key = $('#access_key').val();
        var access_secret = encodeURIComponent($('#access_secret').val());
        var source_region = $('#source_region').val();
        var source_key = $('#source_key').val();
        var delimiter = $('#delimiter').val();
        if ($('#headers').attr('checked')) {
            var headers = 'yes';
        } else {
            var headers = 'no';
        }
        
        $.ajax({
            url: '{{url('getfile')}}',
            type: 'POST',
            data: {'acessKey':access_key,'accesSecret':access_secret,'dataSource':dataSource,'dataSourceRegion':source_region,'dataSourceKey':source_key,'delimiter':delimiter,'headers':headers,'_token':$('input:hidden[name=_token]').val()},
            success: function(data) {
                var output =  $.trim(data);
                var output_lower = output.toLowerCase();
                if(output.length < 3 || output_lower.indexOf("error") !== -1 || output_lower.indexOf("cannot") !== -1 || output_lower.indexOf("false") !== -1 || output_lower.indexOf("invalid") !== -1) {
                    $("#message_file").css("color", "red");
                    $('#message_file').html(data);
                } else {
                    $('#get_data_file').attr('disabled',true);
                    $("#source_key").attr('readonly', true);
                    $("#delimiter").attr('readonly', true);
                    var s3lines=$.parseJSON(data);
                    if(typeof s3lines[1] !='undefined'){
	                var numCols=s3lines[1].length;
	            } else { 
                        var numCols=0;
                    }
                    var tableheads='<th style="width: 26%;">Column Order</th><th style="width: 37%;">Row 1 Values</th><th style="width: 37%;">Row 2 Values</th>';
	            var selectOptions='';
                    var row1cols=s3lines[0];
	            var row2cols=s3lines[1];
                    if(row1cols[0].length==0){ 
                        var row1cols=row2cols; 
                        var row2cols=[];
                    }
                    var tableOut='';
                    for(i = 1; i <= numCols; i++){
                        var colIndex=i-1;
                        if(typeof row2cols[colIndex]==='undefined'){ 
                            var  row2val=''; 
                        }else{
                            var row2val=row2cols[colIndex];
                        }
                        selectOptions+='<option value="'+i+'">Column '+i+'</option>';
                        tableOut+='<tr>';
                        tableOut+='<td>Column '+i+' <input type="checkbox" name="col_include[]" value="'+i+'"></td>';
                        tableOut+='<td>'+row1cols[colIndex]+'</td>';
                        tableOut+='<td>'+row2val+'</td>';
                        tableOut+='</tr>';
                    }
                    
                    $('#result_table').html('<tr>'+tableheads+'</tr>');
                    $('#dates_column').html(selectOptions);
                    $('#values_column').html(selectOptions);
                    $('#result_table').append(tableOut);
                    
                    $('#message_file').html('Success! File Data received.');
                    $('#pipe_data_div').show();
                }
            },
        });
        
    }
});

$(document).on("click","#save_pipe",function(e) {

    /*var blank_email = 1;
    var valid_email = 1;
    $.each($(".nemail"), function(index, value){
        var nmailvalue = ($(value).val());
        if(nmailvalue == ''){
            blank_email = 0;
        } else {
            var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
            if (testEmail.test(nmailvalue)){
                //return true;
            } else {
                valid_email = 0;
            }
        }
    });
    
    if(blank_email == 0 && valid_email == 0){
        return true;
    } else {
        if(blank_email == 1){
            swal('Please enter Notifications Email');
        } else if(valid_email == 1){
            swal('Notification email should be email an format');
        }
        return false;
    }*/
    
    $('#save_pipe').attr('disabled',true);

    var pipe_name = $("#pipe_name").val();
    var pipe_description = $("#pipe_description").val();
    
    if(pipe_name != '' && pipe_description != ''){
        //return true;
        $('#pipe_form').submit();
    } else {
        //alert('out');
        //alert('Please fillup all required fields!');
        if(pipe_name == ''){
            swal('Please enter Pipe Name');
            $('#save_pipe').removeAttr("disabled")
        } else if(pipe_description == '') {
            swal('Please enter Pipe Description');
            $('#save_pipe').removeAttr("disabled")
        }
        return false;
    }
    
});

$(document).on("change","#dates_column, #values_column",function(e) {
    var dates_selected=$('#dates_column').val();
    var vals_selected=$('#values_column').val();
    var checkboxes=$('#result_table input[type="checkbox"][name="col_include[]"]');
    //checkbox;
    $.each(checkboxes, function(){
        if($(this).val()==dates_selected || $(this).val()==vals_selected){
            $(this).prop('checked', true).attr('disabled','disabled');
        } else {
            $(this).removeAttr('disabled');
        }
    });
});

var counter = $('#hid_count').val();

$(document).on("click","#addButton",function(e) {
    if(counter >= 5 ){
        swal('We have allowed maximum five emails address.');
    } else {
        counter++;
        var newTextBoxDiv = $(document.createElement('div'))
        .attr("id", 'TextBoxDiv' + counter);
        newTextBoxDiv.after().html(
                '<div class="col-md-10">'+
          '<input type="email" name="notifications_email[]'+
          '" id="notifications_email' + counter + '" class="form-control nemail" style="margin-top:15px;" required value="" ></div>'+
          '<div class="col-md-2"><i class="fa fa-trash delete" title = "Remove Email" data-custom-value="'+ counter +'"  style="cursor:pointer;margin-top:15px;font-size:30px;" ></i></div>');
        newTextBoxDiv.appendTo("#TextBoxesGroup");
    }
    

});


$(document).on("click","#removeButton",function(e) {
    if(counter==1){
      swal("No more email to remove");
      return false;
    }
    $("#TextBoxDiv" + counter).remove();
    counter--;
    var new_count = $('#hid_count').val() - 1;
    $('#hid_count').val(new_count)
});


$(document).on("click", ".delete", function () {
    var emailid = $(this).data("custom-value");
    $("#TextBoxDiv" + emailid).remove();
    
    counter--;
    var new_count = $('#hid_count').val() - 1;
    $('#hid_count').val(new_count)
});


</script>

@endsection
