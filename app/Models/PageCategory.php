<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageCategory extends Model
{
    use HasFactory;

    /**
     * Get the associated pages for the page category
     * 
     */
    public function pages()
    {
        return $this->hasMany(Page::class, 'category_id', 'id');
    }
}
