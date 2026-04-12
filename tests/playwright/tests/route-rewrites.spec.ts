import { test, expect } from '@playwright/test';
import { loginAs } from '../helpers/elgg';

/**
 * The plugin installs route-hook handlers that rewrite legacy URLs
 * to the unified /settings/* layout:
 *   /notifications/personal/<user>  -> /settings/notifications/<user>
 *   /profile/<user>/edit            -> /settings/profile/<user>
 *   /avatar/edit/<user>             -> /settings/avatar/<user>
 */
test.describe('Legacy route rewrites', () => {
  const username = process.env.ELGG_TEST_USER || 'testuser';

  test.beforeEach(async ({ page }) => {
    await loginAs(page, username);
  });

  test('notifications/personal rewrites to settings/notifications', async ({ page }) => {
    await page.goto(`/notifications/personal/${username}`);
    await expect(page).toHaveURL(new RegExp(`/settings/notifications/${username}$`));
  });

  test('notifications/group rewrites to settings/notifications', async ({ page }) => {
    await page.goto(`/notifications/group/${username}`);
    await expect(page).toHaveURL(new RegExp(`/settings/notifications/${username}$`));
  });

  test('profile/<user>/edit rewrites to settings/profile', async ({ page }) => {
    await page.goto(`/profile/${username}/edit`);
    await expect(page).toHaveURL(new RegExp(`/settings/profile/${username}$`));
  });

  test('avatar/edit/<user> rewrites to settings/avatar', async ({ page }) => {
    await page.goto(`/avatar/edit/${username}`);
    await expect(page).toHaveURL(new RegExp(`/settings/avatar/${username}$`));
  });
});
