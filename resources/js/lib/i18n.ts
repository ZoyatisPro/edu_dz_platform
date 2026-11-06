import { usePage } from '@inertiajs/react';
import type { Direction, Translations, TranslationValue } from '@/types';

export function translate(
    translations: Translations,
    key: string,
    fallback?: string,
): string {
    const value = key
        .split('.')
        .reduce<TranslationValue | undefined>((carry, segment) => {
            if (carry === undefined || typeof carry === 'string') {
                return undefined;
            }

            return carry[segment];
        }, translations);

    return typeof value === 'string' ? value : (fallback ?? key);
}

export function useTranslation() {
    const { locale, direction, translations } = usePage().props;

    const t = (key: string, fallback?: string): string =>
        translate(translations, key, fallback);

    return { t, locale, direction: direction as Direction };
}
