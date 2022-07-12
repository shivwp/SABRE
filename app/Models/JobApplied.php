<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplied extends Model
{
    use HasFactory;
    protected $table = "job_applied";

    protected $fillable = [
        'job_id',
        'user_id',
        'applied_date',
        'status',
    ];

    public function jobs()
    {
        return $this->belongsToMany('App\Models\Jobs','job_id');
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User','user_id');
    }
}
