<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskLog extends Model
{
    use HasFactory;
    
    protected $table = "task_log";

    protected $fillable = [
        'user_id',
        'job_id',
        'arrive_on_site',
        'document_mileage',
        'call_local',
        'task_status',
    ];

    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }

    public function jobs()
    {
        return $this->belongsToMany('App\Models\Jobs');
    }
}
