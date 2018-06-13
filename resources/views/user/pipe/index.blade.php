@extends('layouts.user')

@section('content')
<div class="container">
     <?php
        if(!isset($_GET['page'])){
            $page = 1;
        } else {
            $page = $_GET['page'];
        }
    ?>
@if(Session::has('message'))
    <div class="content pt0">
        <div class="alert alert-success">
            <a class="close" data-dismiss="alert">X</a>
            <strong>{{ Session::get('message') }}</strong>
        </div>
    </div>
@endif
    
  <a href="{{ route('pipe.create',$id) }}" class="btn btn-info">Add New Data Source from S3</a>
  <h2>Data Sources</h2>  
  <div style="font-style:italic;margin-bottom:  10px;"> Anomaly data source is an S3 file with date column, dimensions and some data column that is being monitored daily for anomalies and reported on if the anomaly is observed.</div>
  <table class="table table-bordered" id="user-table" style="text-align:center;">
    <thead>
        <tr class="btn-info"> 
            <th style="text-align: center;">ID</th>
            <th style="text-align: center;">Name</th>
            <th style="text-align: center;">Description</th>
            <th style="text-align: center;">Bucket</th>
            <th style="text-align: center;">Data Source Region</th>
            <th style="text-align: center;">Access key</th>
            <th style="text-align: center;">Weeks to Analyze</th>
            <th style="text-align: center;">Preprocessing</th>
            <!--<th style="text-align: center;">Created</th>-->
            <th style="text-align: center;">Last Processed</th>
            <th style="text-align: center;">Notification Email</th>
            <th style="text-align: center;">Active</th>
            <th style="text-align: center;">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($pipedata as $pipe)
            <tr style="background-color:  white;">
                <td><a href="{{ route('streams.show',['cid' => $pipe->client_id, 'pid' => $pipe->id])}}">{{$pipe->id}}</a></td>
                <td>{{$pipe->pipe_name}}</td>
                <td>{{$pipe->pipe_desc}}</td>
                <td>{{$pipe->data_source_bucket}}</td>
                <td>{{$pipe->data_source_region}}</td>
                <td>{{substr($pipe->access_key,0,4).'****'.substr($pipe->access_key,-4,4)}}</td>
                <td>{{$pipe->weeks_to_analyze}}</td>
                <td>{{$pipe->preprocessing}}</td>
                <!--<td>{{$pipe->created_at}}</td>-->
                <td>{{$pipe->last_run_ts}}</td>
                <td>
                    <?php
                       $emaillist = explode(",",$pipe->email_to);
                       foreach($emaillist as $email){
                           echo $email.'<br>';
                       }
                    ?>
                </td>
                <td>@if($pipe->active == '1') {{'Yes'}} @else {{'No'}} @endif</td>
                <td>
                    @if($pipe->active == '1')
                        <a href="{{ route('pipe.show',$pipe->id)}}"><i class="fa fa-eye" title = "Show Data Pipe"></i></a> &nbsp;
                        <a href="{{ route('pipe.edit',['cid' => $pipe->client_id, 'pid' => $pipe->id])}}"><i class="fa fa-pencil" title = "Edit Data Pipe"></i></a> &nbsp;
                        <a><span class="reset_pipe_data" data-custom-value="{{$pipe->id}}"><i class="fa fa-undo" title = "Reset Data Pipe" style="cursor:  pointer;"></i></span></a> &nbsp;
                        <a><span class="delete_pipe_data" data-custom-value="{{$pipe->id}}"><i class="fa fa-trash" title = "Remove Data Pipe" style="cursor:  pointer;"></i></span></a>
                        <a><span class="inactive_pipe_data" data-custom-value="{{$pipe->id}}"><i class="fa fa-power-off" title = "Inactivate Data Pipe" style="cursor:  pointer;"></i></span></a>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
  </table>
    <div style="text-align: right;">{{ $pipedata->links() }}</div>
</div>

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
      <div class="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Modal Header</h4>
              </div>
              <div class="modal-body">
                  <p id="massage_data">Some text in the modal.</p>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
          </div>

      </div>
  </div>

<script>
$(document).on("click",".delete_pipe_data",function(e) {
    //confirm('Are you sure you want to delete this ?');
    //e.preventDefault();
    var pipe_id = $(this).data("custom-value");
    
        swal({
            title: "Are you sure?",
            text: "Are you sure are you want to delete data pipe.",
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
                    url: '{{url('delete_pipe_data')}}',
                    type: 'POST',
                    data: {'pipe_id' : pipe_id,'_token':$('input:hidden[name=_token]').val()},
                    success: function(data) {
                        swal.close()
                        $('.modal-title').html('Delete Data Pipe')
                        $('#massage_data').html(data);
                        $('#myModal').modal('show');
                        setTimeout(function(){
                            location.reload();
                        }, 4000);
                    },
                });
            } else {
              swal("Cancelled", "Data Pipe is safe", "error");
            }
        });
});

$(document).on("click",".reset_pipe_data",function(e) {

    var pipe_id = $(this).data("custom-value");
    
    swal({
        title: "Are you sure?",
        text: "Are you sure are you want to reset data pipe.",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, reset it!",
        cancelButtonText: "No, cancel it!",
        closeOnConfirm: false,
        closeOnCancel: false
      },
      function(isConfirm) {
        if (isConfirm) {
            $.ajax({
                url: '{{url('reset_pipe_data')}}',
                type: 'POST',
                data: {'pipe_id' : pipe_id,'_token':$('input:hidden[name=_token]').val()},
                success: function(data) {
                    swal.close()
                    $('.modal-title').html('Reset Data Pipe')
                    $('#massage_data').html(data);
                    $('#myModal').modal('show');
                },
            });
        } else {
          swal("Cancelled", "Data Pipe is safe", "error");
        }
    });  
});

$(document).on("click",".inactive_pipe_data",function(e) {

    var pipe_id = $(this).data("custom-value");
    
    swal({
        title: "Are you sure?",
        text: "Are you sure are you want to inactive data pipe.",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, inactive it!",
        cancelButtonText: "No, cancel it!",
        closeOnConfirm: false,
        closeOnCancel: false
      },
      function(isConfirm) {
        if (isConfirm) {
            $.ajax({
                url: '{{url('inactive_pipe_data')}}',
                type: 'POST',
                data: {'pipe_id' : pipe_id,'_token':$('input:hidden[name=_token]').val()},
                success: function(data) {
                    swal(data);
                    setTimeout(function(){
                        location.reload();
                    }, 4000);
                },
            });
        } else {
          swal("Cancelled", "Data Pipe is safe", "error");
        }
    });  
});
</script>

@stop