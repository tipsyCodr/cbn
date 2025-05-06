<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThankYou extends Model
{
    protected $table = 'tyfcb_requests';
    protected $fillable = ['from', 'to', 'amount', 'business_type', 'referral_type','comments','active'];

    protected function user(){
        return $this->belongsToMany(User::class);
    }
}
