<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorySubscription extends Model
{
    use HasFactory;
    protected $table = "category_subscription";

    protected $fillable = [
        'user_id',
        'cate_id',
    ];


}
