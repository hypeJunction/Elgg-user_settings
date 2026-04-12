import { test, expect } from '@playwright/test';
import { loginAs } from '../helpers/elgg';

/**
 * Admin plugin settings page must render and expose the
 * show_statistics / show_language selects defined by
 * views/default/plugins/user_settings/settings.php.
 */
test.describe('Admin plugin settings', () => {
  test('plugin_settings/user_settings page renders', async ({ page }) => {
    const admin = process.env.ELGG_ADMIN_USER || 'admin';
    await loginAs(page, admin);

    const response = await page.goto('/admin/plugin_settings/user_settings');
    expect(response?.status() ?? 0).toBeLessThan(500);

    await expect(page.locator('.elgg-system-messages .elgg-message-error')).toHaveCount(0);
    await expect(page.locator('select[name="params[show_statistics]"]')).toBeVisible();
    await expect(page.locator('select[name="params[show_language]"]')).toBeVisible();
  });
});
