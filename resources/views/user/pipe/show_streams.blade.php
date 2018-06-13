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
  <h2>Data Stream List</h2>
  <table class="table table-bordered" id="user-table" style="text-align:center;">
    <thead>
        <tr class="btn-info"> 
            <th style="text-align: center;">Stream ID</th>
            <th style="text-align: center;">Pipe ID</th>
            <th style="text-align: center;">Client ID</th>
            <th style="text-align: center;">Stream Name</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data_stream as $stream)
            <tr style="background-color:  white;">
                <td><a href="{{ route('chart.show',['cid' => $cid,'sid' => $stream->id, 'date' => date('Ymd'), 'week' => '4'])}}">{{$stream->id}}</a></td>
                <td>{{$stream->data_pipe_id}}</td>
                <td>{{$stream->client_id}}</td>
                <td>{{$stream->data_stream_name}}</td>
            </tr>
        @endforeach
    </tbody>
  </table>
    <div style="text-align: right;">{{ $data_stream->links() }}</div>
</div>
@stop