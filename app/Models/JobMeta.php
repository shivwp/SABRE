<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobMeta extends Model
{
    use HasFactory;
    protected $table = "job_meta";

    protected $fillable = [
        'job_id',
        'key',
        'value',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);

    }
}
