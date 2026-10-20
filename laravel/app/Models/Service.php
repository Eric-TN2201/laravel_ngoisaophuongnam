<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'meta_title',
        'meta_description',
        'title',
        'description',
        'image',
        'status',
    ];

    public function scopePosted($query)
    {
        return $query->where('status', 1);
    }
}
