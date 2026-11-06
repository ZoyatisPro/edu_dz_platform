import { render, screen } from '@testing-library/react';
import userEvent from '@testing-library/user-event';
import { beforeEach, describe, expect, it, vi } from 'vitest';
import { LanguageSwitcher } from '@/components/language-switcher';

const { post } = vi.hoisted(() => ({ post: vi.fn() }));

vi.mock('@inertiajs/react', () => ({
    usePage: () => ({
        props: {
            locale: 'fr',
            supportedLocales: [
                { code: 'fr', label: 'Français' },
                { code: 'ar', label: 'العربية' },
            ],
            translations: {
                language: { label: 'Langue', fr: 'Français', ar: 'العربية' },
            },
        },
    }),
    router: { post },
}));

describe('LanguageSwitcher', () => {
    beforeEach(() => {
        post.mockClear();
    });

    it('shows the current language', () => {
        render(<LanguageSwitcher />);

        expect(
            screen.getByRole('button', { name: 'Langue' }),
        ).toHaveTextContent('Français');
    });

    it('posts the chosen locale and leaves the current one untouched', async () => {
        const user = userEvent.setup();

        render(<LanguageSwitcher />);

        await user.click(screen.getByRole('button', { name: 'Langue' }));
        await user.click(screen.getByRole('menuitem', { name: 'العربية' }));

        expect(post).toHaveBeenCalledWith(
            '/locale/ar',
            {},
            { preserveScroll: true },
        );

        post.mockClear();

        await user.click(screen.getByRole('button', { name: 'Langue' }));
        await user.click(screen.getByRole('menuitem', { name: 'Français' }));

        expect(post).not.toHaveBeenCalled();
    });
});
