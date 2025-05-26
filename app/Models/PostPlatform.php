<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostPlatform extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'post_id',
        'platform_id',
        'platform_status',
    ];

    /**
     * The possible status values for platform publishing.
     */
    const STATUS_PENDING = 'pending';
    const STATUS_PUBLISHED = 'published';
    const STATUS_FAILED = 'failed';

    /**
     * Get the post that owns the platform association.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the platform that owns the post association.
     */
    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platform::class);
    }
}
