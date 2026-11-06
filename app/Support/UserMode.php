<?php

namespace App\Support;

/**
 * The account modes available on the platform.
 *
 * A user may hold several modes at once (for example a teacher who is also a
 * parent). Student and parent modes are self-service, teacher mode requires an
 * administrator decision, and staff modes are never self-assigned.
 */
final class UserMode
{
    public const STUDENT = 'student';

    public const PARENT = 'parent';

    public const TEACHER = 'teacher';

    public const ASSISTANT = 'assistant';

    public const ADMIN = 'admin';

    /**
     * Modes a user may activate without approval.
     *
     * @var list<string>
     */
    public const SELF_SERVICE = [self::STUDENT, self::PARENT];

    /**
     * Modes only an administrator may grant.
     *
     * @var list<string>
     */
    public const STAFF = [self::ASSISTANT, self::ADMIN];

    /**
     * All available modes, in display order.
     *
     * @var list<string>
     */
    public const ALL = [self::STUDENT, self::PARENT, self::TEACHER, self::ASSISTANT, self::ADMIN];

    /**
     * The mode used by default when a user holds several of them.
     *
     * @var list<string>
     */
    public const PRIORITY = [self::ADMIN, self::ASSISTANT, self::TEACHER, self::PARENT, self::STUDENT];

    /**
     * @return list<string>
     */
    public static function assignableByAdmin(): array
    {
        return self::ALL;
    }

    public static function isStaff(string $mode): bool
    {
        return in_array($mode, self::STAFF, true);
    }

    public static function isSelfService(string $mode): bool
    {
        return in_array($mode, self::SELF_SERVICE, true);
    }

    /**
     * Pick the first mode the user owns, following the priority order.
     *
     * @param  list<string>  $owned
     */
    public static function defaultFor(array $owned): ?string
    {
        foreach (self::PRIORITY as $mode) {
            if (in_array($mode, $owned, true)) {
                return $mode;
            }
        }

        return null;
    }
}
