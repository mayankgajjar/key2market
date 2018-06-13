<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\Appuser;
use DataTables;
use DB;


class DatatabledataController extends Controller
{
    public function getuser() {
        //DB::statement(DB::raw('set @rownum=0'));
        $user = Appuser::select(['id', 'client', 'notification_emails','active'])->orderBy('created_at', 'dese')->get()->toArray();
        echo '<pre>';
        print_r($user);
        die();
        return Datatables::of($user)
            ->addColumn('action', function ($user) {
                return '<a href="user/edit/'.$user->id.'"><i class="fa fa-pencil" title = "Edit User"></i></a> &nbsp'
                        . '<a href="user/delete/'.$user->id.'" class="del_user"><i class="fa fa-trash" title = "Delete User"></a>';
            })
            ->editColumn('active', function($user){
                if($user->active == 1){
                    return 'Yes';
                } else{
                    return 'No';  
                }                        
            })
            ->make(true);
    }
}
