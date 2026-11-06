import { describe, expect, it } from 'vitest';
import { translate } from '@/lib/i18n';
import type { Translations } from '@/types';

const translations: Translations = {
    brand: { name: 'منصة الأستاذة هاجر' },
    welcome: {
        title: 'Un seul endroit pour apprendre.',
        features: { groups: 'Groupes de cours' },
    },
};

describe('translate', () => {
    it('resolves nested keys', () => {
        expect(translate(translations, 'welcome.features.groups')).toBe(
            'Groupes de cours',
        );
    });

    it('resolves a key containing non-latin characters', () => {
        expect(translate(translations, 'brand.name')).toBe('منصة الأستاذة هاجر');
    });

    it('returns the fallback when the key is missing', () => {
        expect(translate(translations, 'welcome.missing', 'Fallback')).toBe(
            'Fallback',
        );
    });

    it('returns the key itself when no fallback is provided', () => {
        expect(translate(translations, 'does.not.exist')).toBe(
            'does.not.exist',
        );
    });

    it('does not return a nested object as a string', () => {
        expect(translate(translations, 'welcome.features', 'Fallback')).toBe(
            'Fallback',
        );
    });
});
