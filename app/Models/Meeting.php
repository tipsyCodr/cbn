<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Meeting extends Model
{
    use HasFactory;
    
    protected $table = 'meetings';
    
    protected $fillable = [
        'name',
        'date',
        'start_time',
        'end_time',
        'location',
        'tyfcb',
        'visitors',
        'speakers'
    ];
    
    /**
     * Get the next meeting from current date
     * 
     * @return Meeting|null
     */
    public static function getNextMeeting()
    {
        $today = date('Y-m-d');
        
        return self::whereDate('date', '>=', $today)
            ->orderBy('date', 'asc')
            ->first();
    }

    public function attendances()
    {
        return $this->hasMany(MeetingAttendance::class);
    }
}
