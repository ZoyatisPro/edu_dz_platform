<?php

namespace App\Support;

/**
 * Permissions attached to the platform roles.
 *
 * The list is intentionally small: it is the skeleton of the permission
 * matrix, grown one feature at a time.
 */
final class RolePermissions
{
    public const VIEW_USERS = 'users.view';

    public const MANAGE_ROLES = 'users.manage-roles';

    public const REVIEW_TEACHERS = 'teachers.review';

    public const MANAGE_GROUPS = 'groups.manage';

    public const MANAGE_CONTENT = 'content.manage';

    public const MANAGE_PLATFORM = 'platform.manage';

    /**
     * @return list<string>
     */
    public static function all(): array
    {
        return [
            self::VIEW_USERS,
            self::MANAGE_ROLES,
            self::REVIEW_TEACHERS,
            self::MANAGE_GROUPS,
            self::MANAGE_CONTENT,
            self::MANAGE_PLATFORM,
        ];
    }

    /**
     * Permissions granted to a role.
     *
     * @return list<string>
     */
    public static function forRole(string $role): array
    {
        return match ($role) {
            UserMode::ADMIN, UserMode::ASSISTANT => self::all(),
            UserMode::TEACHER => [self::MANAGE_GROUPS, self::MANAGE_CONTENT],
            default => [],
        };
    }
}
