<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Response;
use Aws\S3\S3Client;
Use App\Appuser;
use App\User;
Use App\Pipe;
Use App\Connections;
Use DB;
include(public_path() . '/lib/aws/aws-autoloader.php');



class AjaxController extends Controller
{
    public function verify_bucket(Request $request) {
        $accKey = trim($request->get('accKey'));
        $accSecret = trim($request->get('accSecret'));
        $bucket = trim($request->get('bucket'));
        echo shell_exec('python3 -c \'import os; os.chdir("/home/key2mark/anomaly"); from utils.aws import Aws_cli; aws = Aws_cli(); print(aws.check_bucket_loc("'.$accKey.'","'.$accSecret.'","'.$bucket.'"))\'');
    }
    
    public function getfile(Request $request) {
        
        $error_track=false;        
        $access_key=trim($request->get('acessKey'));
        $access_secret=urldecode($request->get('accesSecret'));
        $source_bucket=trim($request->get('dataSource'));
        $source_region=trim($request->get('dataSourceRegion'));
        $source_key=trim($request->get('dataSourceKey'));
        $headers=$request->get('headers');
        $delimiter=$request->get('delimiter');
        
        if(empty($delimiter) || $delimiter==''){$delimiter=',';}
        
        $client = S3Client::factory(array(
            'version' => 'latest',
            'region'  => $source_region,
            'credentials' => array(
                'key'    => $access_key,
                'secret' => $access_secret
            )
        ));
        
        #GET FILE SIZE
        try {
            $s3call_list = $client->listObjects([
                'Bucket' => $source_bucket,
                'Prefix' => $source_key
            ]);
            
        }catch (Throwable $t){ $output='<pre><b>S3 Error: </b>' .$t->getMessage().'</pre>'; $error_track=true;}
        catch(S3Exception $e) {$output='<pre><b>S3 Error: </b>' .$e->getMessage().'</pre>'; $error_track=true;}
        
        if(!$error_track){
            $size = $s3call_list['Contents'][0]['Size'];
        }
        
        #GET OBJECT WITH RANGE
        try {
            $s3call_data_file = $client->getObject([
               'Bucket' => $source_bucket,
               'Key'    => $source_key,
               'Range'  => 'bytes=0='.round($size * 0.1)
            ]);
        }catch (Throwable $t){ $output='<p><b>S3 Error: </b>' .$t->getMessage().'</p>'; $error_track=true;}
        catch(S3Exception $e) {$output='<p><b>S3 Error: </b>' .$e->getMessage().'</p>'; $error_track=true;}
        
       
        if(!$error_track){
            $nisl_s3_data = $s3call_data_file['Body']->__toString();
            $final_output = str_replace( ', ', '' , $nisl_s3_data);
            $outlines=explode("\n",$final_output);

            $output=array();
            foreach($outlines as $outl){
                $output[]=explode($delimiter, $outl);  
            }
            echo $output=json_encode($output);
        }
        
        
    }
    
    public function delete_pipe(Request $request){
        $pipe_id=trim($request->get('pipe_id'));
        $cmd = "python3 /home/key2mark/anomaly/reset_pipe.py $pipe_id 2>&1";
        //$cmd = "python3 /home/www-dev/public_html/key2market/public/anomaly/reset_pipe.py $pipe_id";
        $tmp = exec($cmd);
        
        /*if (is_null($tmp)) {   
            $massage = "Error: We have got the error using the command";
        } else {  
            $massage = "Success : Command is successfully executed.";
        }*/
        
        DB::table('dim_data_pipe')->where('id', '=', $pipe_id)->delete();
        
        echo $tmp;
    }
    
    public function reset_pipe(Request $request) {
        $pipe_id=trim($request->get('pipe_id'));
        
        $cmd = "python3 /home/key2mark/anomaly/reset_pipe.py  $pipe_id 2>&1";
        //$cmd = "python3 /home/www-dev/public_html/key2market/public/anomaly/reset_pipe.py $pipe_id";
        $tmp = exec($cmd);
        
        /*if (is_null($tmp)) {   
            $massage = "Error: We have got the error using the command";
        } else {  
            $massage = "Success : Command is successfully executed.";
        }*/
        
        echo $tmp;
    }
    
    public function showgetfile(Request $request) {
        $error_track=false;        
        $access_key=trim($request->get('acessKey'));
        $access_secret=urldecode($request->get('accesSecret'));
        $source_bucket=trim($request->get('dataSource'));
        $source_region=trim($request->get('dataSourceRegion'));
        $source_key=trim($request->get('dataSourceKey'));
        $headers=$request->get('headers');
        $delimiter=$request->get('delimiter');
        $pid = $request->get('pid');
        $pipe_data = Pipe::findOrFail($pid);
        
        if(empty($delimiter) || $delimiter==''){$delimiter=',';}
        
        $client = S3Client::factory(array(
            'version' => 'latest',
            'region'  => $source_region,
            'credentials' => array(
                'key'    => $access_key,
                'secret' => $access_secret
            )
        ));
        
        #GET FILE SIZE
        try {
            $s3call_list = $client->listObjects([
                'Bucket' => $source_bucket,
                'Prefix' => $source_key
            ]);
            
            
        }catch (Throwable $t){ $output='<pre><b>S3 Error: </b>' .$t->getMessage().'</pre>'; $error_track=true;}
        catch(S3Exception $e) {$output='<pre><b>S3 Error: </b>' .$e->getMessage().'</pre>'; $error_track=true;}
        
      
        if(!$error_track){
            $size = $s3call_list['Contents'][0]['Size'];
        }
        
        #GET OBJECT WITH RANGE
        try {
            $s3call_data_file = $client->getObject([
               'Bucket' => $source_bucket,
               'Key'    => $source_key,
               'Range'  => 'bytes=0='.round($size * 0.1)
            ]);
        }catch (Throwable $t){ $output='<p><b>S3 Error: </b>' .$t->getMessage().'</p>'; $error_track=true;}
        catch(S3Exception $e) {$output='<p><b>S3 Error: </b>' .$e->getMessage().'</p>'; $error_track=true;}
        
        if(!$error_track){
            $nisl_s3_data = $s3call_data_file['Body']->__toString();
            $final_output = str_replace( ', ', '' , $nisl_s3_data);
            $outlines=explode("\n",$final_output);

            $output=array();
            foreach($outlines as $outl){
                $output[]=explode($delimiter, $outl);  
            }
            echo $output=json_encode($output);
        }
        
    }
    
    public function getcoindata(Request $request){
        $coin=trim($request->get('coin'));
        $rowCount = trim($request->get('rowCount'));
        //$coin = $id;
        $coin_name = get_coin_name($coin);
        $total_supply = get_total_supply($coin);
        $json = get_24h_coin_data($coin);
        $numOfCols = 3;
        $bootstrapColWidth = 12 / $numOfCols;
        
        
        $html = '';
        $html .= '<div class="coin-chart col-sm-'.$bootstrapColWidth.'">';
        $html .= '<script>';
                    $html .= 'jQuery( document ).ready(function() {
                                var data = '.$json.';

                                Highcharts.stockChart("nisl-24-container-'.$coin.'", {
                                    rangeSelector: {
                                        enabled: false,
                                    },
                                    xAxis: {type: "datetime"},
                                    title: {
                                        text: ""
                                    },
                                    navigator: {enabled: false},
                                    scrollbar: {enabled: false},
                                    exporting: { enabled: false },
                                    tooltip: {
                                        crosshairs: true,
                                        shared: true,
                                        formatter: function() {
//                                            console.log(this.points);
                                            var s = "<b>" + this.y + "</b>";
                                            jQuery.each(this.points, function (i, datum) {
//                                                console.log(datum.point);
                                                s += "<br/>Percent change 1h = " + datum.point.percent_change_1h;
                                                s += "<br/>Percent change 24h = " + datum.point.percent_change_24h;
                                                s += "<br/>Percent change 7d = " + datum.point.percent_change_7d;
                                                s += "<br/>24h volume usd = " + datum.point.volume_24h_usd;
                                                s += "<br/>Available supply = " + datum.point.available_supply;
                                                s += "<br/>Total supply = " + datum.point.total_supply;
                                            });
                                            return s;
                                        }
                                    },
                                    series: [{
                                        name: "'.$coin.'",
                                        data: data
                                    }]
                                });
                            });

                    ';
                    $html .= '</script>';

                        $html .= '<div class="nisl-parent-block">';
                            $html .= '<h3 class="crypto-title"><a href="/crypto/show/'.$coin.'">'.$coin_name.' 24 hours</a></h3>';
                            $html .= '<div id="nisl-24-container-'.$coin.'" class="nisl-block"></div>';
                            $html .= '<h4 style="text-align: center;font-weight: 700;">Total supply: '.number_format($total_supply).'</h4>';
                        $html .= '</div>';
                    $html .= '</div>';
                    
                    if($rowCount % $numOfCols == 0){
                        $html .='</div><div class="row">';
                    }
                
                return $html;
      
    }
    
    public function getsingalchart(Request $request){
        $coin=trim($request->get('coin'));
        
        $coin_name = get_coin_name($coin);
        $total_supply = get_total_supply($coin);
        $json = get_coin_data($coin);
        $html = '';
            $html .= '<script>';
            $html .= 'jQuery( document ).ready(function() {
                        var data = '.$json.';
                            
                        Highcharts.stockChart("nisl-container-'.$coin.'", {
                            rangeSelector: {
                                allButtonsEnabled: true,
                                buttons: [
                                    {type: "hour",count: 1,text: "1h"},
                                    {type: "hour",count: 12,text: "12h"},
                                    {type: "hour",count: 24,text: "24h"},
                                    {type: "week",count: 1,text: "1w"},
                                    {type: "month",count: 1,text: "1m"},
                                    {type: "month",count: 3,text: "3m"},
                                    {type: "month",count: 6,text: "6m"},
                                    {type: "ytd",text: "YTD"},
                                    {type: "year",count: 1,text: "1y"},
                                    {type: "all",text: "All"}
                                ]
                            },

                            title: {
                                text: "'.$coin_name.' price chart"
                            },

                            tooltip: {
                                crosshairs: true,
                                shared: true,
                                formatter: function() {
//                                    console.log(this);
                                    var s = "<b>" + this.y + "</b>";
                                    jQuery.each(this.points, function (i, datum) {
                                    //console.log(datum.point);
                                        s += "<br/>Percent change 1h = " + datum.point.percent_change_1h;
                                        s += "<br/>Percent change 24h = " + datum.point.percent_change_24h;
                                        s += "<br/>Percent change 7d = " + datum.point.percent_change_7d;
                                        s += "<br/>24h volume usd = " + datum.point.volume_24h_usd;
                                        s += "<br/>Available supply = " + datum.point.available_supply;
                                        s += "<br/>Total supply = " + datum.point.total_supply;
                                    });
                                    return s;
                                }
                            },

                            series: [{
                                turboThreshold: 300000000,
                                name: "'.$coin.'",
                                data: data,
                            }]
                        });
                    });

            ';
            $html .= '</script>';
            
            $html .= '<div id="nisl-container-'.$coin.'" style="height: 400px; min-width: 310px"></div>';
            $html .= '<h4 style="text-align: center;font-weight: 700;">Total supply: '.number_format($total_supply).'</h4>';


            return $html;
        
    }
    
    public function delete_client(Request $request) {
        $client_id =  trim($request->get('client_id'));
        $appuser = Appuser::findOrFail($client_id);
        $user_id = $appuser->user_id;
        $appuser->delete();
        $user = User::findOrFail($user_id);
        $user->delete();
        DB::table('dim_data_pipe')->where('client_id', '=', $client_id)->delete();
        return 'Client Delete successfully !';
    }
    
    public function inactive_pipe_data(Request $request) {
        $pipe_id=trim($request->get('pipe_id'));
        $up_data = array('active' => '0');
        DB::table('dim_data_pipe')->where('id', $pipe_id)->update($up_data);
        return 'Data Pipe Inactive Successfully !';
    }
    
    public function delete_connection(Request $request) {
        $connection_id =  trim($request->get('connection_id'));
        $connection = Connections::findOrFail($connection_id);
        $connection->delete();
        return 'Connection Deleted successfully !';
    }
    
    public function delete_table_data(Request $request) {
        $table_id = trim($request->get('table_id'));
        DB::table('dim_tables')->where('id', '=', $table_id)->delete();
        return 'Monitor Table Deleted successfully !';
    }
    
    public function get_date_id_column(Request $request){
        $table_name = trim($request->get('table_name'));
        $schema_name = trim($request->get('schema_name'));
        $connection_id = trim($request->get('connection_id'));
        $connection_info = Connections::findOrFail($connection_id)->toArray();
        
        $postData = '';
            $params = array(
                'connection_type' => $connection_info['connection_type'],
                'host' => $connection_info['host'],
                'username' => encryption_decryption_data($connection_info['user_name'],'d'),
                'database' => $connection_info['database'],
                'password' => encryption_decryption_data($connection_info['password'],'d'),
                'port' => $connection_info['port'],
                'schema' => $schema_name
            );

            foreach($params as $k => $v) { 
               $postData .= $k . '='.$v.'&'; 
            }
            $postData = rtrim($postData, '&');
            

            if($params['connection_type'] == 'postgres'){
                $url = env('APP_URL', 'default').'/db/pgtabel.php';
                $ch = curl_init(); 
                curl_setopt($ch,CURLOPT_URL,$url);
                curl_setopt($ch,CURLOPT_POST, 1);                //0 for a get request
                curl_setopt($ch,CURLOPT_POSTFIELDS,$postData);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,30);
                curl_setopt($ch,CURLOPT_TIMEOUT, 200);
                $response = curl_exec($ch);
                curl_close ($ch);
                $table_data = json_decode($response);
                
                $table_schemas = $table_data->$table_name;
                
                $output = array();
                $output['date_col'] = '<option value="">Select Date Column</option>';
                $output['id_col'] = '<option value="">Select ID Column</option>';
                
                foreach ($table_schemas as $table_schema) {
                    if( strpos( $table_schema->data_type, 'date' ) !== false ) {
                        $output['date_col'].= '<option value='.$table_schema->column_name.'>'.$table_schema->column_name.'</option>';
                    }
                    if( strpos( $table_schema->data_type, 'timestamp' ) !== false ) {
                        $output['date_col'].= '<option value='.$table_schema->column_name.'>'.$table_schema->column_name.'</option>';
                    }
                }
                
                foreach ($table_schemas as $table_schema) {
                    $output['id_col'].= '<option value='.$table_schema->column_name.'>'.$table_schema->column_name.'</option>';
                }
                
            } else {
                
                $url = env('APP_URL', 'default').'/db/redshifttable.php';
                $ch = curl_init(); 
                curl_setopt($ch,CURLOPT_URL,$url);
                curl_setopt($ch,CURLOPT_POST, 1);                //0 for a get request
                curl_setopt($ch,CURLOPT_POSTFIELDS,$postData);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,30);
                curl_setopt($ch,CURLOPT_TIMEOUT, 200);
                $response = curl_exec($ch);
                curl_close ($ch);
                $table_data = json_decode($response);
                
                $table_schemas = $table_data->$table_name;
                
                $output = array();
                $output['date_col'] = '<option value="">Select Date Column</option>';
                $output['id_col'] = '<option value="">Select ID Column</option>';
                
                foreach ($table_schemas as $table_schema) {
                    if( strpos( $table_schema->data_type, 'date' ) !== false ) {
                        $output['date_col'].= '<option value='.$table_schema->column_name.'>'.$table_schema->column_name.'</option>';
                    }
                    if( strpos( $table_schema->data_type, 'timestamp' ) !== false ) {
                        $output['date_col'].= '<option value='.$table_schema->column_name.'>'.$table_schema->column_name.'</option>';
                    }
                }
                
                foreach ($table_schemas as $table_schema) {
                    $output['id_col'].= '<option value='.$table_schema->column_name.'>'.$table_schema->column_name.'</option>';
                }
            }
            
            return $output;
    }
    
    public function check_connection(Request $request) {
        $data = $request->all();
        
        $postData = '';
        $params = array(
            'connection_type' => $data['connection_type'],
            'host' => $data['host'],
            'username' => $data['username'],
            'database' => $data['database'],
            'password' => $data['password'],
            'port' => $data['port'],
        );
       
        foreach($params as $k => $v) { 
           $postData .= $k . '='.$v.'&'; 
        }
        $postData = rtrim($postData, '&');
        
        $url = env('APP_URL', 'default').'/db/check_connection.php';
                
        $ch = curl_init(); 
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST, 1);                //0 for a get request
        curl_setopt($ch,CURLOPT_POSTFIELDS,$postData);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,30);
        curl_setopt($ch,CURLOPT_TIMEOUT, 200);
        $response = curl_exec($ch);
        curl_close ($ch);
        
        if($response == 'not connected'){
            return 'The system is not able to connect this database, please check connection details.';
        } elseif($response == 'connected') {
            return 'Connection details are perfect.we can connect this database.';
        } else {
            return 'Connection detail is wrong, Would you please verify it?';
        }
        
    }
    
    public function get_table_date_id_column(Request $request){
        
        
        
        $schema_name = trim($request->get('schema_name'));
        $connection_id = trim($request->get('connection_id'));
        $connection_info = Connections::findOrFail($connection_id)->toArray();
        
        $postData = '';
            $params = array(
                'connection_type' => $connection_info['connection_type'],
                'host' => $connection_info['host'],
                'username' => encryption_decryption_data($connection_info['user_name'],'d'),
                'database' => $connection_info['database'],
                'password' => encryption_decryption_data($connection_info['password'],'d'),
                'port' => $connection_info['port'],
                'schema' => $schema_name,
            );

            foreach($params as $k => $v) { 
               $postData .= $k . '='.$v.'&'; 
            }
            $postData = rtrim($postData, '&');
            
            if($params['connection_type'] == 'postgres'){
                
                $url = env('APP_URL', 'default').'/db/pgtabel.php';
                $ch = curl_init(); 
                curl_setopt($ch,CURLOPT_URL,$url);
                curl_setopt($ch,CURLOPT_POST, 1);                //0 for a get request
                curl_setopt($ch,CURLOPT_POSTFIELDS,$postData);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,30);
                curl_setopt($ch,CURLOPT_TIMEOUT, 200);
                $response = curl_exec($ch);
                curl_close ($ch);
                $response_data = json_decode($response);
                
                $output = array();
                $output['table'] = '<option value="">Select Table</option>';
                
                foreach ($response_data as $key => $rdata){
                    $output['table'].= '<option value='.$key.'>'.$key.'</option>';
                }
                
            } else {
                
                $url = env('APP_URL', 'default').'/db/redshifttable.php';
                $ch = curl_init(); 
                curl_setopt($ch,CURLOPT_URL,$url);
                curl_setopt($ch,CURLOPT_POST, 1);                //0 for a get request
                curl_setopt($ch,CURLOPT_POSTFIELDS,$postData);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,30);
                curl_setopt($ch,CURLOPT_TIMEOUT, 200);
                $response = curl_exec($ch);
                curl_close ($ch);
                $response_data = json_decode($response);
                
                $output = array();
                $output['table'] = '<option value="">Select Table</option>';
                
                foreach ($response_data as $key => $rdata){
                    $output['table'].= '<option value='.$key.'>'.$key.'</option>';
                }
            }
        
        return $output;
        
    }
    
}
