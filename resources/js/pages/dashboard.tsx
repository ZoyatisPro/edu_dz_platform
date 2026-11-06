import { Head, Link, usePage } from '@inertiajs/react';
import {
    ArrowRight,
    Settings,
    ShieldCheck,
    UserCog,
    UserRound,
} from 'lucide-react';
import Heading from '@/components/heading';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { useTranslation } from '@/lib/i18n';
import { dashboard } from '@/routes';
import { edit } from '@/routes/profile';

export default function Dashboard() {
    const { auth, activeMode } = usePage().props;
    const { t } = useTranslation();
    const user = auth.user;

    const shortcuts = [
        {
            title: t('nav.space'),
            description: t('dashboard.open_space'),
            href: '/space',
            icon: UserCog,
        },
        {
            title: t('nav.settings'),
            description: t('dashboard.open_settings'),
            href: edit().url,
            icon: Settings,
        },
    ];

    if (user?.is_staff) {
        shortcuts.push({
            title: t('nav.admin'),
            description: t('dashboard.manage_users'),
            href: '/admin/users',
            icon: ShieldCheck,
        });
    }

    return (
        <>
            <Head title={t('nav.dashboard')} />

            <div className="flex flex-1 flex-col gap-6 p-4 md:p-6">
                <div className="space-y-1">
                    <Heading
                        title={t('dashboard.greeting', undefined, {
                            name: user?.name ?? '',
                        })}
                        description={t('dashboard.description')}
                    />
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle className="flex items-center gap-2 text-base">
                            <UserRound className="size-4" />
                            {t('dashboard.active_mode')}
                        </CardTitle>
                        <CardDescription>
                            {activeMode
                                ? t(`modes.descriptions.${activeMode}`)
                                : t('dashboard.no_mode')}
                        </CardDescription>
                    </CardHeader>
                    {activeMode && (
                        <CardContent>
                            <Badge>{t(`modes.${activeMode}`)}</Badge>
                        </CardContent>
                    )}
                </Card>

                <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    {shortcuts.map((shortcut) => (
                        <Card key={shortcut.href}>
                            <CardHeader>
                                <shortcut.icon className="mb-2 size-5 text-muted-foreground" />
                                <CardTitle className="text-base">
                                    {shortcut.title}
                                </CardTitle>
                                <CardDescription>
                                    {shortcut.description}
                                </CardDescription>
                            </CardHeader>
                            <CardContent>
                                <Button asChild variant="outline" size="sm">
                                    <Link href={shortcut.href}>
                                        {t('nav.home')}
                                        <ArrowRight className="ms-2 size-4 rtl:rotate-180" />
                                    </Link>
                                </Button>
                            </CardContent>
                        </Card>
                    ))}
                </div>
            </div>
        </>
    );
}

Dashboard.layout = {
    breadcrumbs: [
        {
            title: 'nav.dashboard',
            href: dashboard(),
        },
    ],
};
