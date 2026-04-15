<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'banner',
        'description',
        'address',
        'time_start',
        'status',
        'created_by',
    ];

    protected $casts = [
        'time_start' => 'datetime',
    ];

    /**
     * Relation to the user who created the news/event.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope for posted items.
     */
    public function scopePosted($query)
    {
        return $query->where('status', 1);
    }
}
