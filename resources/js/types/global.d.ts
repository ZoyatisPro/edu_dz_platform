import type {
    Auth,
    Direction,
    Locale,
    SupportedLocale,
    Translations,
    UserMode,
} from '@/types';

declare module 'react' {
    // The type parameter mirrors React's declaration and must keep its name.
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    interface InputHTMLAttributes<T> {
        passwordrules?: string;
    }
}

declare module '@inertiajs/core' {
    export interface InertiaConfig {
        sharedPageProps: {
            name: string;
            displayName: string;
            locale: Locale;
            direction: Direction;
            supportedLocales: SupportedLocale[];
            translations: Translations;
            auth: Auth;
            activeMode: UserMode | null;
            turnstileSiteKey: string | null;
            sidebarOpen: boolean;
            [key: string]: unknown;
        };
    }
}
