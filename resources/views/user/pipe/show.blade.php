@extends('layouts.user')

@section('content')
<style>
    .pipe_data{margin-top: 5px !important}
</style>
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Show Pipe</div>
                <input type="hidden" name="pid" id="pid" value="{{$pid}}"/>
                <input type="hidden" name="col_date" id="col_date" value="{{$pipe_data->col_date}}"/>
                <input type="hidden" name="col_val" id="col_val" value="{{$pipe_data->col_val}}"/>
                <input type="hidden" name="col_include" id="col_include" value="{{$pipe_data->col_include}}"/>
                
                {{ csrf_field() }}
                <div class="panel-body">
                        <div>
                            <div class="row" style="margin-top: 5px;">
                                <div class="form-group">
                                    <label for="name" class="col-md-4 control-label">Data Source Bucket</label>

                                    <div class="col-md-8">
                                        <span id='source_bucket'>{{$pipe_data->data_source_bucket}}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row" style="margin-top: 5px;">
                                <div class="form-group">
                                    <label for="name" class="col-md-4 control-label">Access key </label>
                                    <div class="col-md-8">
                                        <span id='access_key'>{{$pipe_data->access_key}}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row" style="margin-top: 5px;">
                                <div class="form-group">
                                    <label for="name" class="col-md-4 control-label">Access secret key </label>
                                    <div class="col-md-8">
                                        <span id='access_secret'>{{$pipe_data->access_secret}}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row" style="margin-top: 5px;">
                                <div class="form-group">
                                    <label for="name" class="col-md-4 control-label">Data Source Region</label>
                                    <div class="col-md-8">
                                        <span id='source_region'>{{$pipe_data->data_source_region}}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row" style="margin-top: 5px;">
                                <div class="form-group">
                                    <label for="name" class="col-md-4 control-label">Data Source Key</label>
                                    <div class="col-md-8">
                                        <span id='source_key'>{{$pipe_data->data_source_key}}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row" style="margin-top: 5px;">
                                <div class="form-group">
                                    <label for="name" class="col-md-4 control-label">Delimiter</label>
                                    <div class="col-md-8">
                                        <span id='delimiter'>{{$pipe_data->delimiter}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 5px;">
                                <div class="form-group">
                                    <label for="name" class="col-md-4 control-label">Headers</label>
                                    <div class="col-md-8">
                                        <input type="hidden" value="{{$pipe_data->headers}}" id="headers" />
                                        @if($pipe_data->headers == '1') {{'Yes'}} @else {{'No'}} @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row" style="margin-top: 5px;">
                                <div class="form-group">
                                    <label for="name" class="col-md-4 control-label">Pipe Name</label>
                                    <div class="col-md-8">
                                        {{$pipe_data->pipe_name}}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row" style="margin-top: 5px;">
                                <div class="form-group">
                                    <label for="name" class="col-md-4 control-label">Pipe Description</label>
                                    <div class="col-md-8">
                                        {{$pipe_data->pipe_desc}}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row" style="margin-top: 5px;">
                                <div class="form-group">
                                    <label for="name" class="col-md-4 control-label">Notifications Email</label>
                                    <div class="col-md-8">
                                        <?php
                                            $emaillist = explode(",",$pipe_data->email_to);
                                            foreach($emaillist as $email){
                                                echo $email.'<br>';
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row" style="margin-top: 5px;">
                                <div class="form-group">
                                    <label for="name" class="col-md-4 control-label">Preprocessing of data</label>
                                    <div class="col-md-8">
                                        @if($pipe_data->preprocessing == 'none') {{'None'}} @elseif($pipe_data->preprocessing == 'normalization'){{'Normalization'}} @else {{'Detrend'}} @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row" style="margin-top: 5px;">
                                <div class="form-group">
                                    <label for="name" class="col-md-4 control-label">Column to represent dates</label>
                                    <div class="col-md-8">
                                        <span id="dates_value"></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row" style="margin-top: 5px;">
                                <div class="form-group">
                                    <label for="name" class="col-md-4 control-label">Column to represent values</label>
                                    <div class="col-md-8">
                                       <span id="represent_value"></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row" style="margin-top: 5px;">
                                <div class="form-group">
                                    <label for="name" class="col-md-4 control-label">Select the columns to be included:</label>
                                    <div class="col-md-8">
                                        <table id="result_table" style="width:  100%;"></table>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row" style="margin-top: 5px;">
                                <div class="form-group" >
                                    <label for="name" class="col-md-3 control-label">&nbsp;</label>
                                    <div class="col-md-8">
                                          <a href="{{ route('pipe.edit',['cid' => $cid, 'pid' => $pid])}}" class="btn btn-primary">Edit Pipe</a>
                                          <a href="{{ route('pipe.index',$cid) }}" class="btn btn-info">Back</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

$( document ).ready(function() {
        var dataSource = $('#source_bucket').html();
        var access_key = $('#access_key').html();
        var access_secret = encodeURIComponent($('#access_secret').html());
        var source_region = $('#source_region').html();
        var source_key = $('#source_key').html();
        var delimiter = $('#delimiter').html();
        
        if($('#headers').html() == '1'){
            var headers = 'yes';
        } else {
            var headers = 'no';
        }
        
        $.ajax({
            url: '{{url('getfile')}}',
            type: 'POST',
            data: {'acessKey':access_key,'accesSecret':access_secret,'dataSource':dataSource,'dataSourceRegion':source_region,'dataSourceKey':source_key,'delimiter':delimiter,'pid': $('#pid').val(),'headers':headers,'_token':$('input:hidden[name=_token]').val()},
            success: function(data) {
                var output =  $.trim(data);
                var output_lower = output.toLowerCase();
                if(output.length < 3 || output_lower.indexOf("error") !== -1 || output_lower.indexOf("cannot") !== -1 || output_lower.indexOf("false") !== -1 || output_lower.indexOf("invalid") !== -1) {
                    $("#message_file").css("color", "red");
                    $('#message_file').html(data);
                } else {
                    
                    var s3lines=$.parseJSON(data);
                    if(typeof s3lines[1] !='undefined'){
	                var numCols=s3lines[1].length;
	            } else { 
                        var numCols=0;
                    }
                    var tableheads='<th style="width: 26%;">Column Order</th><th style="width: 37%;">Row 1 Values</th><th style="width: 37%;">Row 2 Values</th>';
	            var selectdataOptions = '';
                    var selectOptions = '';
                    var selectvalOptions = '';
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
                        
                        if(i == $('#col_date').val()){
                            selectdataOptions+= 'Column ' +i;
                        }
                        
                        if(i == $('#col_val').val()){
                            selectvalOptions+= 'Column ' +i;
                        }
                        
                        tableOut+='<tr>';
                        if(i == $('#col_val').val() || i == $('#col_date').val()){
                            tableOut+='<td>Column '+i+' <input type="checkbox" onclick="return false;" disabled="disabled" checked></td>';
                        } else {
                            var col_include = $('#col_include').val();
                            if(col_include.indexOf(i) != -1){
                                tableOut+='<td>Column '+i+' <input type="checkbox" onclick="return false;" checked></td>';
                            } else {
                                tableOut+='<td>Column '+i+' <input type="checkbox" onclick="return false;"></td>';
                            }
                            
                        }
                        tableOut+='<td>'+row1cols[colIndex]+'</td>';
                        tableOut+='<td>'+row2val+'</td>';
                        tableOut+='</tr>';
                        
                    }
                    
                    $('#result_table').html('<tr>'+tableheads+'</tr>');
                    $('#dates_value').html(selectdataOptions);
                    $('#represent_value').html(selectvalOptions);
                    $('#result_table').append(tableOut);
                    
                    $('#message_file').html('Success! File Data received.');
                    $('#pipe_data_div').show();
                }
            },
        });
        
    
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


</script>

@endsection
