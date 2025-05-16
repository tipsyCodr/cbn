<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentDetails extends Model
{
    protected $fillable = [
        'user_id',
        'payment_details',
        'payment_screenshot',
        'other_details',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'id','user_id');
    }
}
