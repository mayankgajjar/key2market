@extends('layouts.user')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Edit Table Data</div>
                @if($massage !== '')
                    <!--<div class="content pt0">
                        <div class="alert alert-success">
                            <a class="close" data-dismiss="alert">X</a>
                            <strong>{{ $massage }}</strong>
                        </div>
                    </div>-->
                @endif
                
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('monitor.update',$id) }}" id="table_form">
                        <input name="_method" type="hidden" value="PATCH">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{$id}}" />
                        <input type="hidden" name="client_id" value="{{$connection_info['client_id']}}" />
                        
                        
                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">Schema<span class="required">*</span></label>
                            <div class="col-md-6">
                                <select class="form-control" id="schema_name" name="schema_name">
                                    <option value="">Select Schema</option>
                                    <?php foreach($schema_data as $key => $sdata ){ ?>
                                        <option value="{{$sdata->table_schema}}" <?php if($sdata->table_schema == $table_data_db->schema_name) { echo 'Selected'; } ?>>{{$sdata->table_schema}}</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">Table Name<span class="required">*</span></label>
                            <div class="col-md-6">
                                <select class="form-control" id="table_name" name="table_name">
                                    <option value="">Select Table</option>
                                    <?php foreach($table_data as $key => $tdata ){ ?>
                                        <option value="{{$key}}" <?php if($key == $table_data_db->table_name) { echo 'Selected'; } ?>>{{$key}}</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">Date Column</label>
                            <div class="col-md-6">
                                <select class="form-control" id="date_column_name" name="date_column_name">
                                    <option value="">Select Date Column</option>
                                    
                                        <?php foreach ($table_schemas as $table_schema) { ?>
                                            <?php if( strpos( $table_schema->data_type, 'date' ) !== false ) { ?>
                                                <option value="{{$table_schema->column_name}}" <?php if($table_schema->column_name == $table_data_db->date_column_name){ echo 'Selected'; } ?>>{{$table_schema->column_name}}</option>
                                            <?php } ?>
                                            <?php if( strpos( $table_schema->data_type, 'timestamp' ) !== false ) { ?>
                                                <option value="{{$table_schema->column_name}}" <?php if($table_schema->column_name == $table_data_db->date_column_name){ echo 'Selected'; } ?>>{{$table_schema->column_name}}</option>
                                            <?php } ?>
                                        <?php } ?>
                                    
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">ID Column</label>
                            <div class="col-md-6">
                                <select class="form-control" id="id_column_name" name="id_column_name">
                                    <option value="">Select ID Column</option>
                                        <?php foreach ($table_schemas as $table_schema) { ?>
                                            <option value="{{$table_schema->column_name}}" <?php if($table_schema->column_name == $table_data_db->id_column_name){ echo 'Selected'; } ?>>{{$table_schema->column_name}}</option>                                           
                                        <?php } ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button class="btn btn-primary " id="btnSubmit">Save Table</button>
                                <a class="btn btn-primary" href="{{ route('monitor.index',$connection_info['client_id']) }}">Back</a>
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
            var table_name = $("#table_name").val();
            var schema_name = $("#schema_name").val();

            if(table_name != '' && schema_name != ''){
                $('#table_form').submit();
            } else {
                swal("Please fill up all required fields!");
                return false;
            }
        });
        
        $("#table_name").change(function () {
            if($(this).val() !== ''){
                H5_loading.show();
                var schema_name = $('#schema_name').val();
                var table_name = $(this).val();
                var connection_id = '<?php echo $table_data_db->connection_id; ?>'
                $('#date_column_name').html('');
                $('#id_column_name').html('');

                $.ajax({
                    url: '{{url('get_date_id_column')}}',
                    type: 'POST',
                    data: {'table_name' : table_name,'schema_name' : schema_name,'_token':$('input:hidden[name=_token]').val(),'connection_id':connection_id},
                    success: function(data) {
                        $('#date_column_name').html(data.date_col);
                        $('#id_column_name').html(data.id_col);
                        H5_loading.hide();
                    },
                });
            }
            
        });
        
        $("#schema_name").change(function () {
            H5_loading.show();
            var schema_name = $(this).val();
            var connection_id = '<?php echo $table_data_db->connection_id; ?>'
            $('#table_name').html('');
            $('#date_column_name').html('');
            $('#id_column_name').html('');
            
            $.ajax({
                url: '{{url('get_table_date_id_column')}}',
                type: 'POST',
                data: {'schema_name' : schema_name,'_token':$('input:hidden[name=_token]').val(),'connection_id':connection_id},
                success: function(data) {
                    $('#table_name').html(data.table);
                    $('#date_column_name').html(data.date_col);
                    $('#id_column_name').html(data.id_col);
                    H5_loading.hide();
                },
            });
        });
    });
</script>
@endsection
