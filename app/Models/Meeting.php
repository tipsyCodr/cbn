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
        'speakers',
        'is_recurring',
        'interval',
        'parent_id',
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

    public function parent()
    {
        return $this->belongsTo(Meeting::class, 'parent_id');
    }

    public function occurrences()
    {
        return $this->hasMany(Meeting::class, 'parent_id');
    }
}
