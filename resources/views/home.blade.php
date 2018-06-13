@extends('layouts.user')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                    
                    <div class="row" style="margin-top: 10px;">
                         <div class="col-sm-3">
                             <a href="{{route('pipe.index',$user_data->id)}}">
                                 <img src="{{ asset('public/chart.png') }}" style="width: 100%">
                                 <div style="text-align:center;">Anomaly Detection</div>
                             </a>
                        </div>
                        <div class="col-sm-3">
                            <a href="{{route('connection.index',$user_data->id)}}">
                                <img src="{{ asset('public/table.png') }}" style="width: 100%">
                                <div style="margin-top:10px;text-align:center;">Data Monitor</div>
                            </a>
                        </div>
                    </div>
                    
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
