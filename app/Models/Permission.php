<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'user_id',
        'video_id',
        'status',
        'expires_at',
    ];
    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function video()
    {
        return $this->belongsTo(Video::class);
    }
    public function getIsAccessibleAttribute()
    {
        return $this->status === 'approved' && $this->expires_at !== null &&  $this->expires_at->isFuture();
    }
    public function scopeActive($query)
    {
        return $query->where('status', 'approved')
            ->where('expires_at', '>', now());
    }
}
