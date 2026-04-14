<?php

namespace UserSettings;

use Elgg\HooksRegistrationService\Hook;
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

    /**
     * @return string
     */
    public function getPluginID(): string {
        return '';
    }

    /**
     * @return void
     */
    public function testNotificationsRouteRewritesPersonalForLoggedInUser(): void {
        $user = $this->createUser();
        elgg_get_session()->setLoggedInUser($user);

        $value = [
            'identifier' => 'notifications',
            'segments' => ['personal'],
        ];

        $hook = new Hook(elgg(), 'route', 'notifications', $value, []);
        $result = Router::notificationsRoute($hook);

        $this->assertIsArray($result);
        $this->assertEquals('settings', $result['identifier']);
        $this->assertEquals(['notifications', $user->username], $result['segments']);

        elgg_get_session()->removeLoggedInUser();
    }

    /**
     * @return void
     */
    public function testNotificationsRouteRewritesGroupForNamedUser(): void {
        $user = $this->createUser();

        $value = [
            'identifier' => 'notifications',
            'segments' => ['group', $user->username],
        ];

        $hook = new Hook(elgg(), 'route', 'notifications', $value, []);
        $result = Router::notificationsRoute($hook);

        $this->assertIsArray($result);
        $this->assertEquals('settings', $result['identifier']);
        $this->assertEquals(['notifications', $user->username], $result['segments']);
    }

    /**
     * @return void
     */
    public function testNotificationsRouteDefaultsToPersonal(): void {
        $user = $this->createUser();
        elgg_get_session()->setLoggedInUser($user);

        $value = [
            'identifier' => 'notifications',
            'segments' => [],
        ];

        $hook = new Hook(elgg(), 'route', 'notifications', $value, []);
        $result = Router::notificationsRoute($hook);

        $this->assertIsArray($result);
        $this->assertEquals('settings', $result['identifier']);
        $this->assertEquals('notifications', $result['segments'][0]);

        elgg_get_session()->removeLoggedInUser();
    }

    /**
     * @return void
     */
    public function testNotificationsRouteIgnoresUnrelatedIdentifier(): void {
        $value = [
            'identifier' => 'not-notifications',
            'segments' => ['personal'],
        ];

        $hook = new Hook(elgg(), 'route', 'notifications', $value, []);
        $result = Router::notificationsRoute($hook);

        $this->assertNull($result);
    }

    /**
     * @return void
     */
    public function testNotificationsRouteIgnoresUnknownPage(): void {
        $user = $this->createUser();

        $value = [
            'identifier' => 'notifications',
            'segments' => ['unknown_page', $user->username],
        ];

        $hook = new Hook(elgg(), 'route', 'notifications', $value, []);
        $result = Router::notificationsRoute($hook);

        // handler only returns for 'personal' or 'group'
        $this->assertNull($result);
    }

    /**
     * @return void
     */
    public function testProfileRouteRewritesEdit(): void {
        $user = $this->createUser();

        $value = [
            'identifier' => 'profile',
            'segments' => [$user->username, 'edit'],
        ];

        $hook = new Hook(elgg(), 'route', 'profile', $value, []);
        $result = Router::profileRoute($hook);

        $this->assertIsArray($result);
        $this->assertEquals('settings', $result['identifier']);
        $this->assertEquals(['profile', $user->username], $result['segments']);
    }

    /**
     * @return void
     */
    public function testProfileRouteIgnoresNonEdit(): void {
        $user = $this->createUser();

        $value = [
            'identifier' => 'profile',
            'segments' => [$user->username, 'view'],
        ];

        $hook = new Hook(elgg(), 'route', 'profile', $value, []);
        $result = Router::profileRoute($hook);

        $this->assertNull($result);
    }

    /**
     * @return void
     */
    public function testProfileRouteIgnoresNonArray(): void {
        $hook = new Hook(elgg(), 'route', 'profile', false, []);
        $result = Router::profileRoute($hook);

        $this->assertNull($result);
    }

    /**
     * @return void
     */
    public function testAvatarRouteRewritesEdit(): void {
        $user = $this->createUser();

        $value = [
            'identifier' => 'avatar',
            'segments' => ['edit', $user->username],
        ];

        $hook = new Hook(elgg(), 'route', 'avatar', $value, []);
        $result = Router::avatarRoute($hook);

        $this->assertIsArray($result);
        $this->assertEquals('settings', $result['identifier']);
        $this->assertEquals(['avatar', $user->username], $result['segments']);
    }

    /**
     * @return void
     */
    public function testAvatarRouteIgnoresNonEdit(): void {
        $user = $this->createUser();

        $value = [
            'identifier' => 'avatar',
            'segments' => ['view', $user->username],
        ];

        $hook = new Hook(elgg(), 'route', 'avatar', $value, []);
        $result = Router::avatarRoute($hook);

        $this->assertNull($result);
    }

    /**
     * @return void
     */
    public function testAvatarRouteIgnoresNonArray(): void {
        $hook = new Hook(elgg(), 'route', 'avatar', null, []);
        $result = Router::avatarRoute($hook);

        $this->assertNull($result);
    }
}
