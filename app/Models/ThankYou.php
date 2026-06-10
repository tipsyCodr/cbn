<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThankYou extends Model
{
    protected $table = 'tyfcb_requests';
    protected $fillable = ['from', 'to', 'amount', 'business_type', 'referral_type','comments','active', 'referral_id'];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'active' => 'boolean',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'from');
    }
    
    public function toUser(){
        return $this->belongsTo(User::class, 'to');
    }

    public function referral(){
        return $this->belongsTo(Referral::class, 'referral_id');
    }
}
