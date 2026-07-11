<?php
/**
 * Dispatcher for the `settings` route (`/settings/{segments}`).
 *
 * The route was ported from a 2.x page handler and kept the catch-all path, but
 * named a resource view — resources/settings.php — that was never created, so every
 * /settings/<section> URL the plugin links to (/settings/avatar, /settings/profile,
 * /settings/user, ...) raised ResourceNotFoundException and 404'd. The section
 * bodies live at resources/settings/<section>.php and were unreachable
 * (bd elgg-migrate-ckn0c).
 *
 * Core 7.x owns the username-qualified settings routes — /settings/user/{username},
 * /settings/notifications/{username}, /settings/statistics/{username},
 * /settings/plugins/{username}/{plugin_id} — and those are more specific, so they
 * still win. This catch-all only serves the plugin's own /settings/<section> links
 * (avatar, profile, and the rest) for the CURRENT user.
 */

use Elgg\Exceptions\Http\PageNotFoundException;

elgg_gatekeeper(); // settings are per-user; never anonymous

$segments = (string) elgg_extract('segments', $vars, '');
$parts = array_values(array_filter(explode('/', trim($segments, '/')), 'strlen'));
$section = $parts[0] ?? 'account';

// Only sections that have a resource view. Anything else is a 404, not a 500.
$known = ['account', 'avatar', 'notifications', 'plugins', 'profile', 'statistics', 'tools', 'user'];
if (!in_array($section, $known, true)) {
	throw new PageNotFoundException();
}

// These are "my settings" pages: the section bodies default $entity to the page
// owner, which defaults to the logged-in user. Set it explicitly so a section body
// that reads the page owner resolves to the current user rather than nothing.
$user = elgg_get_logged_in_user_entity();
if (!elgg_get_page_owner_guid()) {
	elgg_set_page_owner_guid($user->guid);
}

echo elgg_view_resource("settings/{$section}", $vars + ['entity' => $user]);
