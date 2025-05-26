<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    /**
     * Log an activity.
     *
     * @param string $action The action performed
     * @param string|null $entityType The type of entity (post, platform, etc.)
     * @param int|null $entityId The ID of the entity
     * @param string|null $description Additional description
     * @param array|null $metadata Any additional data
     * @param User|null $user The user who performed the action (defaults to current authenticated user)
     * @return ActivityLog
     */
    public function log(
        string $action,
        ?string $entityType = null,
        ?int $entityId = null,
        ?string $description = null,
        ?array $metadata = null,
        ?User $user = null
    ): ActivityLog {
        $user = $user ?? Auth::user();

        return ActivityLog::create([
            'user_id' => $user?->id,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'description' => $description,
            'metadata' => $metadata,
        ]);
    }

    /**
     * Log a create action.
     *
     * @param string $entityType
     * @param int $entityId
     * @param string|null $description
     * @param array|null $metadata
     * @param User|null $user
     * @return ActivityLog
     */
    public function logCreate(
        string $entityType,
        int $entityId,
        ?string $description = null,
        ?array $metadata = null,
        ?User $user = null
    ): ActivityLog {
        return $this->log(
            ActivityLog::ACTION_CREATE,
            $entityType,
            $entityId,
            $description,
            $metadata,
            $user
        );
    }

    /**
     * Log an update action.
     *
     * @param string $entityType
     * @param int $entityId
     * @param string|null $description
     * @param array|null $metadata
     * @param User|null $user
     * @return ActivityLog
     */
    public function logUpdate(
        string $entityType,
        int $entityId,
        ?string $description = null,
        ?array $metadata = null,
        ?User $user = null
    ): ActivityLog {
        return $this->log(
            ActivityLog::ACTION_UPDATE,
            $entityType,
            $entityId,
            $description,
            $metadata,
            $user
        );
    }

    /**
     * Log a delete action.
     *
     * @param string $entityType
     * @param int $entityId
     * @param string|null $description
     * @param array|null $metadata
     * @param User|null $user
     * @return ActivityLog
     */
    public function logDelete(
        string $entityType,
        int $entityId,
        ?string $description = null,
        ?array $metadata = null,
        ?User $user = null
    ): ActivityLog {
        return $this->log(
            ActivityLog::ACTION_DELETE,
            $entityType,
            $entityId,
            $description,
            $metadata,
            $user
        );
    }

    /**
     * Log a login action.
     *
     * @param User $user
     * @param array|null $metadata
     * @return ActivityLog
     */
    public function logLogin(User $user, ?array $metadata = null): ActivityLog
    {
        return $this->log(
            ActivityLog::ACTION_LOGIN,
            'user',
            $user->id,
            "User {$user->name} logged in",
            $metadata,
            $user
        );
    }

    /**
     * Log a logout action.
     *
     * @param User $user
     * @param array|null $metadata
     * @return ActivityLog
     */
    public function logLogout(User $user, ?array $metadata = null): ActivityLog
    {
        return $this->log(
            ActivityLog::ACTION_LOGOUT,
            'user',
            $user->id,
            "User {$user->name} logged out",
            $metadata,
            $user
        );
    }

    /**
     * Log a publish action.
     *
     * @param string $entityType
     * @param int $entityId
     * @param string|null $description
     * @param array|null $metadata
     * @param User|null $user
     * @return ActivityLog
     */
    public function logPublish(
        string $entityType,
        int $entityId,
        ?string $description = null,
        ?array $metadata = null,
        ?User $user = null
    ): ActivityLog {
        return $this->log(
            ActivityLog::ACTION_PUBLISH,
            $entityType,
            $entityId,
            $description,
            $metadata,
            $user
        );
    }
}
