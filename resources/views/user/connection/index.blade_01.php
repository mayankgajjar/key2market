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
  <a href="{{ route('connection.create',$user_data->id) }}" class="btn btn-info">Add New Connection</a>
  <h2>Connections</h2>  
  <table class="table table-bordered" id="user-table" style="text-align:center;">
    <thead>
        <tr class="btn-info"> 
          <th style="text-align: center;">Connection ID</th>
          <th style="text-align: center;">Connection Name</th>
          <th style="text-align: center;">Host</th>
          <th style="text-align: center;">Database</th>
          <th style="text-align: center;">Username</th>
          <th style="text-align: center;">Port</th>
          <!--<th style="text-align: center;">Password</th>-->
          <th style="text-align: center;">Connection Type</th>
          <th style="text-align: center;">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($connections as $con)
        <tr style="background-color:  white;">
            <td>{{ $con->id }}</td>
            <td>{{ $con->connection_name }}</td>
            <td>{{ $con->host }}</td>
            <td>{{ $con->database }}</td>
            <td>{{ encryption_decryption_data($con->user_name,'d') }}</td>
            <td>{{ $con->port }}</td>
            <!--<td>{{ encryption_decryption_data($con->password,'d') }}</td>-->
            <td>{{ $con->connection_type }}</td>
            <td>
                <a href="{{ route('connection.edit',$con->id) }}"><i class="fa fa-pencil" title = "Edit Connection"></i></a> &nbsp;
                <a><span class="del_connection" data-custom-value="{{$con->id}}"><i class="fa fa-trash" title = "Delete Connection" style="cursor:  pointer;"></i></span></a> &nbsp;
                <a href="{{ route('monitor.showtable',$con->id) }}"><i class="fa fa-link" title = "Connect to Database"></i></a> &nbsp;
            </td>
        </tr>
        @endforeach
    </tbody>
  </table>
  <div style="text-align: right;">{{ $connections->links() }}</div>
</div>

<script>
    $(document).on("click", ".del_connection", function () {
        var id = $(this).data("custom-value");
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this connection!",
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
                    url: '{{url('delete_connection')}}',
                    type: 'POST',
                    data: {'connection_id' : id,'_token':$('input:hidden[name=_token]').val()},
                    success: function(data) {
                        swal(data);
                        setTimeout(function(){location.reload();}, 2000);
                    },
                });
            } else {
              swal("Cancelled", "Connection is safe", "error");
            }
        });
    })
</script>

@stop