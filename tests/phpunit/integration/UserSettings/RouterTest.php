<?php

namespace UserSettings;

use Elgg\Hook;
use Elgg\IntegrationTestCase;

/**
 * Tests for route hook handlers in \UserSettings\Router.
 *
 * These handlers rewrite /notifications/, /profile/<name>/edit, and /avatar/edit/
 * routes to the plugin's /settings/ layout.
 */
class RouterTest extends IntegrationTestCase {

    public function up() {
    }

    public function down() {
    }

    public function getPluginID(): string {
        return '';
    }

    public function testNotificationsRouteRewritesPersonalForLoggedInUser(): void {
        $user = $this->createUser();
        elgg_get_session()->setLoggedInUser($user);

        $value = [
            'identifier' => 'notifications',
            'segments' => ['personal'],
        ];

        $hook = new Hook(elgg_services()->hooks, 'route', 'notifications', [], $value);
        $result = Router::notificationsRoute($hook);

        $this->assertIsArray($result);
        $this->assertEquals('settings', $result['identifier']);
        $this->assertEquals(['notifications', $user->username], $result['segments']);

        elgg_get_session()->removeLoggedInUser();
    }

    public function testNotificationsRouteRewritesGroupForNamedUser(): void {
        $user = $this->createUser();

        $value = [
            'identifier' => 'notifications',
            'segments' => ['group', $user->username],
        ];

        $hook = new Hook(elgg_services()->hooks, 'route', 'notifications', [], $value);
        $result = Router::notificationsRoute($hook);

        $this->assertIsArray($result);
        $this->assertEquals('settings', $result['identifier']);
        $this->assertEquals(['notifications', $user->username], $result['segments']);
    }

    public function testNotificationsRouteDefaultsToPersonal(): void {
        $user = $this->createUser();
        elgg_get_session()->setLoggedInUser($user);

        $value = [
            'identifier' => 'notifications',
            'segments' => [],
        ];

        $hook = new Hook(elgg_services()->hooks, 'route', 'notifications', [], $value);
        $result = Router::notificationsRoute($hook);

        $this->assertIsArray($result);
        $this->assertEquals('settings', $result['identifier']);
        $this->assertEquals('notifications', $result['segments'][0]);

        elgg_get_session()->removeLoggedInUser();
    }

    public function testNotificationsRouteIgnoresUnrelatedIdentifier(): void {
        $value = [
            'identifier' => 'not-notifications',
            'segments' => ['personal'],
        ];

        $hook = new Hook(elgg_services()->hooks, 'route', 'notifications', [], $value);
        $result = Router::notificationsRoute($hook);

        $this->assertNull($result);
    }

    public function testNotificationsRouteIgnoresUnknownPage(): void {
        $user = $this->createUser();

        $value = [
            'identifier' => 'notifications',
            'segments' => ['unknown_page', $user->username],
        ];

        $hook = new Hook(elgg_services()->hooks, 'route', 'notifications', [], $value);
        $result = Router::notificationsRoute($hook);

        // handler only returns for 'personal' or 'group'
        $this->assertNull($result);
    }

    public function testProfileRouteRewritesEdit(): void {
        $user = $this->createUser();

        $value = [
            'identifier' => 'profile',
            'segments' => [$user->username, 'edit'],
        ];

        $hook = new Hook(elgg_services()->hooks, 'route', 'profile', [], $value);
        $result = Router::profileRoute($hook);

        $this->assertIsArray($result);
        $this->assertEquals('settings', $result['identifier']);
        $this->assertEquals(['profile', $user->username], $result['segments']);
    }

    public function testProfileRouteIgnoresNonEdit(): void {
        $user = $this->createUser();

        $value = [
            'identifier' => 'profile',
            'segments' => [$user->username, 'view'],
        ];

        $hook = new Hook(elgg_services()->hooks, 'route', 'profile', [], $value);
        $result = Router::profileRoute($hook);

        $this->assertNull($result);
    }

    public function testProfileRouteIgnoresNonArray(): void {
        $hook = new Hook(elgg_services()->hooks, 'route', 'profile', [], false);
        $result = Router::profileRoute($hook);

        $this->assertNull($result);
    }

    public function testAvatarRouteRewritesEdit(): void {
        $user = $this->createUser();

        $value = [
            'identifier' => 'avatar',
            'segments' => ['edit', $user->username],
        ];

        $hook = new Hook(elgg_services()->hooks, 'route', 'avatar', [], $value);
        $result = Router::avatarRoute($hook);

        $this->assertIsArray($result);
        $this->assertEquals('settings', $result['identifier']);
        $this->assertEquals(['avatar', $user->username], $result['segments']);
    }

    public function testAvatarRouteIgnoresNonEdit(): void {
        $user = $this->createUser();

        $value = [
            'identifier' => 'avatar',
            'segments' => ['view', $user->username],
        ];

        $hook = new Hook(elgg_services()->hooks, 'route', 'avatar', [], $value);
        $result = Router::avatarRoute($hook);

        $this->assertNull($result);
    }

    public function testAvatarRouteIgnoresNonArray(): void {
        $hook = new Hook(elgg_services()->hooks, 'route', 'avatar', [], null);
        $result = Router::avatarRoute($hook);

        $this->assertNull($result);
    }
}
