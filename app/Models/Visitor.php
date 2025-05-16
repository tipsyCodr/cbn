<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Visitor extends Model
{
    use Notifiable;
    protected $fillable = ['name', 'email', 'phone', 'address', 'company', 'position', 'status'];
    protected $table = 'visitors';
}
