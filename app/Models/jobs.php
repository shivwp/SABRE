<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobs extends Model
{
    use HasFactory;
    
        protected $table = "jobs";

    protected $fillable = [
        'client_name',
        'title',
        'category',
        'profile_image',
        'number_of_agents',
        'job_location',
        'job_address',
        'assignment_start_date',
        'assignment_end_date',
        'start_time',
        'end_time',
        'point_contactname',
        'point_phonenumber',
        'agent_attire',
        'armed',
        'concealed',
        'status',
        'pay_rate',
    ];

    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }
     public function tasklog()
    {
        return $this->belongsToMany('App\Models\TaskLog');
    }
}
