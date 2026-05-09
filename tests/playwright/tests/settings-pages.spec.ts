import { test, expect } from '@playwright/test';
import { loginAs } from '../helpers/elgg';

/**
 * Verifies the unified /settings/* layout installed by Elgg-user_settings:
 * each resource view renders without system-message errors and contains
 * expected form fields.
 */
test.describe('User settings pages', () => {
  const username = process.env.ELGG_TEST_USER || 'testuser';

  test.beforeEach(async ({ page }) => {
    await loginAs(page, username);
  });

  test('/settings/user renders account page', async ({ page }) => {
    await page.goto(`/settings/user/${username}`);
    await expect(page.locator('.elgg-system-messages .elgg-message-error')).toHaveCount(0);
    await expect(page.locator('form')).toBeVisible();
  });

  test('/settings/account renders account form', async ({ page }) => {
    await page.goto(`/settings/account/${username}`);
    await expect(page.locator('.elgg-system-messages .elgg-message-error')).toHaveCount(0);
    await expect(page.locator('input[name="name"], input[name="email"]')).toHaveCount.greaterThan(0);
  });

  test('/settings/notifications renders notification form', async ({ page }) => {
    await page.goto(`/settings/notifications/${username}`);
    await expect(page.locator('.elgg-system-messages .elgg-message-error')).toHaveCount(0);
    // The plugin exposes the notification subscriptions table
    await expect(page.locator('form')).toBeVisible();
  });

  test('/settings/avatar renders avatar page', async ({ page }) => {
    await page.goto(`/settings/avatar/${username}`);
    await expect(page.locator('.elgg-system-messages .elgg-message-error')).toHaveCount(0);
  });

  test('/settings/profile renders profile edit page', async ({ page }) => {
    await page.goto(`/settings/profile/${username}`);
    await expect(page.locator('.elgg-system-messages .elgg-message-error')).toHaveCount(0);
    await expect(page.locator('form')).toBeVisible();
  });

  test('/settings/statistics renders statistics page', async ({ page }) => {
    await page.goto(`/settings/statistics/${username}`);
    await expect(page.locator('.elgg-system-messages .elgg-message-error')).toHaveCount(0);
  });

  test('/settings/plugins renders plugin-user-settings page', async ({ page }) => {
    await page.goto(`/settings/plugins/${username}`);
    await expect(page.locator('.elgg-system-messages .elgg-message-error')).toHaveCount(0);
  });
});
