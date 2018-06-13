<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pipe extends Model
{
    protected $table = "dim_data_pipe";
    protected $fillable = ['id','client_id','data_source_bucket','data_source_key','data_source_region','pipe_name','pipe_desc','access_key','access_secret','weeks_to_analyze','preprocessing','email_to','delimiter','headers','col_val','col_date','col_include','last_run_ts','last_run_id'];
}
