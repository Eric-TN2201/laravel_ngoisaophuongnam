<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function scopeVisible($query)
    {
        return $query->where('status', 1)
            ->whereHas('category', fn ($category) => $category->where('status', 1))
            ->where(function ($query) {
                $query->whereNull('sub_category_id')
                    ->orWhereHas('subCategory', fn ($subCategory) => $subCategory
                        ->where('status', 1)
                        ->whereColumn('sub_categories.category_id', 'products.category_id'));
            });
    }

    protected $fillable = [
        'sub_category_id',
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'thumbnail',
        'meta_title',
        'meta_description',
        'status',
    ];

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
