<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\Connections;
Use DB;
Use Auth;

class ConnectionsController extends Controller
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
            $user_data =  DB::table('dim_client')->where('id', $id)->first();
            $connections = DB::table('dim_connections')->where('client_id', $id)->orderBy('created_at', 'DESC')->paginate(10);
        }
        catch (\Exception $e) {
            return 'Client ID is not found in our record.';
        }
        return view('user.connection.index',compact('user_data','connections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        try {
            $user_data =  DB::table('dim_client')->where('user_id', Auth::user()->id)->first();
        }
        catch (\Exception $e) {
            return 'Client ID is not found in our record.';
        }
        
        return view('user.connection.create',compact('id','user_data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'connection_name' => 'required',
            'host' => 'required',
            'port' => 'required',
            'database' => 'required',
            'user_name' => 'required',
            'password' => 'required'
        ]);
        
        $cid = $this->gen_uuid();
        
        try {
            $user = DB::table('dim_client')->where('id', $request->get('client_id'))->first();
            
            $connections = new Connections([
                'id' => $cid,
                'client_id' => $request->get('client_id'),
                'connection_name' => strip_tags($request->get('connection_name')),
                'host' => strip_tags($request->get('host')),
                'port' => strip_tags($request->get('port')),
                'database' => strip_tags($request->get('database')),
                'user_name' => strip_tags($request->get('user_name')),
                'password' => encryption_decryption_data(strip_tags($request->get('password')),'e'),
                'connection_type' => $request->get('connection_type'),
            ]);
            $connections->save();
            
            return redirect()->route('monitor.showtable',$cid);
            //return redirect()->route('connection.index',$request->get('client_id'))->with('message', 'Connection Added successfully !');
            
        }
        catch (\Exception $e) {
            return redirect()->back()->withInput()->with('message','Client ID is not found in our record.');
        }
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
        try {
            $user_data =  DB::table('dim_client')->where('user_id', Auth::user()->id)->first();
            $connection = Connections::findOrFail($id);
        } 
        catch (\Exception $ex) {
            return 'Connections ID is not found in our record.';
        }
        return view('user.connection.edit',compact('connection','id','user_data'));
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
            'connection_name' => 'required',
            'host' => 'required',
            'port' => 'required',
            'database' => 'required',
            'user_name' => 'required',
            'password' => 'required'
        ]);
        
        try {
            $connections = Connections::findOrFail($request->get('id'));
            $connections->id = $request->get('id');
            $connections->connection_name = strip_tags($request->get('connection_name'));
            $connections->host = strip_tags($request->get('host'));
            $connections->port = strip_tags($request->get('port'));
            $connections->database = strip_tags($request->get('database'));
            $connections->user_name = strip_tags($request->get('user_name'));
            $connections->password = encryption_decryption_data(strip_tags($request->get('password')),'e');
            $connections->connection_type = $request->get('connection_type');
            //$connections->connection_type => encryption_decryption_data($request->get('connection_type').'e'),
            
            $connections->save();
            return redirect()->route('connection.index',$request->get('client_id'))->with('message', 'Connection saved.');
        }
        catch (\Exception $e) {
            return redirect()->back()->withInput()->with('message','Connections ID is not found in our record.');
        }
    }

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
