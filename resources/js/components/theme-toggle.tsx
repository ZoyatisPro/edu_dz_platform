import { Check, Monitor, Moon, Sun } from 'lucide-react';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import type { Appearance } from '@/hooks/use-appearance';
import { useAppearance } from '@/hooks/use-appearance';
import { useTranslation } from '@/lib/i18n';

export function ThemeToggle() {
    const { appearance, resolvedAppearance, updateAppearance } =
        useAppearance();
    const { t } = useTranslation();

    const options: { value: Appearance; label: string; icon: typeof Sun }[] = [
        { value: 'light', label: t('theme.light'), icon: Sun },
        { value: 'dark', label: t('theme.dark'), icon: Moon },
        { value: 'system', label: t('theme.system'), icon: Monitor },
    ];

    const CurrentIcon = resolvedAppearance === 'dark' ? Moon : Sun;

    return (
        <DropdownMenu>
            <DropdownMenuTrigger asChild>
                <Button
                    variant="ghost"
                    size="icon"
                    aria-label={t('theme.toggle')}
                >
                    <CurrentIcon />
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end" className="w-44">
                <DropdownMenuLabel>{t('theme.label')}</DropdownMenuLabel>
                <DropdownMenuSeparator />
                {options.map(({ value, label, icon: Icon }) => (
                    <DropdownMenuItem
                        key={value}
                        onSelect={() => updateAppearance(value)}
                        className="cursor-pointer"
                        aria-current={appearance === value ? 'true' : undefined}
                    >
                        <Icon />
                        <span className="flex-1">{label}</span>
                        {appearance === value && <Check className="size-4" />}
                    </DropdownMenuItem>
                ))}
            </DropdownMenuContent>
        </DropdownMenu>
    );
}
