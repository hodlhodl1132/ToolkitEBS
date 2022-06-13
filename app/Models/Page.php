<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillabled = [
        'content',
        'title',
        'category_id',
        'last_modified_by',
        'slug'
    ];
}
