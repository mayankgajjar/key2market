<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Connections extends Model
{
    protected $table = "dim_connections";
    protected $fillable = ['id','client_id','connection_name','host','port','database','user_name','password','connection_type'];
}
