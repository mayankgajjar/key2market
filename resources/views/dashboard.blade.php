@extends('layouts.app')

@section('content')
<?php
if(Auth::user()->admin == ''){
    echo 'You are not allowed to use admin functionality.';
    die();
}
?>
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

                    Welcome to dashboard ..
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
