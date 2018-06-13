<?php
use DB;

    function check_if_known_exist($pipe_id, $stream_id, $client_id, $date_dt){
        $check_known = DB::select( "SELECT * FROM fact_anomaly_known WHERE data_pipe_id='$pipe_id' AND data_stream_id='$stream_id' AND client_id='$client_id' AND date_dt='$date_dt'");

        if(empty($check_known)){
            return true;
        } else {
            return false;
        }
    }
    
    function get_coin_data($coin=""){
        $db_ext = DB::connection('external_pgsql');
        $result = $db_ext->select("SELECT * FROM public.cryto_exchange_rates WHERE symbol='".$coin."' order by created_ts");
        $data = nisl_array_to_json($result);
        return $data;
    }

    function get_coin_name($coin=""){
        $db_ext = DB::connection('external_pgsql');
        $result = $db_ext->select("SELECT name FROM public.cryto_exchange_rates WHERE symbol='".$coin."' LIMIT 1");
        $result = array_map(function ($value) {
               return (array) $value;
           }, $result);
        return $result[0]['name'];
    }
    
    function get_total_supply($coin=""){
        $db_ext = DB::connection('external_pgsql');
        $total_supply = $db_ext->select("SELECT total_supply FROM public.cryto_exchange_rates WHERE symbol='".$coin."' order by rate_last_updated_ts DESC");
        $total_supply = array_map(function ($value) {
              return (array) $value;
        }, $total_supply);
          
        return $total_supply[0]['total_supply'];
    }
    
    function get_24h_coin_data($coin=""){
        $db_ext = DB::connection('external_pgsql');
        $today_date = date("Y-m-d H:i");
        $last_date = date("Y-m-d H:i",strtotime('-24 hours'));
        $results = $db_ext->select("SELECT * FROM public.cryto_exchange_rates WHERE symbol='".$coin."' and (created_ts BETWEEN '".$last_date."' AND '".$today_date."') order by created_ts");
        $data = nisl_array_to_json($results);
        
        return $data;
    }
    
    function nisl_array_to_json($array){
        $array = array_map(function ($value) {
            return (array) $value;
        }, $array);
        
        if(empty($array))
            return;

        $json = "";
        foreach ($array as $value) {
            
            $json .="{";
                $json .="x:".strtotime($value['rate_last_updated_ts'])*1000 .",";
                $json .="y:".$value['price_usd'] .",";
                $json .="percent_change_1h:".$value['percent_change_1h'] .",";
                $json .="percent_change_24h:".$value['percent_change_24h'] .",";
                $json .="percent_change_7d:".$value['percent_change_7d'] .",";
                $json .="volume_24h_usd:'".number_format($value['24h_volume_usd']) ."',";
                $json .="available_supply:'".number_format($value['available_supply']) ."',";
                $json .="total_supply:'".number_format($value['total_supply']) ."'";
            $json .="},";
        }

        return "[".$json."]";
    }
    
    function encryption_decryption_data( $string, $action = 'e' ) {
        // you may change these values to your own
        $secret_key = 'k2m_26OAtjbo73b';
        $secret_iv = 'k2m_n0G8Kak46zb';

        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash( 'sha256', $secret_key );
        $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );

        if( $action == 'e' ) {
            $output = openssl_encrypt( $string, $encrypt_method, $key, 0, $iv );
        } else if( $action == 'd' ){
            $output = openssl_decrypt( $string, $encrypt_method, $key, 0, $iv );
        }

        return $output;
    }

?>