import { test, expect } from '@playwright/test';

test('Test user can connect', async({ page }) => {
   await page.goto('http://nginx');
   await page.waitForURL('http://nginx/login');
   await page.getByPlaceholder("Nom d'utilisateur").fill('test');
   await page.getByPlaceholder('Mot de passe').fill('test');
   await page.getByText('Connexion').click();

   await expect(page).toHaveURL('/dasvhoard');
});