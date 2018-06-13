<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\Appuser;
use App\User;
Use DB;

class UserController extends Controller
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
    public function index()
    {
        //$users = DB::table('dim_client')->where('admin', false)->orderBy('created_at', 'DESC')->paginate(10);
        
        $users = DB::table('dim_client')
            ->join('users', 'users.id', '=', 'dim_client.user_id')
            ->select('dim_client.*')
            ->where('users.admin', false)
            ->orderBy('dim_client.created_at', 'DESC')
            ->paginate(10);
        
        return view('admin.user.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.user.create');
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
            //'id' => 'required',
            'client' => 'required|string|',
            'notification_emails' => 'required|email|string',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);
        
        /*if($request->get('id') != ''){
            $uuid = $request->get('id');
            if (!is_string($uuid) || (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $uuid) !== 1)) {
                return redirect()->back()->withInput()->with('message','Client ID is invalid !');
            }
            $number_id = DB::table('dim_client')->where('id', '=', $uuid)->count();
            if($number_id > 0){
                return redirect()->back()->withInput()->with('message','Client ID is already in use please try a different Client ID');
            }
        }*/
        
        $cid = $this->gen_uuid();
        
        if($request->get('email') != ''){
            $user_email = $request->get('email');
            $number_email = DB::table('users')->where('email', '=', $user_email)->count();
            if($number_email > 0){
                return redirect()->back()->withInput()->with('message','User email address is already in use. Please try a different email address.');
            }
        }
        
        $user = new User([
            'name' => strip_tags($request->get('client')),
            'email' => strip_tags($request->get('email')),
            'password' => strip_tags(bcrypt($request->get('password'))),
            'remember_token' => 'MEh8j4XtQbPbrYF5oxqwbJDK2Z5s6LKd5YDzIlJMMnFu6VDtj8711MIXMhSP',
            'admin' => 'false',
        ]);
        $user->save();
        $client = $user->id;
       
        $userdata = new Appuser([
            'id' => $cid,
            'client' => strip_tags($request->get('client')),
            'notification_emails' => strip_tags($request->get('notification_emails')),
            'active' => $request->get('active'),
            'created_ts' => date('Y- m-d h:i:s', time()),
            'user_id' => $client,
        ]);
        $userdata->save();
        return redirect('/user')->with('message', 'User saved.');
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
            $appuser = Appuser::findOrFail($id);
        } 
        catch (\Exception $ex) {
            return 'Client ID is not found in our record.';
        }
        
        return view('admin.user.edit',compact('appuser','id'));
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
        $appuser = Appuser::findOrFail($id);
        $user_id = $appuser->user_id;
        $user = User::findOrFail($user_id);
        $old_password = $user->password;
        
        $this->validate($request, [
            'client' => 'required',
            'notification_emails' => 'required',
        ]);
        
        $appuser->client = strip_tags($request->get('client'));
        $appuser->notification_emails = strip_tags($request->get('notification_emails'));
        $appuser->active = $request->get('active');
        
        if($request->get('active') == 'false'){
            $up_data = array('active' => $request->get('active'));
            DB::table('dim_data_pipe')->where('client_id', $id)->update($up_data);
            DB::table('dim_data_stream')->where('client_id', $id)->update($up_data);
            DB::table('users')->where('id', $user_id)->update($up_data);
        } else {
            $up_data = array('active' => $request->get('active'));
            DB::table('users')->where('id', $user_id)->update($up_data);
        }
        
        if($request->get('password') != ''){
            if($old_password != bcrypt($request->get('password'))){
                $user_array = array(
                    'name' => $request->get('client'),
                    'password' => bcrypt($request->get('password'))
                );
            } else {
                $user_array = array(
                    'name' => $request->get('client'),
                );
            }
        } else {
            $user_array = array(
                'name' => $request->get('client'),
            );
        }
        $appuser->save();
        DB::table('users')->where('id', $user_id)->update($user_array);
        
        return redirect('/user')->with('message', 'User saved.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $appuser = Appuser::findOrFail($id);
        $user_id = $appuser->user_id;
        $appuser->delete();
        $user = User::findOrFail($user_id);
        $user->delete();
        return redirect('/user')->with('message', 'User deleted.');
    }
    
    public function passwordsend(Request $request){
        echo $request->email;
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
