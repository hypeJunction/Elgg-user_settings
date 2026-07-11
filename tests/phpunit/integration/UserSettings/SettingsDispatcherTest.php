<?php

namespace UserSettings;

use Elgg\Exceptions\Http\PageNotFoundException;
use Elgg\Exceptions\Http\GatekeeperException;
use Elgg\IntegrationTestCase;

/**
 * The `settings` route (/settings/{segments}) renders through the dispatcher
 * resources/settings.php, which maps the first URL segment to
 * resources/settings/<section>.php for the current user.
 *
 * The dispatcher view was missing after the 2.x -> 7.x port, so every
 * /settings/<section> URL the plugin links to (/settings/avatar, /settings/profile,
 * ...) 404'd — the plugin linked to its own dead routes (bd elgg-migrate-ckn0c).
 * These tests exercise the real route -> dispatcher -> section render path.
 */
class SettingsDispatcherTest extends IntegrationTestCase {

    public function up() {}

    public function down() {}

    public function getPluginID(): string {
        return '';
    }

    private function render(string $segments): string {
        return elgg_view_resource('settings', ['segments' => $segments]);
    }

    /**
     * Every section the plugin ships a view for must render for a logged-in user.
     * account/user/notifications call elgg_view_input(), the forms_api BC shim, so
     * this also proves the forms_api dependency is satisfied at runtime.
     */
    public function testEverySectionRendersForLoggedInUser(): void {
        $user = $this->createUser();
        _elgg_services()->session_manager->setLoggedInUser($user);
        elgg_set_page_owner_guid($user->guid);

        foreach (['account', 'avatar', 'notifications', 'plugins', 'profile', 'statistics', 'tools', 'user'] as $section) {
            $html = $this->render($section);
            $this->assertNotEmpty($html, "section '{$section}' rendered nothing");
        }

        _elgg_services()->session_manager->removeLoggedInUser();
    }

    /**
     * A segment with no matching section view is a 404, never a 500.
     */
    public function testUnknownSectionThrowsPageNotFound(): void {
        $user = $this->createUser();
        _elgg_services()->session_manager->setLoggedInUser($user);

        $this->expectException(PageNotFoundException::class);
        try {
            $this->render('this-section-does-not-exist');
        } finally {
            _elgg_services()->session_manager->removeLoggedInUser();
        }
    }

    /**
     * Settings are per-user: an anonymous request must be gatekept, not rendered.
     */
    public function testAnonymousIsGatekept(): void {
        _elgg_services()->session_manager->removeLoggedInUser();
        $this->expectException(GatekeeperException::class);
        $this->render('avatar');
    }

    /**
     * The empty catch-all (/settings) falls back to the account section rather
     * than erroring.
     */
    public function testEmptySegmentsFallsBackToAccount(): void {
        $user = $this->createUser();
        _elgg_services()->session_manager->setLoggedInUser($user);
        elgg_set_page_owner_guid($user->guid);

        $html = $this->render('');
        $this->assertNotEmpty($html);

        _elgg_services()->session_manager->removeLoggedInUser();
    }
}
