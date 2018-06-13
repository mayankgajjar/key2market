<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\Appuser;
use App\User;
Use DB;
use Mail;

class PasswordController extends Controller {

    public function passwordsend(Request $request) {
        $email = $request->email;
        
        try {
            $user = DB::table('users')->where('email', $email)->first();
            if($user->admin != 1){
                return redirect()->back()->with('message','This functionality is only for admin. if you forget the password please contact to admin.');
            }

            $subject = 'Password Reset';
            $token = $this->gen_uuid();
            $data = array('token' => $token);

            DB::table('password_resets')->where('email', $request->email)->update($data);

            $data = array('token' => $token);
            Mail::send('password', $data, function($message) use ($request) {
                $message->to($request->email, 'Key2market.com')->subject('Password Reset');
                $message->from('kirill@key2market.com', 'Key2market.com');
            });
            return redirect()->back()->with('message','Email Sent. Check your inbox.');
        } 
        catch (\Exception $ex) {
            return redirect()->back()->withInput()->with('message','Email addres is not found in our records.');
        }
    }
    
    public function resetpassword(Request $request) {
        $email = $request->email;
        $password = bcrypt($request->password);
        $token = $request->token;
        
        try {
            $user = DB::table('users')->where('email', $email)->first();
            if($user->admin == 1){
                $number = DB::table('password_resets')->where('email', $email)->where('token', $token)->count();
            
                if($number == 1){
                    DB::table('users')->where('email', $email)->update(['password' => $password]);
                    DB::table('password_resets')->where('email', $email)->update(['token' => '$2y$10$BsWaPuX5.gN6q/28En/cju409JEw/NOR17/1UJqaR/Q3VTnTybp1y']);
                    return redirect()->back()->with('message','Password Change successfully. Please try to login with new password. !');
                } else {
                    return redirect()->back()->with('message','Your token is expired so you are not able to Password Change !');
                }
            } else {
                return redirect()->back()->withInput()->with('message','Please enter admin email address.');
            }
        } 
        catch (\Exception $ex) {
            return redirect()->back()->withInput()->with('message','Email addres is not found in our recods.');
        }
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
