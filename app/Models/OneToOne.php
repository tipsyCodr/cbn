<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OneToOne extends Model    
{
    protected $table = 'oto_requests';
    protected $fillable = ['user_id', 'met_with', 'initiated_by', 'meeting_location','meeting_date','topics','active'];
    
    protected function user(){
        return $this->belongsToMany(User::class);
    }
}
