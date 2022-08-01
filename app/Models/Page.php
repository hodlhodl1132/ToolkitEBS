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
        'slug',
        'deleted'
    ];

    /**
     * Get the page category associated with the page
     * 
     * @return \App\Models\PageCategory
     */
    public function pageCategory()
    {
        return $this->hasOne(PageCategory::class, 'id', 'category_id');
    }
}
