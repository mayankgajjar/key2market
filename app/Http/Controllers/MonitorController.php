<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
Use App\Connections;
use Config;
Use DB;
Use Auth;

class MonitorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        try {
            $user_data =  DB::table('dim_client')->where('user_id', Auth::user()->id)->first();
            $table_data = DB::table('dim_tables')
                        ->select('dim_tables.*', 'dc.connection_name','dc.client_id')
                        ->where('dc.client_id', "$id")
                        ->where('dim_tables.active','true')
                        ->Join('dim_connections as dc', 'dc.id', '=', 'dim_tables.connection_id')
                        ->orderBy('dim_tables.created_at', 'DESC')
                        ->paginate(50);
            
        }
        catch (\Exception $e) {
              return 'Client ID is not found in our record.';
                //return $e->getMessage();
        }
        return view('user.monitor.index',compact('table_data','id','user_data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $connection_info = Connections::findOrFail($id)->toArray();
        $user_data =  DB::table('dim_client')->where('user_id', Auth::user()->id)->first();
       
        $postData = '';
        $params = array(
            'connection_type' => $connection_info['connection_type'],
            'host' => $connection_info['host'],
            'username' => $connection_info['user_name'],
            'database' => $connection_info['database'],
            'password' => encryption_decryption_data($connection_info['password'],'d'),
            'port' => $connection_info['port'],
        );
        
       
        foreach($params as $k => $v) { 
           $postData .= $k . '='.$v.'&'; 
        }
        $postData = rtrim($postData, '&');
        
        if($params['connection_type'] == 'postgres'){
            $url = env('APP_URL', 'default').'/db/pgschema.php';
            $ch = curl_init(); 
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_POST, 1);                //0 for a get request
            curl_setopt($ch,CURLOPT_POSTFIELDS,$postData);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,30);
            curl_setopt($ch,CURLOPT_TIMEOUT, 200);
            $response = curl_exec($ch);
            curl_close ($ch);
            
            if($response == 'Not connected'){
                return redirect()->back()->with('message', 'The system is not able to connect to the database connection. Please check connection details.');
            } else {
                $schema_data = json_decode($response);
            }
            
        } else {
            
            $url = env('APP_URL', 'default').'/db/redshiftschema.php';
            $ch = curl_init(); 
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_POST, 1);                //0 for a get request
            curl_setopt($ch,CURLOPT_POSTFIELDS,$postData);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,30);
            curl_setopt($ch,CURLOPT_TIMEOUT, 200);
            $response = curl_exec($ch);
            curl_close ($ch);
            
            if($response == 'Not connected'){
                return redirect()->back()->with('message', 'The system is not able to connect to the database connection. Please check connection details.');
            } else {
                $schema_data = json_decode($response);
            }
        }
        
        return view('user.monitor.schema',compact('connection_info','id','user_data','schema_data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        
        /*echo '<pre>';
        print_r($_POST);
        die();*/
        
        /*foreach($request->get('date_column_name') as $key=>$value){
            if(is_null($value) || $value == ''){
                unset($_POST['date_column_name'][$key]);
            } else {
                $date_column_name[] = $value;
            }
        }

        foreach($request->get('id_column_name') as $key=>$value){
            if(is_null($value) || $value == '') {
                unset($_POST['id_column_name'][$key]);
            } else {
                $ID_column_name[] = $value;
            }
        }*/
        $already_moniter = $request->get('already_moniter');
        $connection_id = $request->get('connection_id');
        $table_name_array = $request->get('table_name');
        $date_array = $request->get('date_column_name');
        $id_array = $request->get('id_column_name');
        $client_id = $request->get('client_id');
        $schema = $request->get('schema');
        $table_name_val = $request->get('table_name_val');
        
        foreach ($table_name_array as $key => $value) {
            $key_table = array_search($value, $table_name_val);
            
            if (in_array($value, $already_moniter)){
                $update_array = array(
                    'date_column_name' => $date_array[$key_table],
                    'id_column_name' => $id_array[$key_table],
                );
                $data_update = DB::table('dim_tables')->where('connection_id', $connection_id)->where('schema_name', $schema)->where('table_name', $value)->update($update_array);
            } else {
                $ins_array = array(
                    'id' => $this->gen_uuid(),
                    'connection_id' => $connection_id,
                    'schema_name' => $schema,
                    'table_name' => $value,
                    'date_column_name' => $date_array[$key_table],
                    'id_column_name' => $id_array[$key_table],
                    'updated_at' => date('Y-m-d h:i:s', time()),
                    'created_at' => date('Y-m-d h:i:s', time()),
                    'active' => 'true',
                );
                $data_insert = DB::table('dim_tables')->insert($ins_array);
            }
            
            
        }
        return redirect()->route('monitor.index',$client_id)->with('message', 'Table data saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /*try {*/
            $massage = '';
            $user_data =  DB::table('dim_client')->where('user_id', Auth::user()->id)->first();
            $table_data_db = DB::table('dim_tables')->where('id', $id)->first();
            $connection_info = Connections::findOrFail($table_data_db->connection_id)->toArray();
            $schema = $table_data_db->schema_name;
            
            $postData = '';
            $params = array(
                'connection_type' =>$connection_info['connection_type'],
                'host' => $connection_info['host'],
                'username' => $connection_info['user_name'],
                'database' => $connection_info['database'],
                'password' => encryption_decryption_data($connection_info['password'],'d'),
                'port' => $connection_info['port'],
                'schema' => $schema,
            );

            foreach($params as $k => $v) { 
               $postData .= $k . '='.$v.'&'; 
            }
            $postData = rtrim($postData, '&');
            

            if($params['connection_type'] == 'postgres'){
                $schemaurl = env('APP_URL', 'default').'/db/pgschema.php';
                $ch = curl_init(); 
                curl_setopt($ch,CURLOPT_URL,$schemaurl);
                curl_setopt($ch,CURLOPT_POST, 1);                //0 for a get request
                curl_setopt($ch,CURLOPT_POSTFIELDS,$postData);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,30);
                curl_setopt($ch,CURLOPT_TIMEOUT, 200);
                $response = curl_exec($ch);
                curl_close ($ch);
                
                if($response == 'Not connected'){
                    return redirect()->back()->with('message', 'The system is not able to connect to the database connection. Please check connection details.');
                } else {
                    $schema_data = json_decode($response);
                }
                
                foreach ($schema_data as $schemaname){
                    $tschema[] = $schemaname->table_schema;
                }
                
                if (in_array($schema, $tschema)){
                    
                    $tableurl = env('APP_URL', 'default').'/db/pgtabel.php';
                    $ch = curl_init(); 
                    curl_setopt($ch,CURLOPT_URL,$tableurl);
                    curl_setopt($ch,CURLOPT_POST, 1);                //0 for a get request
                    curl_setopt($ch,CURLOPT_POSTFIELDS,$postData);
                    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,30);
                    curl_setopt($ch,CURLOPT_TIMEOUT, 200);
                    $response = curl_exec($ch);
                    curl_close ($ch);
                    if($response == 'Not connected'){
                        return redirect()->back()->with('message', 'The system is not able to connect to the database connection. Please check connection details.');
                    } else {
                        $table_data = json_decode($response);
                        $table_name = $table_data_db->table_name;
                        
                        if (array_key_exists($table_name,$table_data)){
                            $table_schemas = $table_data->$table_name;
                        } else {
                            $massage = 'Table Not Found in Database.';
                            $table_schemas = array();
                        }
                        //$table_schemas = $table_data->$table_name;
                    }
                    
                } else {
                    $table_data = array();
                    $table_data = array();
                    $table_schemas = array();
                    $massage = 'The selected schema is not found in the database';
                }
                
            } else {
                
                $schemaurl = env('APP_URL', 'default').'/db/redshiftschema.php';
                $ch = curl_init(); 
                curl_setopt($ch,CURLOPT_URL,$schemaurl);
                curl_setopt($ch,CURLOPT_POST, 1);                //0 for a get request
                curl_setopt($ch,CURLOPT_POSTFIELDS,$postData);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,30);
                curl_setopt($ch,CURLOPT_TIMEOUT, 200);
                $response = curl_exec($ch);
                curl_close ($ch);
                
                if($response == 'Not connected'){
                    return redirect()->back()->with('message', 'The system is not able to connect to the database connection. Please check connection details.');
                } else {
                    $schema_data = json_decode($response);
                }
                
                $tableurl = env('APP_URL', 'default').'/db/redshifttable.php';
                $ch = curl_init(); 
                curl_setopt($ch,CURLOPT_URL,$tableurl);
                curl_setopt($ch,CURLOPT_POST, 1);                //0 for a get request
                curl_setopt($ch,CURLOPT_POSTFIELDS,$postData);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,30);
                curl_setopt($ch,CURLOPT_TIMEOUT, 200);
                $response = curl_exec($ch);
                curl_close ($ch);
                
                if($response == 'Not connected'){
                    return redirect()->back()->with('message', 'The system is not able to connect to the database connection. Please check connection details.');
                } else {
                    $table_data = json_decode($response);
                    $table_name = $table_data_db->table_name;
                    $table_schemas = $table_data->$table_name;
                }
            }
        /*} 
        catch (\Exception $ex) {
            return 'Table Data ID is not found in our record.';
        }*/
        return view('user.monitor.edit',compact('table_data','id','user_data','table_schemas','table_data_db','schema_data','massage','connection_info'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'table_name' => 'required',
            'schema_name' => 'required',
        ]);
        
        try {
            $update_array = array(
                'table_name' => $request->get('table_name'),
                'date_column_name' => $request->get('date_column_name'),
                'id_column_name' => $request->get('id_column_name'),
                'schema_name' => $request->get('schema_name'),
                'updated_at' => date('Y-m-d h:i:s', time()),
            );
            DB::table('dim_tables')->where('id', $id)->update($update_array);
        } 
        catch (\Exception $ex) {
            return 'Table Data ID is not found in our record.';
        }
        
        return redirect()->route('monitor.index',$request->get('client_id'))->with('message', 'Table data saved.');    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function table(Request $request, $id) {
         
        $connection_info = Connections::findOrFail($id)->toArray();
        $user_data =  DB::table('dim_client')->where('user_id', Auth::user()->id)->first();
        
        $postData = '';
        $schema = $request->schema;
        $params = array(
            'connection_type' => $connection_info['connection_type'],
            'host' => $connection_info['host'],
            'username' => $connection_info['user_name'],
            'database' => $connection_info['database'],
            'password' => encryption_decryption_data($connection_info['password'],'d'),
            'port' => $connection_info['port'],
            'schema' => $schema,
        );
              
        foreach($params as $k => $v) { 
           $postData .= $k . '='.$v.'&'; 
        }
        $postData = rtrim($postData, '&');
        $selectedTables = DB::table('dim_tables')->select('*')->where([['dim_tables.connection_id','=', $id],['dim_tables.schema_name', '=', $schema],['dc.client_id', '=' ,"$user_data->id"],['dim_tables.active', '=' ,'t']])->Join('dim_connections as dc', 'dc.id', '=', 'dim_tables.connection_id')->get();
        
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
            
            if($response == 'Not connected'){
                return redirect()->back()->with('message', 'The system is not able to connect to the database connection. Please check connection details.');
            } else {
                $table_data = json_decode($response);                
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
            
            if($response == 'Not connected'){
                return redirect()->back()->with('message', 'The system is not able to connect to the database connection. Please check connection details.');
            } else {
                $table_data = json_decode($response);
            }
        }       
        
        return view('user.monitor.showtable',compact('connection_info','id','user_data','table_data','schema', 'selectedTables'));        
    }
    
    // generate UUID
    public function gen_uuid() {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,

            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }
}
