import { expect, test } from '@playwright/test';

test('the landing page is shown in french by default', async ({ page }) => {
    await page.goto('/');

    await expect(page.locator('html')).toHaveAttribute('lang', 'fr');
    await expect(page.locator('html')).toHaveAttribute('dir', 'ltr');
    await expect(page.getByRole('heading', { level: 1 })).toContainText(
        'Un seul endroit pour apprendre',
    );
});

test('the language switcher renders the arabic interface right-to-left', async ({
    page,
}) => {
    await page.goto('/');

    await page.getByRole('button', { name: 'Langue' }).click();
    await page.getByRole('menuitem', { name: 'العربية' }).click();

    await expect(page.locator('html')).toHaveAttribute('dir', 'rtl');
    await expect(page.locator('html')).toHaveAttribute('lang', 'ar');
    await expect(page.getByRole('heading', { level: 1 })).toContainText(
        'مكان واحد',
    );
});

test('the theme toggle switches to dark mode', async ({ page }) => {
    await page.goto('/');

    await page.getByRole('button', { name: 'Changer de thème' }).click();
    await page.getByRole('menuitem', { name: 'Sombre' }).click();

    await expect(page.locator('html')).toHaveClass(/dark/);
});
