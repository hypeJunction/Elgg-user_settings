<?php
// Dispatcher for /settings/{segments} catch-all route registered by user_settings plugin.
// Segments format: "<page>/<username>" e.g. "notifications/admin"
$segments_str = elgg_extract('segments', $vars, '');
$segments = $segments_str ? explode('/', $segments_str) : [];
$page = array_shift($segments) ?: 'account';
$username = array_shift($segments);

if ($username) {
    $entity = get_user_by_username($username);
    if ($entity) {
        elgg_set_page_owner_guid($entity->guid);
    }
} else {
    $entity = elgg_get_logged_in_user_entity();
}

$vars['entity'] = $entity;

switch ($page) {
    case 'notifications':
        echo elgg_view_resource('settings/notifications', $vars);
        break;
    case 'avatar':
        echo elgg_view_resource('settings/avatar', $vars);
        break;
    case 'profile':
        echo elgg_view_resource('settings/profile', $vars);
        break;
    case 'plugins':
        echo elgg_view_resource('settings/plugins', $vars);
        break;
    case 'statistics':
        echo elgg_view_resource('settings/statistics', $vars);
        break;
    case 'tools':
        echo elgg_view_resource('settings/tools', $vars);
        break;
    case 'account':
    default:
        echo elgg_view_resource('settings/account', $vars);
        break;
}
