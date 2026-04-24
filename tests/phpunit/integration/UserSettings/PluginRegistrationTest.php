<?php

namespace UserSettings;

use Elgg\IntegrationTestCase;

/**
 * Smoke-tests that plugin-level registrations from elgg-plugin.php are
 * in place: the settings route, the notificationsettings/save action,
 * and the core views ship-shape.
 */
class PluginRegistrationTest extends IntegrationTestCase {

    public function up() {
    }

    public function down() {
    }

    /**
     * @return string
     */
    public function getPluginID(): string {
        return '';
    }

    /**
     * @return void
     */
    public function testNotificationSettingsSaveActionIsRegistered(): void {
        $actions = _elgg_services()->actions->getAllActions();
        $this->assertArrayHasKey('notificationsettings/save', $actions);
    }

    /**
     * @return void
     */
    public function testSettingsRouteIsRegistered(): void {
        $routes = _elgg_services()->routes->all();
        $this->assertArrayHasKey('settings', $routes);
    }

    /**
     * @return void
     */
    public function testKeySettingsViewsExist(): void {
        $views = [
            'resources/settings/user',
            'resources/settings/account',
            'resources/settings/notifications',
            'resources/settings/avatar',
            'resources/settings/profile',
            'resources/settings/plugins',
            'resources/settings/statistics',
            'resources/settings/tools',
            'forms/usersettings/notifications',
            'forms/usersettings/save',
            'notifications/subscriptions/table',
            'notifications/subscriptions/personal',
            'notifications/subscriptions/groups',
            'notifications/subscriptions/collections',
            'plugins/user_settings/settings',
        ];

        foreach ($views as $view) {
            $this->assertTrue(elgg_view_exists($view), "View $view should exist");
        }
    }

    /**
     * @return void
     */
    public function testNotificationSubscriptionsTableRenders(): void {
        $user = $this->createUser();
        _elgg_services()->session_manager->setLoggedInUser($user);

        $output = elgg_view('notifications/subscriptions/personal', [
            'entity' => $user,
        ]);

        $this->assertIsString($output);

        _elgg_services()->session_manager->removeLoggedInUser();
    }

    /**
     * @return void
     */
    public function testPluginSettingsViewRendersWithDefaults(): void {
        $plugin = elgg_get_plugin_from_id('user_settings');
        if (!$plugin) {
            $this->markTestSkipped('user_settings plugin not installed');
        }

        $output = elgg_view('plugins/user_settings/settings', [
            'entity' => $plugin,
        ]);

        $this->assertIsString($output);
        $this->assertNotEmpty($output);
        // Plugin settings view renders two selects: show_statistics, show_language
        $this->assertStringContainsString('params[show_statistics]', $output);
        $this->assertStringContainsString('params[show_language]', $output);
    }
}
