@extends('layouts.user')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Select Tables to Monitor</div>
                <div class="panel-body">

                    <div class="action-form">
                        <form class="form-inline" onsubmit="actionFormSubmit(event);return false;">
                          <div class="form-group">
                            <label for="action">Action:</label>
                            <select name="action" class="form-control">
                                <option value="">Please Select</option>
                                <option value="monitor">Activate Monitoring</option>
                            </select>   
                          </div>
                          <button type="submit" class="btn btn-primary">Apply to All</button>
                        </form>                        
                    </div>

                    <form class="form-horizontal" method="POST" action="{{ route('monitor.store') }}" id='table-form'>
                        {{ csrf_field() }}
                        <input type="hidden" name="connection_id" value="{{$id}}"/>
                        <input type="hidden" name="client_id" value="{{$connection_info['client_id']}}"/>
                        <input type="hidden" name="schema" value="{{$schema}}" />
                        <table class="table table-bordered" id="dbtable-table" style="text-align:center;">
                            <thead>
                                <tr class="btn-info">
                                    <th scope="col" style="text-align:center;">Activate Monitoring<br /></th>
                                    <th scope="col" style="text-align:center;">Table Name</th>
                                    <th scope="col" style="text-align:center;">Date Column</th>
                                    <th scope="col" style="text-align:center;">ID Column</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if($table_data != '') {
                                    foreach ($table_data as $key => $tdata ) { ?>                                    
                                    <?php $monitorTable = $selectedTables->where('table_name', $key)->first() ?>
                                <tr class='{{$key}}  <?php echo $monitorTable  ? 'monitor-table' : '' ?>'>                                    
                                    <td>
                                        <input type="hidden" name="already_moniter[]" value="<?php echo $monitorTable  ?  $key : '' ?>" >
                                        <input type="hidden" name='table_name_val[]' value="{{$key}}">
                                        <div class="checkbox checkbox-primary">
                                        <input type="checkbox" name='table_name[]' value="{{$key}}" <?php echo $monitorTable  ? 'checked="checked"' : '' ?> /><label></label>
                                    </div>
                                    </td>
                                    <td>{{$key}}</td>
                                    <td>
                                        <select class="form-control date_column" name="date_column_name[]">
                                            <option value="">Select Date Column</option>
                                            <?php if($tdata != '') {?>
                                                <?php foreach ($tdata as $table_schema) { ?>
                                                    <?php if( strpos( $table_schema->data_type, 'date' ) !== false ) { ?>
                                                        <option value="{{$table_schema->column_name}}" <?php echo $monitorTable && $monitorTable->date_column_name === $table_schema->column_name ? 'selected="selected"' : '' ?>>{{$table_schema->column_name}}</option>
                                                    <?php } ?>
                                                    <?php if( strpos( $table_schema->data_type, 'timestamp' ) !== false ) { ?>
                                                        <option value="{{$table_schema->column_name}}" <?php echo $monitorTable && $monitorTable->date_column_name === $table_schema->column_name ? 'selected="selected"' : '' ?>>{{$table_schema->column_name}}</option>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>
                                            
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control id_column" name="id_column_name[]">
                                            <option value="">Select ID Column</option>
                                            <?php if($tdata != '') {?>
                                                <?php foreach ($tdata as $table_schema) { ?>
                                                    <option value="{{$table_schema->column_name}}" <?php echo $monitorTable && $monitorTable->id_column_name === $table_schema->column_name ? 'selected="selected"' : '' ?>>{{$table_schema->column_name}}</option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </td>
                                </tr>
                                <?php } } else { ?>
                                <tr>
                                    <td colspan="4">No table found in a selected database.</td>
                                </tr> 
                                <?php } ?>
                            </tbody>
                        </table>
                        <?php if($table_data != '') { ?>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button class="btn btn-primary " id="btnSubmit">Save</button>
                                <a class="btn btn-primary" href="{{route('monitor.showtable',$id)}}">Back</a>
                            </div>
                        </div>
                        <?php } ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function actionFormSubmit(event){
        event.preventDefault();
       var action = jQuery('[name="action"]').val()
       if(action === ''){
          swal ( "Oops!" ,"Please choose at least one action." ,  "error" )
          return false;
       }
       switch(action){
          case 'monitor':
              jQuery('#table-form').find('[type="checkbox"]').prop('checked', true)
              break;
       }       
    }

    $(document).on("click", "#btnSubmit", function (e) {
        e.preventDefault();
        var checkBoxCount=$("input[name='table_name[]']:checked").length;
        var error = 0;
        if(checkBoxCount == 0){
            swal('Select at least one table');
        } else {
            /*$($("input[name='table_name[]']:checked")).each(function() {
                var pclass = $(this).val();
                $("." + pclass +" .form-control").each(function() {
                    if($(this).val() == ''){
                        swal('Please select Date Column and ID Column for the selected table.');
                        error = 1;
                    } else {
                        return true;
                    }
                });
            });*/
            
            if(error === 0){
                $('#table-form').submit();
            }
            
        }
        
    });
</script>

@endsection
