<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Faqs extends Model
{
    use HasFactory;
     use Sluggable;
        protected $table = "faqs";

    protected $fillable = [
        'title',
        'slug',
        'status',
        'description',
        'id'
    ];


     public function sluggable(): array

    {

        return [

            'slug' => [

                'source' => 'title'

            ]

        ];

    }

}
