import { router, usePage } from '@inertiajs/react';
import { Check, ChevronDown, UserCog } from 'lucide-react';
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
import type { UserMode } from '@/types';

const MODE_ORDER: UserMode[] = [
    'student',
    'parent',
    'teacher',
    'assistant',
    'admin',
];

export function ModeSwitcher() {
    const { auth, activeMode } = usePage().props;
    const { t } = useTranslation();

    const user = auth.user;

    if (!user || user.roles.length === 0) {
        return null;
    }

    const owned = MODE_ORDER.filter((mode) => user.roles.includes(mode));

    if (owned.length === 1) {
        return (
            <span className="hidden items-center gap-1.5 rounded-full border border-border px-2.5 py-1 text-xs font-medium text-muted-foreground sm:inline-flex">
                <UserCog className="size-3.5" />
                {t(`modes.${owned[0]}`)}
            </span>
        );
    }

    const switchMode = (mode: UserMode): void => {
        if (mode === activeMode) {
            return;
        }

        router.post(`/modes/${mode}/switch`, {}, { preserveScroll: true });
    };

    return (
        <DropdownMenu>
            <DropdownMenuTrigger asChild>
                <Button variant="outline" size="sm" className="gap-2">
                    <UserCog className="size-4" />
                    <span className="hidden sm:inline">
                        {activeMode
                            ? t(`modes.${activeMode}`)
                            : t('modes.none')}
                    </span>
                    <ChevronDown className="size-3.5 opacity-60" />
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end" className="w-52">
                <DropdownMenuLabel>{t('modes.switch')}</DropdownMenuLabel>
                <DropdownMenuSeparator />
                {owned.map((mode) => (
                    <DropdownMenuItem
                        key={mode}
                        onSelect={() => switchMode(mode)}
                        className="cursor-pointer"
                        aria-current={
                            mode === activeMode ? 'true' : undefined
                        }
                    >
                        <span className="flex-1">{t(`modes.${mode}`)}</span>
                        {mode === activeMode && <Check className="size-4" />}
                    </DropdownMenuItem>
                ))}
            </DropdownMenuContent>
        </DropdownMenu>
    );
}
