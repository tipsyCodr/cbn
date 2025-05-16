<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PaymentDetails extends Model
{
    protected $fillable = [
        'user_id',
        'payment_details',
        'payment_screenshot',
        'other_details',
    ];

    /**
     * Get the full URL for the payment screenshot.
     *
     * @return string|null
     */
    public function getPaymentScreenshotUrlAttribute()
    {
        if (!$this->payment_screenshot) {
            return null;
        }

        return Storage::url($this->payment_screenshot);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
