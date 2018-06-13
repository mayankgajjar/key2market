@extends('layouts.guest')
@section('content')
{{ csrf_field() }}

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="https://code.highcharts.com/stock/highstock.js?ver=1.0.0"></script>
<script type="text/javascript" src="https://code.highcharts.com/stock/modules/exporting.js?ver=1.0.0"></script>
<script src="http://app.key2market.com/key2market/public/js/chart/crypto-chart-public.js"></script>
<script type="text/javascript" src="http://code.highcharts.com/highcharts-more.js?ver=4.9.4"></script>

<div class="container" >
    <div class="col-md-12" style="background-color: white;">
        <div class="row chart"></div>
    </div>
</div>

<script>
    jQuery( document ).ready(function() {
        
    var coin = '<?php echo $coin ?>'

        $.ajax({
            url: '{{url('getsingalchart')}}',
            type: 'POST',
            data: {'coin':coin,'_token':$('input:hidden[name=_token]').val()},                    
            success: function(data){
                    jQuery('.chart').append(data);
                }
        });
    });   
 
</script>
@endsection