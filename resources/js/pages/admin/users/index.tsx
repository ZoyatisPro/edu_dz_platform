import { Form, Head, Link, usePage } from '@inertiajs/react';
import { Search } from 'lucide-react';
import Heading from '@/components/heading';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { useTranslation } from '@/lib/i18n';
import type { UserMode } from '@/types';

type AdminUser = {
    id: number;
    name: string;
    email: string;
    phone: string | null;
    roles: UserMode[];
    created_at: string | null;
};

type PaginationLink = {
    url: string | null;
    label: string;
    active: boolean;
};

type PaginatedUsers = {
    data: AdminUser[];
    links: PaginationLink[];
    from: number | null;
    to: number | null;
    total: number;
};

type Props = {
    users: PaginatedUsers;
    filters: {
        search: string;
    };
};

const MODES: UserMode[] = ['student', 'parent', 'teacher', 'assistant', 'admin'];

const STAFF_MODES: UserMode[] = ['assistant', 'admin'];

export default function AdminUsersIndex({ users, filters }: Props) {
    const { auth } = usePage().props;
    const { t } = useTranslation();
    const isAdmin = auth.user?.roles.includes('admin') ?? false;

    return (
        <>
            <Head title={t('admin.users.title')} />

            <div className="flex flex-1 flex-col gap-6 p-4 md:p-6">
                <Heading
                    title={t('admin.users.title')}
                    description={t('admin.users.description')}
                />

                <Form action="/admin/users" method="get" className="max-w-md">
                    {({ processing }) => (
                        <div className="flex gap-2">
                            <Input
                                name="search"
                                defaultValue={filters.search}
                                placeholder={t('admin.users.search_placeholder')}
                                aria-label={t('admin.users.search')}
                            />
                            <Button
                                type="submit"
                                variant="outline"
                                disabled={processing}
                            >
                                <Search className="me-2 size-4" />
                                {t('admin.users.search')}
                            </Button>
                        </div>
                    )}
                </Form>

                {!isAdmin && (
                    <p className="rounded-lg border border-amber-200 bg-amber-50 p-3 text-sm text-amber-700 dark:border-amber-200/20 dark:bg-amber-900/20 dark:text-amber-200">
                        {t('admin.users.staff_only_note')}
                    </p>
                )}

                <Card>
                    <CardContent className="p-0">
                        <div className="overflow-x-auto">
                            <table className="w-full text-sm">
                                <thead className="border-b bg-muted/50 text-start">
                                    <tr>
                                        <th className="p-3 text-start font-medium">
                                            {t('admin.users.user')}
                                        </th>
                                        <th className="p-3 text-start font-medium">
                                            {t('admin.users.contact')}
                                        </th>
                                        <th className="p-3 text-start font-medium">
                                            {t('admin.users.modes')}
                                        </th>
                                        <th className="p-3 text-start font-medium">
                                            {t('admin.users.actions')}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {users.data.length === 0 && (
                                        <tr>
                                            <td
                                                colSpan={4}
                                                className="p-6 text-center text-muted-foreground"
                                            >
                                                {t('admin.users.empty')}
                                            </td>
                                        </tr>
                                    )}
                                    {users.data.map((user) => {
                                        const isSelf =
                                            auth.user?.id === user.id;

                                        return (
                                            <tr
                                                key={user.id}
                                                className="border-b last:border-b-0"
                                            >
                                                <td className="p-3 align-top">
                                                    <div className="font-medium">
                                                        {user.name}
                                                    </div>
                                                    {isSelf && (
                                                        <span className="text-xs text-muted-foreground">
                                                            {t(
                                                                'admin.users.cannot_edit_self',
                                                            )}
                                                        </span>
                                                    )}
                                                </td>
                                                <td className="p-3 align-top">
                                                    <div>{user.email}</div>
                                                    {user.phone && (
                                                        <div className="text-muted-foreground">
                                                            {user.phone}
                                                        </div>
                                                    )}
                                                </td>
                                                <td className="p-3 align-top">
                                                    <div className="flex flex-wrap gap-1">
                                                        {user.roles.length ===
                                                        0 ? (
                                                            <span className="text-muted-foreground">
                                                                —
                                                            </span>
                                                        ) : (
                                                            user.roles.map(
                                                                (mode) => (
                                                                    <Badge
                                                                        key={
                                                                            mode
                                                                        }
                                                                        variant="secondary"
                                                                    >
                                                                        {t(
                                                                            `modes.${mode}`,
                                                                        )}
                                                                    </Badge>
                                                                ),
                                                            )
                                                        )}
                                                    </div>
                                                </td>
                                                <td className="p-3 align-top">
                                                    {isSelf ? (
                                                        <span className="text-muted-foreground">
                                                            —
                                                        </span>
                                                    ) : (
                                                        <Form
                                                            action={`/admin/users/${user.id}/roles`}
                                                            method="patch"
                                                            options={{
                                                                preserveScroll:
                                                                    true,
                                                            }}
                                                            className="space-y-3"
                                                        >
                                                            {({
                                                                processing,
                                                                errors,
                                                            }) => (
                                                                <>
                                                                    <div className="grid grid-cols-2 gap-2">
                                                                        {MODES.map(
                                                                            (
                                                                                mode,
                                                                            ) => {
                                                                                const isStaffMode =
                                                                                    STAFF_MODES.includes(
                                                                                        mode,
                                                                                    );

                                                                                if (
                                                                                    isStaffMode &&
                                                                                    !isAdmin
                                                                                ) {
                                                                                    return user.roles.includes(
                                                                                        mode,
                                                                                    ) ? (
                                                                                        <input
                                                                                            key={
                                                                                                mode
                                                                                            }
                                                                                            type="hidden"
                                                                                            name="roles[]"
                                                                                            value={
                                                                                                mode
                                                                                            }
                                                                                        />
                                                                                    ) : null;
                                                                                }

                                                                                return (
                                                                                    <label
                                                                                        key={
                                                                                            mode
                                                                                        }
                                                                                        className="flex items-center gap-2"
                                                                                    >
                                                                                        <input
                                                                                            type="checkbox"
                                                                                            name="roles[]"
                                                                                            value={
                                                                                                mode
                                                                                            }
                                                                                            defaultChecked={user.roles.includes(
                                                                                                mode,
                                                                                            )}
                                                                                            className="size-4 rounded border-input accent-primary"
                                                                                        />
                                                                                        <span>
                                                                                            {t(
                                                                                                `modes.${mode}`,
                                                                                            )}
                                                                                        </span>
                                                                                    </label>
                                                                                );
                                                                            },
                                                                        )}
                                                                    </div>

                                                                    {errors.roles && (
                                                                        <p className="text-sm text-destructive">
                                                                            {
                                                                                errors.roles
                                                                            }
                                                                        </p>
                                                                    )}

                                                                    <Button
                                                                        size="sm"
                                                                        disabled={
                                                                            processing
                                                                        }
                                                                    >
                                                                        {t(
                                                                            'admin.users.save',
                                                                        )}
                                                                    </Button>
                                                                </>
                                                            )}
                                                        </Form>
                                                    )}
                                                </td>
                                            </tr>
                                        );
                                    })}
                                </tbody>
                            </table>
                        </div>
                    </CardContent>
                </Card>

                {users.links.length > 3 && (
                    <div className="flex flex-wrap items-center gap-1">
                        {users.links.map((link, index) =>
                            link.url ? (
                                <Button
                                    key={index}
                                    asChild
                                    size="sm"
                                    variant={
                                        link.active ? 'default' : 'outline'
                                    }
                                >
                                    <Link
                                        href={link.url}
                                        preserveScroll
                                        dangerouslySetInnerHTML={{
                                            __html: link.label,
                                        }}
                                    />
                                </Button>
                            ) : (
                                <Button
                                    key={index}
                                    size="sm"
                                    variant="ghost"
                                    disabled
                                    dangerouslySetInnerHTML={{
                                        __html: link.label,
                                    }}
                                />
                            ),
                        )}
                    </div>
                )}
            </div>
        </>
    );
}

AdminUsersIndex.layout = {
    breadcrumbs: [
        {
            title: 'admin.users.title',
            href: '/admin/users',
        },
    ],
};
