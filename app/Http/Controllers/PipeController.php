<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\Pipe;
Use DB;
Use Auth;

class PipeController extends Controller
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
            $pipedata = DB::table('dim_data_pipe')->where('client_id', $id)->where('active', 'true')->orderBy('created_at', 'DESC')->paginate(5);
          }
        catch (\Exception $e) {
              return 'Client ID is not found in our record.';
                //return $e->getMessage();
        }
        return view('user.pipe.index',compact('pipedata','id','user_data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $user_data =  DB::table('dim_client')->where('user_id', Auth::user()->id)->first();
        return view('user.pipe.create',compact('id','user_data'));
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
            'source_bucket' => 'required',
            'access_key' => 'required',
            'access_secret' => 'required',
            'source_key' => 'required',
            'delimiter' => 'required',
            'pipe_name' => 'required',
            'pipe_description' => 'required',
            'col_date' => 'required',
            'col_val' => 'required',
        ]);
        
         if($request->get('client_id') != ''){
            $uuid = $request->get('client_id');
            if (!is_string($uuid) || (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $uuid) !== 1)) {
                return redirect()->back()->withInput()->with('message','Client ID is invalid !');
            }
        }
        
        $pid = $this->gen_uuid();
        if($request->get('headers')=='true'){
            $headers=true;
        }else{
            $headers=false;
        }
        $email_array = $request->get('notifications_email');
        $email_string = implode(',', $email_array);
        
        $pipedata = new Pipe([
            'id' => strip_tags($pid),
            'client_id' => strip_tags($request->get('client_id')),
            'data_source_bucket' => strip_tags($request->get('source_bucket')),
            'data_source_key' => strip_tags($request->get('source_key')),
            'data_source_region' => strip_tags($request->get('source_region')),
            'pipe_name' => strip_tags($request->get('pipe_name')),
            'pipe_desc' => strip_tags($request->get('pipe_description')),
            'access_key' => strip_tags($request->get('access_key')),
            'access_secret' => strip_tags($request->get('access_secret')),
            'weeks_to_analyze' => '4',
            'preprocessing' => strip_tags($request->get('preprocessing')),
            'email_to' => $email_string,
            'delimiter' => strip_tags($request->get('delimiter')),
            'headers' => $headers,
            'col_val' => $request->get('col_val'),
            'col_date' => $request->get('col_date'),
            'col_include' =>serialize($request->get('col_include')),
            'last_run_ts' => date('Y-m-d h:i:s'),
            'last_run_id' => strip_tags($request->get('client_id')),
        ]);
        $pipedata->save();
        return redirect()->route('pipe.index',$request->get('client_id'))->with('message', 'Data pipe saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($pid)
    {
        try {
            $user_data =  DB::table('dim_client')->where('user_id', Auth::user()->id)->first();
            $pipe_data = Pipe::findOrFail($pid);
            $cid = $pipe_data['client_id'];
            $pipe_data['col_include'] = unserialize($pipe_data['col_include']);

            is_array($pipe_data['col_include']) ? $pipe_data['col_include'] = implode(',', $pipe_data['col_include']) : $pipe_data['col_include'] = '';
            if($pipe_data['col_include'] != ''){
                $pipe_data['col_include'] = $pipe_data['col_include'];
            } else {
                $pipe_data['col_include'] = '';
            }
        }
        catch (\Exception $e) {
              return 'Pipe ID is not found in our record.';
                //return $e->getMessage();
        }

        return view('user.pipe.show',compact('pipe_data','pid','user_data','cid'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($cid , $pid)
    {
        try {
            $user_data =  DB::table('dim_client')->where('user_id', Auth::user()->id)->first();
            //$pipe_data = DB::table('dim_data_pipe')->where('id', $pid)->first();
            $pipe_data = Pipe::findOrFail($pid);
        }
        catch (\Exception $e) {
              return 'Pipe ID is not found in our record.';
                //return $e->getMessage();
        }
        return view('user.pipe.edit',compact('pipe_data','pid','cid','user_data'));
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
            'source_bucket' => 'required',
            'access_key' => 'required',
            'access_secret' => 'required',
            'source_key' => 'required',
            'delimiter' => 'required',
            'pipe_name' => 'required',
            'pipe_description' => 'required',
            'col_date' => 'required',
            'col_val' => 'required',
        ]);
        
        
        $pid = $id;
        if($request->get('headers')=='true'){
            $headers=true;
        }else{
            $headers=false;
        }
        
        //DB::table('dim_data_pipe')->where('id', $id)->update(['active' => 'f']);
        DB::table('dim_data_pipe')->where('id', $id)->delete();
        
        $email_array = $request->get('notifications_email');
        $email_string = implode(',', $email_array);
        
        $pipedata = new Pipe([
            'client_id' => strip_tags($request->get('client_id')),
            'data_source_bucket' => strip_tags($request->get('source_bucket')),
            'data_source_key' => strip_tags($request->get('source_key')),
            'data_source_region' => strip_tags($request->get('source_region')),
            'pipe_name' => strip_tags($request->get('pipe_name')),
            'pipe_desc' => strip_tags($request->get('pipe_description')),
            'access_key' => strip_tags($request->get('access_key')),
            'access_secret' => strip_tags($request->get('access_secret')),
            'weeks_to_analyze' => '4',
            'preprocessing' => strip_tags($request->get('preprocessing')),
            'email_to' => $email_string,
            'delimiter' => strip_tags($request->get('delimiter')),
            'headers' => $headers,
            'col_val' => $request->get('col_val'),
            'col_date' => $request->get('col_date'),
            'col_include' =>serialize($request->get('col_include')),
            'last_run_ts' => date('Y-m-d h:i:s'),
            'last_run_id' => strip_tags($request->get('client_id')),
        ]);
        $pipedata->save();
        return redirect()->route('pipe.index',$request->get('client_id'))->with('message', 'Data pipe saved.');
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
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function inactive($id){
        try {
            $user_data =  DB::table('dim_client')->where('user_id', Auth::user()->id)->first();
            $pipedata = DB::table('dim_data_pipe')->where('client_id', $id)->where('active', 'false')->orderBy('created_at', 'DESC')->paginate(5);
        }
        catch (\Exception $e) {
              return 'Client ID is not found in our record.';
                //return $e->getMessage();
        }
        
        return view('user.pipe.inactive',compact('pipedata','id','user_data'));
    }
    
    public function statusupdate($cid,$pid) {
        $up_data = array('active' => 'true');
        DB::table('dim_data_pipe')->where('client_id', $cid)->where('id', $pid)->update($up_data);
        DB::table('dim_data_stream')->where('data_pipe_id', $pid)->where('client_id', $cid)->update($up_data);
        return redirect()->route('pipe.inactive',$cid)->with('message', 'Data pipe activated.');
    }
    
    
    public function showstreams($cid,$pid) {
        try {
            $user_data =  DB::table('dim_client')
                    ->where('user_id', Auth::user()->id)
                    ->first();
        
            $data_stream = DB::table('dim_data_stream')
                            ->select(DB::raw('id,data_pipe_id,client_id,data_stream_name'))
                            ->where('data_pipe_id', $pid)
                            ->where('client_id', $cid)
                            ->where('active', 'true')
                            ->paginate(10);
        }  catch (\Exception $ex) {
            return 'Data Stream is not found for particular Pipe ID in our record.';
        }
        
        return view('user.pipe.show_streams',compact('cid','user_data','pid','data_stream'));
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
