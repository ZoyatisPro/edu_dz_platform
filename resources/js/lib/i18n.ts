import { usePage } from '@inertiajs/react';
import type { Direction, Translations, TranslationValue } from '@/types';

export function translate(
    translations: Translations,
    key: string,
    fallback?: string,
    replacements?: Record<string, string | number>,
): string {
    const value = key
        .split('.')
        .reduce<TranslationValue | undefined>((carry, segment) => {
            if (carry === undefined || typeof carry === 'string') {
                return undefined;
            }

            return carry[segment];
        }, translations);

    if (typeof value !== 'string') {
        return fallback ?? key;
    }

    if (!replacements) {
        return value;
    }

    return Object.entries(replacements).reduce(
        (result, [name, replacement]) =>
            result.replaceAll(`:${name}`, String(replacement)),
        value,
    );
}

export function useTranslation() {
    const { locale, direction, translations } = usePage().props;

    const t = (
        key: string,
        fallback?: string,
        replacements?: Record<string, string | number>,
    ): string => translate(translations, key, fallback, replacements);

    return { t, locale, direction: direction as Direction };
}
