@extends('layouts.user')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Select Schema</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" id="schema_form" action="{{ route('monitor.table',$id) }}">
                        {{ csrf_field() }}
                        
                        <input type="hidden" name="client_id" value="{{$user_data->id}}"/>
                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">Select Schema</label>
                            <div class="col-md-6">
                                <?php foreach($schema_data as $key => $sdata ){ ?>
                                    <div class="radio">
                                        <label><input type="radio" name="schema" value="{{$sdata->table_schema}}" class="cls_schema">{{$sdata->table_schema}}</label>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button class="btn btn-primary " id="btnSubmit">Next</button>
                                <a class="btn btn-primary" href="{{route('connection.index',$user_data->id)}}">Back</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    
    $(document).on('click', '#btnSubmit', function(event) {
        if($('input:radio:checked').length > 0){
            $('#schema_form').submit();
        }else{
            swal('Please select at least one schema');
            return false;
        }
    });
    
</script>

@endsection
