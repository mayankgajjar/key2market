<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appuser extends Model
{
    protected $table = "dim_client";
    protected $fillable = ['id','client','active','notification_emails','user_id'];
}
