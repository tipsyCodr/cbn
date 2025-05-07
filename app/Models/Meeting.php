<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = [ 'name','date','start_time','end_time','tyfcb','visitors','speakers'];

    
}
