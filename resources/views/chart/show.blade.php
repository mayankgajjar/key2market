@extends('layouts.guest')

@section('content')
<style>
</style>
<div class="container">
    <div class="row">
        <div class="view-data"><a href="{{route('pipe.index',$cid)}}">View data pipe</a></div>
    </div>
    
    <div class="row">
        <?php if(is_array($arr)){ ?>
            <h4><b>Anomalies for stream / <?=$arr[0]['pipe_desc'];?> / <?=$arr[0]['data_stream_name'];?> / for <?php echo $wk; ?> weeks from <?php echo $printable_date; ?></b></h4>
        <?php } ?>
    </div>
    
    <?php if(strtotime($datefilter)<strtotime('today UTC')){ ?>
        <div class="row">
            <div class="view-data"><a href="{{ route('chart.show',['cid' => $cid,'sid' => $stream_id, 'date' => date('Ymd'), 'week' => $wk])}}">Click here to see the latest data</a></div>
        </div>
    <?php } ?>
    
    <div class="row">
        <div class="graphcontainer" style="margin-top: 20px;">
            <div id="graphdiv"></div>
        </div>
    </div>
    
    <?php
    if(!empty($overal_impact)){
        uasort($overal_impact, function($a, $b) {
            return strcmp(abs($a['abs']), ($b['abs']));
        });
        $reversed2 = array_reverse($overal_impact);
    ?>
    <h3>Overall impact from each data stream for <?=$wk?> weeks</h3>
    <ul>
        <li><i>Overall Impact: number of % points of increase or decrease this data stream has contributed to the chart above during the whole period.</i></li>
        <li><i>Absolute Impact: number of % points of absolute data movement this data stream has contributed to the chart above during the whole period.</i></li>
        <li><i>Positive Impact: number of % points of positive (increase) data movement this data stream has contributed to the chart above during the whole period.</i></li>
        <li><i>Negative Impact: number of % points of negative (decline) data movement this data stream has contributed to the chart above during the whole period.</i></li>
    </ul>
    <table class="table table-striped" style="margin-top: 30px;">
        <tr>
            <th>Data Stream (Data Column & Value)</th>
            <th>Overall</th>
            <th>Absolute</th>
            <th>Positive</th>
            <th>Negative</th>
        </tr>
    <?php
        foreach ($reversed2 as $key => $value){
            $colour = '';
            if($value['abs']>0.1){
                $url = route('chart.show',['cid' => $cid,'sid' => $value['data_stream_id'], 'date' => $datefilter, 'week' => $wk]);
                echo "<tr><td>".$key." <a href= '".$url."'>(view)</a></td><td>";
                
                $colour = ($value['overall']<0) ? 'red' : 'blue' ;
                echo '<span style="color: '.$colour.'">'.round($value['overall']*100,1).' p.</span></td><td>';
                echo '<span style="color: black">'.round($value['abs']*100,1).' p.</span></td><td>';
                echo '<span style="color: blue">'.round($value['pos']*100,1).' p.</span></td><td>';
                echo '<span style="color: red">'.round($value['neg']*100,1).' p.</span></td><tr>';
            }

        }
        echo "</table>";
    }
    ?>
    
        <h3><b>Impact Matrix</b></h3>
    <?php if($impact_matrix_counter>0){?>
        
    <p><i>"Expected Anomaly" will tell our systems that this is an expected value and our algorithms will use it for further estimations. All other anomalies are ignored from forecasting.</i></p>
    <div class="graphdata">
        <table class="table table-striped" style="margin-top: 10px;">
            <tr>
		<th>Date</th>
		<th>Value</th>
		<th>Anomaly</th>
                <th colspan="5" style="text-align:center">Impact Matrix</th>
            </tr>
            <?php foreach($tabledata as $tbd){
                if($tbd['is_normal']=='0'){
                        if(check_if_known_exist($tbd['data_pipe_id'], $stream_id, $tbd['client_id'], $tbd['date'])){ 
                            $td_class=' class="td_high_normal"';
                        } else { 
                            $td_class=' class="td_high"';
                        }
                } else {
                    $td_class='';
                } 
            ?>
                <tr>
                    <td style='white-space: nowrap;' <?=$td_class;?>><?=$tbd['date']; ?></td>
                    <td <?=$td_class;?>><?=$tbd['value']; ?></td>
                    <td <?=$td_class;?>><?php if($tbd['is_normal']=='0'){
                            if(check_if_known_exist($tbd['data_pipe_id'], $stream_id, $tbd['client_id'], $tbd['date'])){
                                echo '<div class="knan_call_cont"><a href="#" ajax-data-pipe="'.$tbd['data_pipe_id'].'" ajax-data-stream-id="'.$stream_id.'" ajax-client-id="'.$tbd['client_id'].'" ajax-date="'.$tbd['date'].'"  class="known_anomaly_call btn small">Expected Anomaly</a></div>';
                                echo '<div class="knan_input_cont">
                                <label><small>True value (optional)</small></label><br>
                                <input size="5" placeholder="0.00" type="number" autocomplete="off" step="any" name="true_val">
                                <input name="known_inserted_id" type="hidden" value="">
                                <a href="#" class="known_save_true_val btn small">Save</a>';
                            } else {
                                if($tbd['true_value']!='' && !empty($tbd['true_value'])){$true_val=$tbd['true_value'];}else{$true_val='';}
                                echo '<p>Normal Anomaly '.$true_val.'</p>';
                            }
                        }
                    ?>
                    </td>
                    <?php
                        /*if(isset($tbd['impact_data'])){
                            reset($tbd['impact_data']);
                        }*/
                      
                        for($i=0; $i<5; $i++){
                            echo "<td ".$td_class."  style='white-space: nowrap;'>";
                            if (isset($tbd['impact_data'][$i])){

                                $obj = $tbd['impact_data'][$i];
                                $perc=round($obj->impact,2)*100;
                                $display = "Column ".$obj->data_stream_name.'<br/>';

                                if($obj->child_direction == 'neg'){
                                    echo "<span style='color:red'>";
                                    $display .= $perc.'%';
                                }
                                else{
                                    echo "<span style='color:blue'>";
                                    $display .= '+'.$perc.'%';
                                }
                                echo $display.'</span> ';
                                ?>
                                <a href='{{ route('chart.show',['cid' => $cid,'sid' => $obj->child_data_stream_id, 'date' => $tbd['date'], 'week' => $wk])}}'>(view)</a>
                                <?php
                            }
                            echo "</td>";
                    };?>
                </tr>
            <?php } ?>
	</table>
</div>
<?php } else {
    echo "<p>* No additional data streams with impact on this data have been found. Perhaps this is the lowest drill down column in the data stream.</p>";
}
?>

</div>

<script type='text/javascript' src='http://code.highcharts.com/highcharts.js?ver=4.9.4'></script>
<script type='text/javascript' src='http://code.highcharts.com/highcharts-more.js?ver=4.9.4'></script>
<script type='text/javascript' src="https://code.highcharts.com/modules/exporting.js"></script>

<script>
    var a = 0;
    var AnomaliesValues=[<?php
    $i=1;
    foreach($graphdata as $grval){
        echo $grval;
        if($i<count($graphdata)){echo ',';}
        $i++;
    }; 
    ?>];

    var ShadowValues=[<?php
    $o=1;
    foreach($graphdata_shadow as $grval_shad){
        echo $grval_shad;
        if($o<count($graphdata_shadow)){echo ',';}
        $o++;
    }; 
    ?>];

    var HighlightsH =[<?php
    $u=1;
    foreach($highlights as $hl){
        echo "{color: '#FCFFC5', from: new Date(".$hl[0]."), to: new Date(".$hl[1].")}";
        if($u<count($highlights)){echo ',';}
        $u++;
    }; 
    ?>];
                    
    Highcharts.chart('graphdiv', {
	chart: {
            type: 'spline'
        },
        title: {
            text: '<?php echo $chart_title; ?>',
            x: -20 //center
        },
        subtitle: {
            text: 'Stream <?php if(isset($arr[0]['data_stream_name'])) {echo $arr[0]['data_stream_name'];} ?>',
            x: -20
        },
        xAxis: {
            type: 'datetime',
            dateTimeLabelFormats: {day: '%d/%m/%y'}
        },

        yAxis: {
            title: {text: null}
        },

        tooltip: {
            crosshairs: true,
            shared: true,
            formatter: function () {
	            var result = '';
                    $.each(this.points, function(i, datum) {
	            if(!datum.point.low){
	            result += 'Date: <b>' + Highcharts.dateFormat('%d/%m/%y', new Date(datum.point.x)) + '</b><br>';
				result += 'Value: <b>' + datum.point.y + '</b><br>';
				result += 'Previous Val.: <b>' + datum.point.prev_val + '</b><br>';
				result += 'Delta: <b>' + datum.point.delta + '</b><br>';
	             result += 'Delta (%): <b>' + datum.point.tooltip + '%</b><br>';
	             }
	          });

	          return result;
            //return 'Extra data: <b>' + this.point.tooltip + '</b>';
        }
        },

        legend: {
	    enabled:false,
        },
        series: [{
            name: 'Values',
            data: AnomaliesValues,
            zIndex: 1,
            marker: {
                fillColor: '#C8012C',
            },
        }, {
            name: 'Limits',
            data: ShadowValues,
            type: 'arearange',
            lineWidth: 0,
            linkedTo: ':previous',
            color: Highcharts.getOptions().colors[0],
            fillOpacity: 0.3,
            zIndex: 0,
            
        }]
    });
    
</script>
@endsection