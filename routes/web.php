<?php
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('phpinfo', function () {
    echo phpinfo();
});

Route::get('/', function () {
    if (Auth::user() != '') {
        if(Auth::user()->admin == 'true'){
            return redirect('/dashboard');
        } else {
            return redirect('/home');
        }
        //return redirect('/home');
    }
    
    return view('auth.login');
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::post('login/custom',['uses' => 'LoginController@login', 'as' => 'login.custom']);

Route::group(['middleware' => 'auth'], function(){
    route::get('/home', function(){
            $user_data =  DB::table('dim_client')->where('user_id', Auth::user()->id)->first();
            return view('home',compact('user_data'));
    })->name('home'); 

    route::get('/dashboard', function(){
            return view('dashboard');
    })->name('dashboard');

});

/*  Ajax */
    Route::post('verify_bucket', 'AjaxController@verify_bucket');
    Route::post('getfile', 'AjaxController@getfile');
    Route::post('showgetfile', 'AjaxController@showgetfile');
    Route::post('delete_pipe_data', 'AjaxController@delete_pipe');
    Route::post('reset_pipe_data', 'AjaxController@reset_pipe');
    Route::post('getcoindata', 'AjaxController@getcoindata');
    Route::post('getsingalchart', 'AjaxController@getsingalchart');
    Route::post('delete_client', 'AjaxController@delete_client');
    Route::post('inactive_pipe_data', 'AjaxController@inactive_pipe_data');
    Route::post('delete_connection', 'AjaxController@delete_connection');
    Route::post('delete_table_data', 'AjaxController@delete_table_data');
    Route::post('get_date_id_column', 'AjaxController@get_date_id_column');
    Route::post('check_connection', 'AjaxController@check_connection');
    Route::post('get_table_date_id_column', 'AjaxController@get_table_date_id_column');
/* -------- */

/*  User Route */
    Route::get('user', array('as' => 'user.index', 'uses' => 'UserController@index'));
    Route::get('user/add', array('as' => 'user.create', 'uses' => 'UserController@create'));
    Route::post('user/store', array('as' => 'user.store', 'uses' => 'UserController@store'));
    Route::get('user/edit/{id}', array('as' => 'user.edit', 'uses' => 'UserController@edit'));
    Route::patch('user/update/{id}', array('as' => 'user.update', 'uses' => 'UserController@update'));
    Route::get('user/delete/{id}', array('as' => 'user.delete', 'uses' => 'UserController@destroy'));
    Route::get('user/getuser', array('as'=>'user.getuser','uses'=>'DatatabledataController@getuser'));
    Route::post('user/passwordsend', array('as' => 'user.passwordsend', 'uses' => 'PasswordController@passwordsend'));
    Route::post('user/resetpassword', array('as' => 'user.resetpassword', 'uses' => 'PasswordController@resetpassword'));
/* -------- */

/*  Pipe Route */
    Route::get('client/{cid}', array('as' => 'pipe.index', 'uses' => 'PipeController@index'));
    Route::get('pipe/add/{cid}', array('as' => 'pipe.create', 'uses' => 'PipeController@create'));
    Route::get('pipe/show/{pid}', array('as' => 'pipe.show', 'uses' => 'PipeController@show'));
    Route::post('pipe/store', array('as' => 'pipe.store', 'uses' => 'PipeController@store'));
    Route::get('pipe/edit/{cid}/{pid}', array('as' => 'pipe.edit', 'uses' => 'PipeController@edit'));
    Route::patch('pipe/update/{pid}', array('as' => 'pipe.update', 'uses' => 'PipeController@update'));
    Route::get('pipe/delete/{pid}', array('as' => 'pipe.delete', 'uses' => 'PipeController@destroy'));
    Route::get('data/{cid}', array('as' => 'pipe.inactive', 'uses' => 'PipeController@inactive'));
    Route::get('pipestatus/{cid}/{pid}', array('as' => 'pipestatus.update', 'uses' => 'PipeController@statusupdate'));
    Route::get('streams/show/{cid}/{pid}', array('as' => 'streams.show', 'uses' => 'PipeController@showstreams'));
/* -------- */

/*  Anomaly Route */
    Route::get('anomaly/{cid}/{sid}/{date}/{week}', array('as' => 'chart.show', 'uses' => 'ChartController@show'));
/* -------- */

/*  Crypto Route */
    Route::get('crypto/', array('as' => 'crypto', 'uses' => 'CryptoController@show'));
    Route::get('crypto/show/{id}', array('as' => 'crypto.show', 'uses' => 'CryptoController@fullchart'));
/* -------- */

/*  Connections */
    Route::get('connection/{cid}', array('as' => 'connection.index', 'uses' => 'ConnectionsController@index'));
    Route::get('connection/add/{cid}', array('as' => 'connection.create', 'uses' => 'ConnectionsController@create'));
    Route::post('connection/store', array('as' => 'connection.store', 'uses' => 'ConnectionsController@store'));
    Route::get('connection/edit/{id}', array('as' => 'connection.edit', 'uses' => 'ConnectionsController@edit'));
    Route::patch('connection/update/{id}', array('as' => 'connection.update', 'uses' => 'ConnectionsController@update'));
    Route::get('connection/delete/{id}', array('as' => 'connection.delete', 'uses' => 'ConnectionsController@destroy'));
/* -------- */

/*  Monitored Tables */
    Route::get('monitor/{cid}', array('as' => 'monitor.index', 'uses' => 'MonitorController@index'));
    Route::get('monitor/add/{id}', array('as' => 'monitor.showtable', 'uses' => 'MonitorController@create'));
    Route::post('monitor/store', array('as' => 'monitor.store', 'uses' => 'MonitorController@store'));
    Route::get('monitor/edit/{id}', array('as' => 'monitor.edit', 'uses' => 'MonitorController@edit'));
    Route::patch('monitor/update/{id}', array('as' => 'monitor.update', 'uses' => 'MonitorController@update'));
    Route::post('monitor/table/{id}', array('as' => 'monitor.table', 'uses' => 'MonitorController@table'));
    Route::get('monitor/table/{id}', array('as' => 'monitor.table', 'uses' => 'MonitorController@create'));
/* -------- */

