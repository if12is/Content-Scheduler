<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Platform extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
    ];

    /**
     * Get the users that have this platform activated.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_platforms')
            ->withPivot('is_active')
            ->withTimestamps();
    }

    /**
     * Get the posts scheduled for this platform.
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_platforms')
            ->withPivot('platform_status')
            ->withTimestamps();
    }

    /**
     * Get character limit for the platform based on type.
     */
    public function getCharacterLimit(): int
    {
        return match ($this->type) {
            'twitter' => 280,
            'instagram' => 2200,
            'linkedin' => 3000,
            'facebook' => 63206,
            default => 1000,
        };
    }
}
