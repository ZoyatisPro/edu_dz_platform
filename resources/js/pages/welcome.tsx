import { Head, Link, usePage } from '@inertiajs/react';
import {
    ArrowRight,
    BookOpenCheck,
    CheckCircle2,
    ClipboardCheck,
    GraduationCap,
    Languages,
    Sparkles,
    UserRound,
    Users,
    Video,
} from 'lucide-react';
import AppLogoIcon from '@/components/app-logo-icon';
import { LanguageSwitcher } from '@/components/language-switcher';
import { ThemeToggle } from '@/components/theme-toggle';
import { Button } from '@/components/ui/button';
import { useTranslation } from '@/lib/i18n';
import { dashboard, login, register } from '@/routes';

const featureKeys = [
    'groups',
    'tracking',
    'sessions',
    'quiz',
    'parents',
    'settings',
] as const;

const featureIcons = {
    groups: Users,
    tracking: CheckCircle2,
    sessions: Video,
    quiz: ClipboardCheck,
    parents: UserRound,
    settings: Languages,
};

const levelKeys = [
    'preparatory',
    'primary',
    'middle',
    'high',
    'training',
] as const;

export default function Welcome() {
    const { auth } = usePage().props;
    const { t } = useTranslation();

    return (
        <>
            <Head title={t('brand.name')} />

            <div className="min-h-screen bg-background">
                <header className="sticky top-0 z-50 border-b border-border/60 bg-background/80 backdrop-blur">
                    <div className="mx-auto flex h-16 w-full max-w-6xl items-center gap-2 px-4 sm:gap-4 sm:px-6">
                        <Link
                            href="/"
                            className="flex min-w-0 items-center gap-2"
                            aria-label={t('brand.name')}
                        >
                            <span className="flex size-9 shrink-0 items-center justify-center rounded-lg bg-primary text-primary-foreground">
                                <AppLogoIcon className="size-5 fill-current" />
                            </span>
                            <span className="truncate text-sm font-semibold sm:text-base">
                                {t('brand.name')}
                            </span>
                        </Link>

                        <nav
                            className="ms-4 hidden items-center gap-5 text-sm text-muted-foreground md:flex"
                            aria-label={t('nav.home')}
                        >
                            <a
                                href="#features"
                                className="transition-colors hover:text-foreground"
                            >
                                {t('nav.courses')}
                            </a>
                            <a
                                href="#levels"
                                className="transition-colors hover:text-foreground"
                            >
                                {t('nav.levels')}
                            </a>
                            <a
                                href="#about"
                                className="transition-colors hover:text-foreground"
                            >
                                {t('nav.about')}
                            </a>
                        </nav>

                        <div className="ms-auto flex items-center gap-1">
                            <LanguageSwitcher />
                            <ThemeToggle />

                            {auth.user ? (
                                <Button asChild size="sm" className="ms-1">
                                    <Link href={dashboard()}>
                                        {t('nav.dashboard')}
                                    </Link>
                                </Button>
                            ) : (
                                <>
                                    <Button
                                        asChild
                                        variant="ghost"
                                        size="sm"
                                        className="hidden sm:inline-flex"
                                    >
                                        <Link href={login()}>
                                            {t('nav.login')}
                                        </Link>
                                    </Button>
                                    <Button asChild size="sm" className="ms-1">
                                        <Link href={register()}>
                                            {t('nav.register')}
                                        </Link>
                                    </Button>
                                </>
                            )}
                        </div>
                    </div>
                </header>

                <main>
                    <section className="relative overflow-hidden">
                        <div
                            aria-hidden="true"
                            className="pointer-events-none absolute inset-0 -z-10"
                        >
                            <div className="absolute end-[-10%] -top-32 size-[28rem] rounded-full bg-primary/10 blur-3xl" />
                            <div className="absolute start-[-10%] -bottom-40 size-[26rem] rounded-full bg-accent/20 blur-3xl" />
                        </div>

                        <div className="mx-auto w-full max-w-6xl px-4 pt-16 pb-20 sm:px-6 sm:pt-24 sm:pb-28">
                            <div className="mx-auto max-w-3xl text-center">
                                <span className="inline-flex items-center gap-2 rounded-full border border-border bg-card px-3 py-1 text-xs font-medium text-muted-foreground">
                                    <Sparkles className="size-3.5 text-primary" />
                                    {t('welcome.eyebrow')}
                                </span>

                                <h1 className="mt-6 text-4xl font-bold tracking-tight text-balance sm:text-5xl lg:text-6xl">
                                    {t('welcome.title')}
                                </h1>

                                <p className="mx-auto mt-6 max-w-2xl text-base leading-relaxed text-pretty text-muted-foreground sm:text-lg">
                                    {t('welcome.description')}
                                </p>

                                <div className="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
                                    <Button
                                        asChild
                                        size="lg"
                                        className="w-full sm:w-auto"
                                    >
                                        <Link href={register()}>
                                            {t('welcome.cta_primary')}
                                            <ArrowRight className="rtl:rotate-180" />
                                        </Link>
                                    </Button>
                                    <Button
                                        asChild
                                        variant="outline"
                                        size="lg"
                                        className="w-full sm:w-auto"
                                    >
                                        <Link href={login()}>
                                            {t('welcome.cta_secondary')}
                                        </Link>
                                    </Button>
                                </div>

                                <p className="mt-4 text-xs text-muted-foreground">
                                    {t('welcome.note')}
                                </p>
                            </div>
                        </div>
                    </section>

                    <section
                        id="features"
                        className="border-t border-border py-16 sm:py-24"
                    >
                        <div className="mx-auto w-full max-w-6xl px-4 sm:px-6">
                            <div className="mx-auto max-w-2xl text-center">
                                <h2 className="text-2xl font-semibold tracking-tight sm:text-3xl">
                                    {t('welcome.features.title')}
                                </h2>
                                <p className="mt-3 text-sm text-muted-foreground sm:text-base">
                                    {t('welcome.features.subtitle')}
                                </p>
                            </div>

                            <div className="mt-12 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                {featureKeys.map((key) => {
                                    const Icon = featureIcons[key];

                                    return (
                                        <article
                                            key={key}
                                            className="rounded-xl border border-border bg-card p-5 transition-colors hover:border-primary/40"
                                        >
                                            <span className="inline-flex size-10 items-center justify-center rounded-lg bg-primary/10 text-primary">
                                                <Icon className="size-5" />
                                            </span>
                                            <h3 className="mt-4 font-semibold">
                                                {t(
                                                    `welcome.features.${key}.title`,
                                                )}
                                            </h3>
                                            <p className="mt-2 text-sm leading-relaxed text-muted-foreground">
                                                {t(
                                                    `welcome.features.${key}.description`,
                                                )}
                                            </p>
                                        </article>
                                    );
                                })}
                            </div>
                        </div>
                    </section>

                    <section
                        id="levels"
                        className="border-t border-border bg-muted/40 py-16 sm:py-24"
                    >
                        <div className="mx-auto w-full max-w-6xl px-4 sm:px-6">
                            <div className="mx-auto max-w-2xl text-center">
                                <h2 className="text-2xl font-semibold tracking-tight sm:text-3xl">
                                    {t('welcome.levels.title')}
                                </h2>
                                <p className="mt-3 text-sm text-muted-foreground sm:text-base">
                                    {t('welcome.levels.subtitle')}
                                </p>
                            </div>

                            <ul className="mt-10 flex flex-wrap items-center justify-center gap-3">
                                {levelKeys.map((key) => (
                                    <li
                                        key={key}
                                        className="flex items-center gap-2 rounded-full border border-border bg-background px-4 py-2 text-sm font-medium shadow-xs"
                                    >
                                        <GraduationCap className="size-4 text-primary" />
                                        {t(`welcome.levels.${key}`)}
                                    </li>
                                ))}
                            </ul>
                        </div>
                    </section>

                    <section
                        id="about"
                        className="border-t border-border py-16 sm:py-24"
                    >
                        <div className="mx-auto w-full max-w-3xl px-4 text-center sm:px-6">
                            <span className="inline-flex items-center gap-2 rounded-full bg-accent/20 px-3 py-1 text-xs font-medium text-accent-foreground">
                                <BookOpenCheck className="size-3.5" />
                                {t('nav.about')}
                            </span>
                            <p className="mt-5 text-lg leading-relaxed text-pretty">
                                {t('brand.tagline')}
                            </p>
                            <div className="mt-8">
                                <Button asChild size="lg">
                                    <Link href={register()}>
                                        {t('welcome.cta_primary')}
                                        <ArrowRight className="rtl:rotate-180" />
                                    </Link>
                                </Button>
                            </div>
                        </div>
                    </section>
                </main>

                <footer className="border-t border-border py-8">
                    <div className="mx-auto flex w-full max-w-6xl flex-col items-center justify-between gap-3 px-4 text-xs text-muted-foreground sm:flex-row sm:px-6">
                        <p>
                            © {new Date().getFullYear()} {t('brand.name')}.{' '}
                            {t('footer.rights')}
                        </p>
                        <p>{t('footer.legal')}</p>
                    </div>
                </footer>
            </div>
        </>
    );
}
