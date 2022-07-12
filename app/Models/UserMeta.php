<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{
    use HasFactory;
    protected $table = "user_meta";

    protected $fillable = [
        'user_id',
        'key',
        'value',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);

    }
}
