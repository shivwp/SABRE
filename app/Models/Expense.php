<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
        protected $table = "expense";

    protected $fillable = [
        'user_id',
        'job_id',
        'title',
        'image',
        'description',
    ];
}
