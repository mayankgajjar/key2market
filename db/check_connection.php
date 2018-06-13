<?php

    $host = $_REQUEST['host'];
    $dbname = $_REQUEST['database'];
    $user = $_REQUEST['username'];
    $password = $_REQUEST['password'];
    $port = $_REQUEST['port'];
    $connection_type = $_REQUEST['connection_type'];
   
    /*$host = 'camparound-dwh.c1obhabazcij.eu-central-1.redshift.amazonaws.com';
    $dbname = 'dev';
    $user = 'narola';
    $password = 'yrpZ73q8Z1W';
    $port = '5439';
    $connection_type = 'redshift';*/
    
    /*$host = 'host';
    $dbname = 'anomaly';
    $user = 'test01';
    //$password = 'Test&1234';
    $port = '5432';
    $connection_type = 'postgres';*/
    
    if($connection_type == 'postgres' && $port == '5432'){
        $connection = pg_connect ("host=".$host." port=".$port." dbname=".$dbname." user=".$user." password=".$password."");
        if($connection) {   
            echo 'connected';
        } else {
            echo 'not connected';
        }
    } else if($connection_type == 'redshift' && $port == '5439'){
        $connection = pg_connect ("host=".$host." port=".$port." dbname=".$dbname." user=".$user." password=".$password."");
        if($connection) {   
            echo 'connected';
        } else {
            echo 'not connected';
        }
    } else {
        echo 'Connection type or port is wrong.';
    }
    
   

?>