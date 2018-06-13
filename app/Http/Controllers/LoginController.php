<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use App\User;

class LoginController extends Controller
{
    public function login(Request $request){
        
        $remember_me = $request->has('remember') ? true : false;
        
        if(Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
            'active' => 't',
        ],$remember_me)) {
            
            $user = User::where('email',$request->email)->first();
            if($user->is_admin()){
                return redirect()->route('dashboard');
            } else {
                return redirect()->route('home');
            }
        } else {
            return redirect()->back()->with('message', 'Email address or password is wrong.');
        }

        
    }
}
