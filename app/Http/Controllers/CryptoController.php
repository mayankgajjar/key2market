<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class CryptoController extends Controller
{
    public function show() {
        $db_ext = DB::connection('external_pgsql');
        $coins = array();
        $today_date = date("Y-m-d H:i");
        $last_date = date("Y-m-d H:i",strtotime('-24 hours'));
        $result = $db_ext->select("SELECT symbol, created_ts FROM public.cryto_exchange_rates WHERE (created_ts BETWEEN '".$last_date."' AND '".$today_date."') order by created_ts");
        
        foreach ($result as $value) {
            if(!in_array($value->symbol,$coins)){                
                $coins[] = $value->symbol;
            }
        }
        
        return view('admin.crypto.index',compact('coins'));
    }
    
    public function fullchart($coin) {
        $db_ext = DB::connection('external_pgsql');
        $result = $db_ext->select("SELECT * FROM public.cryto_exchange_rates WHERE symbol='".$coin."' order by created_ts");
        
        return view('admin.crypto.show',compact('coin','result'));
    }
    
    
    
}