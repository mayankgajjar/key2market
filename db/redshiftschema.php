<?php
    
    $host = $_REQUEST['host'];
    $dbname = $_REQUEST['database'];
    $user = $_REQUEST['username'];
    $password = $_REQUEST['password'];
    $port = $_REQUEST['port'];
    

    /*$host = 'camparound-dwh.c1obhabazcij.eu-central-1.redshift.amazonaws.com';
    $dbname = 'dev';
    $user = 'narola';
    $password = 'yrpZ73q8Z1W';
    $port = '5439';*/
    
    if($port == '5439'){
        $connection = pg_connect ("host=".$host." port=".$port." dbname=".$dbname." user=".$user." password=".$password."");
        
        if($connection) {
            //echo 'connected';
            
            $schema = "SELECT table_schema FROM information_schema.tables GROUP BY table_schema";
            $schema_list = pg_query($connection, $schema);
            $schema_list_array = pg_fetch_all($schema_list);
            
            echo json_encode($schema_list_array);
               
            /*$table_list_sql = "SELECT table_name FROM information_schema.tables WHERE table_schema='public' AND table_type='BASE TABLE'";
            $table_list = pg_query($connection, $table_list_sql);
            

            if (!$table_list) {echo "An error occurred in table list.\n";exit;}
            $table_list_array = pg_fetch_all($table_list);

            $table = array();
            $columns_data = array();
            foreach ($table_list_array as $key => $table_l){
                $table_name = $table_l['table_name'];
                $get_columns_sql = "SELECT column_name, data_type, table_name FROM information_schema.columns WHERE table_schema = 'public' AND table_name   = '".$table_name."'";
                $columns_list = $table_list = pg_query($connection, $get_columns_sql);
                if (!$columns_list) {echo "An error occurred in columns list.\n";exit;}
                $columns_list_array = pg_fetch_all($columns_list);
                array_push($columns_data,$columns_list_array);
            }

            $columns_data_new = [];
            foreach($columns_data as $i) {	
                $tablename = $i[0]['table_name'];
                $columns_data_new[$tablename] = $i;
            }
            echo json_encode($columns_data_new);*/
        } else {
            echo 'Not connected';
        }
    } else {
        echo 'Not connected';
    }
    
    
    
    
    
    
?>