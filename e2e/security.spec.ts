import { expect, test } from "@playwright/test";

test('Test user can log in', async ({page}) => {
    await page.goto('/');
    await page.waitForURL('/login');

    await page.getByPlaceholder("Nom d'utilisateur").fill('test');
    await page.getByPlaceholder('Mot de passe').fill('test');
    await page.getByText('Connexion').click();
    await expect(page).toHaveURL('/dashboard');
    await expect(page.locator('h2.navbar-brand')).toHaveText(/Bonjour John Doe/);
});