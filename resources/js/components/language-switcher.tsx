import { router, usePage } from '@inertiajs/react';
import { Languages } from 'lucide-react';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { useTranslation } from '@/lib/i18n';
import type { Locale } from '@/types';

export function LanguageSwitcher() {
    const { locale, supportedLocales } = usePage().props;
    const { t } = useTranslation();

    const change = (code: Locale): void => {
        if (code === locale) {
            return;
        }

        // The locale endpoint stores the choice in the session and a cookie,
        // then redirects back so Inertia reloads the shared translations.
        router.post(`/locale/${code}`, {}, { preserveScroll: true });
    };

    return (
        <DropdownMenu>
            <DropdownMenuTrigger asChild>
                <Button
                    variant="ghost"
                    size="sm"
                    aria-label={t('language.label')}
                >
                    <Languages />
                    <span className="hidden sm:inline">
                        {t(`language.${locale}`)}
                    </span>
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end" className="w-44">
                <DropdownMenuLabel>{t('language.label')}</DropdownMenuLabel>
                <DropdownMenuSeparator />
                {supportedLocales.map((item) => (
                    <DropdownMenuItem
                        key={item.code}
                        onSelect={() => change(item.code)}
                        className="cursor-pointer"
                        aria-current={item.code === locale ? 'true' : undefined}
                    >
                        {item.label}
                    </DropdownMenuItem>
                ))}
            </DropdownMenuContent>
        </DropdownMenu>
    );
}
