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
  <h2>Inactive Data Sources</h2>  
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
                <td>{{$pipe->id}}</td>
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
                   <a href="{{ route('pipestatus.update',['cid' => $id, 'pid' => $pipe->id])}}"><i class="fa fa-thumbs-up" title = "Activate"></i></a>
                </td>
            </tr>
        @endforeach
    </tbody>
  </table>
    <div style="text-align: right;">{{ $pipedata->links() }}</div>
</div>
@stop