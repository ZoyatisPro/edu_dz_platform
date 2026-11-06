export type Locale = 'fr' | 'ar';

export type Direction = 'ltr' | 'rtl';

export type TranslationValue = string | { [key: string]: TranslationValue };

export type Translations = Record<string, TranslationValue>;

export type SupportedLocale = {
    code: Locale;
    label: string;
};
