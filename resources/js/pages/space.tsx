import { Form, Head, Link, router, usePage } from '@inertiajs/react';
import {
    Check,
    Lock,
    Settings,
    ShieldCheck,
    Sparkles,
    UserCog,
    UserRound,
} from 'lucide-react';
import { useState } from 'react';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useTranslation } from '@/lib/i18n';
import { edit } from '@/routes/profile';
import type { UserMode } from '@/types';

const MODE_ORDER: UserMode[] = [
    'student',
    'parent',
    'teacher',
    'assistant',
    'admin',
];

const SELF_SERVICE: UserMode[] = ['student', 'parent'];

export default function Space() {
    const { auth, activeMode } = usePage().props;
    const { t } = useTranslation();
    const [showTeacherForm, setShowTeacherForm] = useState(false);

    const user = auth.user;

    if (!user) {
        return null;
    }

    const teacherStatus = user.teacher_request_status;
    const isTeacher = user.roles.includes('teacher');

    const activate = (mode: UserMode): void => {
        router.post(`/modes/${mode}/activate`, {}, { preserveScroll: true });
    };

    const switchMode = (mode: UserMode): void => {
        router.post(`/modes/${mode}/switch`, {}, { preserveScroll: true });
    };

    return (
        <>
            <Head title={t('space.title')} />

            <div className="flex flex-1 flex-col gap-6 p-4 md:p-6">
                <Heading
                    title={t('space.title')}
                    description={t('space.description')}
                />

                <section className="space-y-4">
                    <div>
                        <h2 className="text-lg font-semibold">
                            {t('space.modes_title')}
                        </h2>
                        <p className="text-sm text-muted-foreground">
                            {t('space.modes_description')}
                        </p>
                    </div>

                    <div className="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                        {MODE_ORDER.map((mode) => {
                            const owned = user.roles.includes(mode);
                            const active = activeMode === mode;
                            const selfService = SELF_SERVICE.includes(mode);
                            const isStaffMode =
                                mode === 'assistant' || mode === 'admin';

                            return (
                                <Card
                                    key={mode}
                                    className={
                                        active
                                            ? 'border-primary/60 ring-1 ring-primary/20'
                                            : undefined
                                    }
                                >
                                    <CardHeader>
                                        <div className="mb-1 flex items-center justify-between gap-2">
                                            <CardTitle className="flex items-center gap-2 text-base">
                                                <UserRound className="size-4 text-muted-foreground" />
                                                {t(`modes.${mode}`)}
                                            </CardTitle>
                                            {active && (
                                                <Badge>{t('space.active')}</Badge>
                                            )}
                                            {owned && !active && (
                                                <Badge variant="secondary">
                                                    {t('space.owned')}
                                                </Badge>
                                            )}
                                        </div>
                                        <CardDescription>
                                            {t(`modes.descriptions.${mode}`)}
                                        </CardDescription>
                                    </CardHeader>
                                    <CardContent className="space-y-3">
                                        {active && (
                                            <p className="flex items-center gap-1.5 text-sm font-medium text-primary">
                                                <Check className="size-4" />
                                                {t('modes.current')}
                                            </p>
                                        )}

                                        {owned && !active && (
                                            <Button
                                                size="sm"
                                                variant="outline"
                                                onClick={() => switchMode(mode)}
                                            >
                                                {t('modes.use')}
                                            </Button>
                                        )}

                                        {!owned && selfService && (
                                            <Button
                                                size="sm"
                                                onClick={() => activate(mode)}
                                            >
                                                {t('modes.activate')}
                                            </Button>
                                        )}

                                        {!owned &&
                                            mode === 'teacher' &&
                                            isTeacher === false && (
                                                <>
                                                    {teacherStatus ===
                                                    'pending' ? (
                                                        <p className="text-sm text-muted-foreground">
                                                            {t(
                                                                'space.pending_description',
                                                            )}
                                                        </p>
                                                    ) : (
                                                        <Button
                                                            size="sm"
                                                            variant="outline"
                                                            onClick={() =>
                                                                setShowTeacherForm(
                                                                    (value) =>
                                                                        !value,
                                                                )
                                                            }
                                                        >
                                                            <Sparkles className="me-2 size-4" />
                                                            {t(
                                                                'space.teacher_title',
                                                            )}
                                                        </Button>
                                                    )}
                                                </>
                                            )}

                                        {!owned && isStaffMode && (
                                            <p className="flex items-start gap-1.5 text-sm text-muted-foreground">
                                                <Lock className="mt-0.5 size-4 shrink-0" />
                                                {t('space.staff_note')}
                                            </p>
                                        )}
                                    </CardContent>
                                </Card>
                            );
                        })}
                    </div>
                </section>

                {teacherStatus === 'approved' && !isTeacher && (
                    <p className="rounded-lg border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-700 dark:border-emerald-200/20 dark:bg-emerald-900/20 dark:text-emerald-200">
                        {t('space.approved')}
                    </p>
                )}

                {teacherStatus === 'rejected' && !isTeacher && (
                    <p className="rounded-lg border border-amber-200 bg-amber-50 p-3 text-sm text-amber-700 dark:border-amber-200/20 dark:bg-amber-900/20 dark:text-amber-200">
                        {t('space.rejected')}
                    </p>
                )}

                {showTeacherForm && !isTeacher && teacherStatus !== 'pending' && (
                    <Card>
                        <CardHeader>
                            <CardTitle className="flex items-center gap-2 text-base">
                                <ShieldCheck className="size-4" />
                                {t('space.teacher_title')}
                            </CardTitle>
                            <CardDescription>
                                {t('space.teacher_description')}
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <Form
                                action="/teacher-request"
                                method="post"
                                onSuccess={() => setShowTeacherForm(false)}
                                resetOnSuccess
                                className="space-y-4"
                            >
                                {({ processing, errors }) => (
                                    <>
                                        <div className="grid gap-2">
                                            <Label htmlFor="motivation">
                                                {t('space.motivation')}
                                            </Label>
                                            <textarea
                                                id="motivation"
                                                name="motivation"
                                                required
                                                minLength={20}
                                                maxLength={2000}
                                                rows={5}
                                                placeholder={t(
                                                    'space.motivation_placeholder',
                                                )}
                                                className="w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs outline-none focus-visible:border-ring focus-visible:ring-3 focus-visible:ring-ring/50"
                                            />
                                            <InputError
                                                message={errors.motivation}
                                            />
                                        </div>

                                        <div className="grid gap-2">
                                            <Label htmlFor="subjects">
                                                {t('space.subjects')}
                                            </Label>
                                            <Input
                                                id="subjects"
                                                name="subjects"
                                                maxLength={255}
                                            />
                                            <InputError
                                                message={errors.subjects}
                                            />
                                        </div>

                                        <Button disabled={processing}>
                                            {t('space.submit')}
                                        </Button>
                                    </>
                                )}
                            </Form>
                        </CardContent>
                    </Card>
                )}

                <Card>
                    <CardHeader>
                        <CardTitle className="flex items-center gap-2 text-base">
                            <UserCog className="size-4" />
                            {t('space.account_title')}
                        </CardTitle>
                        <CardDescription>
                            {t('space.account_description')}
                        </CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-3">
                        <dl className="grid gap-1 text-sm">
                            <div className="flex justify-between gap-4">
                                <dt className="text-muted-foreground">
                                    {t('settings.profile.name')}
                                </dt>
                                <dd className="font-medium">{user.name}</dd>
                            </div>
                            <div className="flex justify-between gap-4">
                                <dt className="text-muted-foreground">
                                    {t('settings.profile.email')}
                                </dt>
                                <dd className="font-medium">{user.email}</dd>
                            </div>
                            {user.phone && (
                                <div className="flex justify-between gap-4">
                                    <dt className="text-muted-foreground">
                                        {t('settings.profile.phone')}
                                    </dt>
                                    <dd className="font-medium">
                                        {user.phone}
                                    </dd>
                                </div>
                            )}
                        </dl>
                        <Button asChild variant="outline" size="sm">
                            <Link href={edit()}>
                                <Settings className="me-2 size-4" />
                                {t('space.open_settings')}
                            </Link>
                        </Button>
                    </CardContent>
                </Card>
            </div>
        </>
    );
}
