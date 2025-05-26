<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'image_url',
        'scheduled_time',
        'status',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'scheduled_time' => 'datetime',
    ];

    /**
     * Get the user that owns the post.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the platforms this post is scheduled for.
     */
    public function platforms(): BelongsToMany
    {
        return $this->belongsToMany(Platform::class, 'post_platforms')
            ->withPivot('platform_status')
            ->withTimestamps();
    }

    /**
     * Scope a query to only include posts with a specific status.
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include posts scheduled for a specific date.
     */
    public function scopeScheduledFor($query, $date)
    {
        return $query->whereDate('scheduled_time', $date);
    }

    /**
     * Scope a query to only include posts that are due for publishing.
     */
    public function scopeDueForPublishing($query)
    {
        return $query->where('status', 'scheduled')
            ->where('scheduled_time', '<=', now());
    }
}
