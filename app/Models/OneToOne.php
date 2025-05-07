<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class OneToOne extends Model
{
    protected $table = 'oto_requests';
    
    protected $fillable = [
        'user_id',
        'met_with',
        'initiated_by',
        'meeting_location',
        'meeting_date',
        'topics'
    ];

    /**
     * Get the user who owns this request
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the user who was met with
     */
    public function metWithUser()
    {
        return $this->belongsTo(User::class, 'met_with');
    }

    /**
     * Get the user who initiated the meeting
     */
    public function initiator()
    {
        return $this->belongsTo(User::class, 'initiated_by');
    }
}
