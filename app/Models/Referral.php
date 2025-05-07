<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    protected $table = 'referrals';
    protected $fillable = ['from','to','referral_type','referral_status','referral_contact_name', 'referral_contact_no','referral_contact_email', 'email', 'mobile', 'address','comments','referral_hotness','active'];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'active' => 'boolean',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'from');
    }
    
    public function referredUser(){
        return $this->belongsTo(User::class, 'to');
    }
}
