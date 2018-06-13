@extends('layouts.user')

@section('content')
<div class="container">
@if(Session::has('message'))
    <div class="content pt0">
        <div class="alert alert-success">
            <a class="close" data-dismiss="alert">X</a>
            <strong>{{ Session::get('message') }}</strong>
        </div>
    </div>
@endif
  <h2>Tables</h2>
    <div style="font-style:italic;margin-bottom:  10px;"> 
        Please select tables that you would like to monitor daily for data inconsistencies.<br>
        Activate Monitoring will monitor the table for empty data<br>
        If you select Date Column for the table, than we will expect daily data in that table and notify you when the daily data has not arrived<br>
        If you select ID column (unique identifier) then we will monitor that table for duplicates against that unique identifier
    </div>
  <table class="table table-bordered" id="user-table" style="text-align:center;">
    <thead>
        <tr class="btn-info"> 
          <!--<th style="text-align: center;">Client ID</th>-->
          <th style="text-align: center;">Connection Name</th>
          <th style="text-align: center;">Schema Name</th>
          <th style="text-align: center;">Table Name</th>
          <th style="text-align: center;">Date Column Name</th>
          <th style="text-align: center;">ID Column Name</th>
          <th style="text-align: center;">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($table_data as $tdata)
        <tr style="background-color:  white;">
            <!--<td>{{ $tdata->client_id }}</td>-->
            <td>{{ $tdata->connection_name }}</td>
            <td>{{ $tdata->schema_name }}</td>
            <td>{{ $tdata->table_name }}</td>
            <td>{{ $tdata->date_column_name }}</td>
            <td>{{ $tdata->id_column_name }}</td>
            <td>
                <a href="{{ route('monitor.edit',$tdata->id) }}"><i class="fa fa-pencil" title = "Edit table info"></i></a> &nbsp;
                <a><span class="del_table_data" data-custom-value="{{$tdata->id}}"><i class="fa fa-trash" title = "Remove table from monitoring" style="cursor:  pointer;"></i></span></a> &nbsp;
            </td>
        </tr>
        @endforeach
    </tbody>
  </table>
  <div style="text-align: right;">{{ $table_data->links() }}</div>
</div>

<script>
    $(document).on("click", ".del_table_data", function () {
        var id = $(this).data("custom-value");
        swal({
            title: "Are you sure?",
            text: "You want to remove this table from monitoring!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel it!",
            closeOnConfirm: false,
            closeOnCancel: false
          },
          function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: '{{url('delete_table_data')}}',
                    type: 'POST',
                    data: {'table_id' : id,'_token':$('input:hidden[name=_token]').val()},
                    success: function(data) {
                        swal(data);
                        setTimeout(function(){location.reload();}, 2000);
                    },
                });
            } else {
              swal("Cancelled!", "The table has not been removed.", "error");
            }
        });
    })
</script>

@stop