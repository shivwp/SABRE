<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAvailability extends Model
{
    use HasFactory;
        protected $table = "user_availability";

    protected $fillable = [
        'user_id',
        'mon_open',
        'mon_close',
        'tue_open',
        'tue_close',
        'wed_open',
        'wed_close',
        'thu_open',
        'thu_close',
        'fri_open',
        'fri_close',
        'sat_open',
        'sat_close',
        'sun_open',
        'sun_close',
        'not_available',
    ];

}
