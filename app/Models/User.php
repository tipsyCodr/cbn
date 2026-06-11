<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\CustomResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'bio',
        'mobile',
        'email',
        'occupation',
        'department',
        'category',
        'designation',
        'company',
        'address',
        'profile_photo',
        'password',
        'due_date',
        'active',
        'role',
        'chapter_id',
        'invite_code'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            if (empty($user->invite_code)) {
                $user->invite_code = self::generateUniqueInviteCode();
            }
        });
    }

    public static function generateUniqueInviteCode()
    {
        do {
            $code = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
        } while (self::where('invite_code', $code)->exists());

        return $code;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'active' => 'boolean',
        ];
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }
    
    public function referral(){
        return $this->hasMany(Referral::class, 'from');
    }
    
    public function receivedReferrals(){
        return $this->hasMany(Referral::class, 'to');
    }
    
    public function oto(){
        return $this->hasMany(OneToOne::class,'user_id');
    }
    public function thankyou(){
        return $this->hasMany(ThankYou::class,'from');
    }
 
    public function chapter()
    {
        return $this->belongsToMany(Chapter::class);
    }

    public function meetingAttendances()
    {
        return $this->hasMany(MeetingAttendance::class);
    }

}
