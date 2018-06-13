<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ChartController extends Controller {

    public function show($cid, $stream_id, $datefilter, $wk) {

        $result = DB::select("SELECT data_stream_id, date_dt, value_, is_normal, bottom_limit, top_limit, data_stream_name, data_pipe_id, client_id, true_value, impact_matrix, delta, prev_val, direction, pipe_desc
        FROM public.anomalies WHERE data_stream_id = '$stream_id' AND date_dt>=('$datefilter'::date - interval '$wk week') AND date_dt<='$datefilter' AND client_id = '" . $cid . "'
        order by date_dt ASC");
        
        if (isset($result) && sizeof($result) > 0) {
            $result = array_map(function ($value) {
                return (array) $value;
            }, $result);

            $arr = $result;

            $datefilter_min = $datefilter - ($wk * 7);
            $printable_date = date('Y-m-d', strtotime($datefilter));

            $graphdata = array();
            $graphdata_shadow = array();
            $highlights = array();
            $tabledata = array();
            $impact_arr = array();
            $overal_impact = array();
            $impact_matrix_counter = 0;


            foreach ($arr as $data) {

                if ($data['top_limit']) {
                    $top_limit = round($data['top_limit']);
                } else {
                    $top_limit = $data['value_'];
                }

                if ($data['bottom_limit']) {
                    $bottom_limit = round($data['bottom_limit']);
                } else {
                    $bottom_limit = $data['value_'];
                }

                if ($data['is_normal'] == 'f') {
                    $dateplusone = date('Y-m-d', strtotime($data['date_dt'] . '+1 days'));
                    $dateminusone = date('Y-m-d', strtotime($data['date_dt'] . '-1 days'));
                    $dateplusone = strtotime($dateplusone) * 1000;
                    $dateminusone = strtotime($dateminusone) * 1000;
                    $highlights[] = array($dateminusone, $dateplusone);
                }

                $decoded_impact_data = json_decode($data['impact_matrix']);



                if (!empty($decoded_impact_data)) {

                    $impact_matrix_counter += 1;
                    usort($decoded_impact_data, function($a, $b) {
                        return strcmp(abs($a->impact), ($b->impact));
                    });
                    $reversed = array_reverse($decoded_impact_data);

                    $row_impact_data = array_slice($reversed, 0, 5, true);



                    foreach ($row_impact_data as $obj) {

                        if (!isset($overal_impact[$obj->data_stream_name])) {
                            $overal_impact[$obj->data_stream_name] = array();
                            $overal_impact[$obj->data_stream_name]['abs'] = 0;
                            $overal_impact[$obj->data_stream_name]['neg'] = 0;
                            $overal_impact[$obj->data_stream_name]['overall'] = 0;
                            $overal_impact[$obj->data_stream_name]['pos'] = 0;
                            $overal_impact[$obj->data_stream_name]['overall'] = 0;
                        }
                        if (!isset($overal_impact[$obj->data_stream_name]['abs'])) {
                            $overal_impact[$obj->data_stream_name]['abs'] = 0;
                        }

                        $overal_impact[$obj->data_stream_name]['abs'] += abs($obj->impact);

                        if ($obj->child_direction == 'neg') {
                            $overal_impact[$obj->data_stream_name]['neg'] += $obj->impact;
                            $overal_impact[$obj->data_stream_name]['overall'] += $obj->impact;
                        } else {
                            $overal_impact[$obj->data_stream_name]['pos'] += $obj->impact;
                            $overal_impact[$obj->data_stream_name]['overall'] += $obj->impact;
                        }
                        $overal_impact[$obj->data_stream_name]['data_stream_id'] = $obj->child_data_stream_id;
                    }
                } else {
                    $row_impact_data = '';
                }

                $date_js = strtotime($data['date_dt']) * 1000;

                $fillcolor = '\'\'';
                if ($data['is_normal'] == 'f') {
                    $fillcolor = '\'#7cb5ec\'';
                }

                $tabledata[] = array('date' => $data['date_dt'], 'value' => $data['value_'], 'is_normal' => $data['is_normal'], 'client_id' => $data['client_id'], 'data_pipe_id' => $data['data_pipe_id'], 'true_value' => $data['true_value'], 'impact_data' => $row_impact_data);
                if ($data['delta'] != 0 && $data['prev_val'] != 0) {
                    $delta = round((float) $data['delta'] / $data['prev_val'] * 100, 2);
                    $prev_val = $data['prev_val'];
                    $original_delta = $data['delta'];
                } else {
                    $delta = '0';
                    $prev_val = '\'\'';
                    $original_delta = '\'\'';
                }
                $graphdata[] = '{x: new Date(' . $date_js . '),y:' . $data['value_'] . ',tooltip:' . $delta . ', delta:' . $original_delta . ', prev_val:' . $prev_val . ', marker: {fillColor: ' . $fillcolor . '}}';
                $graphdata_shadow[] = '{x:new Date(' . $date_js . '), low:' . $bottom_limit . ', high:' . $top_limit . '}';
            }

            krsort($impact_arr);
            $tabledata = array_reverse($tabledata);


            $title_query = DB::select("SELECT anm.data_stream_id, anm.date_dt, anm.data_stream_name, anm.data_pipe_id, anm.client_id, pipe.pipe_name , cl.client as client_name FROM public.anomalies anm LEFT JOIN public.dim_data_pipe pipe on anm.data_pipe_id = pipe.id LEFT JOIN public.dim_client cl on anm.client_id =  cl.id WHERE data_stream_id = '$stream_id' AND date_dt>=('$datefilter'::date - interval '$wk week') AND date_dt<='$datefilter' order by date_dt ASC LIMIT 1");
            $result = array_map(function ($value) {
                return (array) $value;
            }, $title_query);

            if ($title_query) {
                $data_stream_name = $title_query[0]->data_stream_name;
                $pipe_name = $title_query[0]->pipe_name;
                $client_name = $title_query[0]->client_name;
                $chart_title = $client_name . ' / ' . $pipe_name . ' / ' . $data_stream_name;
            }
        } else {
            $result = '';
            $arr = '';
            $printable_date = '';
            $chart_title = '';
            $tabledata = '';
            $graphdata = array();
            $graphdata_shadow = array();
            $highlights = array();
            $impact_arr = '';
            $overal_impact = '';
            $impact_matrix_counter = '';
        }

        $pass_data = array('stream_id', 'datefilter', 'wk', 'result', 'arr', 'printable_date', 'chart_title', 'tabledata', 'graphdata',
            'graphdata_shadow', 'highlights', 'impact_arr', 'overal_impact', 'impact_matrix_counter', 'cid');

        return view('chart.show', compact($pass_data));
    }

}
