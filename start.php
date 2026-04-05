<?php

/**
 * User Settings
 *
 * @author Ismayil Khayredinov <info@hypejunction.com>
 * @copyright Copyright (c) 2015, Ismayil Khayredinov
 */
require_once __DIR__ . '/autoloader.php';
elgg_register_event_handler('init', 'system', 'user_settings_init');
/**
 * Initialize the plugin
 * @return void
 */
function user_settings_init()
{
    elgg_unregister_page_handler('settings', '_elgg_user_settings_page_handler');
    elgg_register_route('settings', ['path' => '/settings/{segments}', 'resource' => 'settings', 'requirements' => ['segments' => '.+'], 'defaults' => ['segments' => '']]);
    elgg_unregister_event_handler('pagesetup', 'system', '_elgg_user_settings_menu_setup');
    if (!elgg_get_plugin_setting('show_language', 'user_settings', true)) {
        elgg_unregister_plugin_hook_handler('usersettings:save', 'user', '_elgg_set_user_language');
        elgg_unextend_view('forms/account/settings', 'core/settings/account/language');
    }
    if (elgg_is_active_plugin('notifications')) {
        elgg_register_plugin_hook_handler('route', 'notifications', 'user_settings_notifications_router');
    }
    elgg_register_plugin_hook_handler('route', 'profile', 'user_settings_profile_router');
    elgg_register_plugin_hook_handler('route', 'avatar', 'user_settings_avatar_router');
    elgg_unregister_event_handler('pagesetup', 'system', 'notifications_plugin_pagesetup');
    elgg_extend_view('elgg.css', 'elements/tables/notifications.css');
    elgg_register_action('notificationsettings/save', __DIR__ . '/actions/notificationsettings/save.php');
}
/**
 * User settings page handler
 *
 * @param array $segments URL segments
 * @return bool
 */
function user_settings_page_handler($segments)
{
    elgg_gatekeeper();
    $page = array_shift($segments);
    $username = array_shift($segments);
    if (!$page) {
        $page = 'user';
    }
    if (!$username) {
        $user = elgg_get_logged_in_user_entity();
    } else {
        $user = get_user_by_username($username);
    }
    if (!$user) {
        forward('', '404');
    }
    if (!$user->canEdit()) {
        forward('', '403');
    }
    elgg_set_page_owner_guid($user->guid);
    $resource = elgg_view_resource("settings/{$page}", array('username' => $user->username, 'entity' => $user, 'segments' => $segments));
    if ($resource) {
        echo $resource;
        return true;
    }
    return false;
}
/**
 * Routes notifications pages to the user settings pages
 * 
 * @param string $hook   "route"
 * @param string $type   "notifications"
 * @param array  $return Identifier and segments
 * @param array  $params Hook params
 * @return array
 */
function user_settings_notifications_router($hook, $type, $return, $params)
{
    $identifier = elgg_extract('identifier', $return);
    $segments = (array) elgg_extract('segments', $return, array());
    if ($identifier != 'notifications') {
        return;
    }
    $page = array_shift($segments);
    $username = array_shift($segments);
    if (!$page) {
        $page = 'personal';
    }
    if (!$username) {
        $user = elgg_get_logged_in_user_entity();
    } else {
        $user = get_user_by_username($username);
    }
    if (in_array($page, array('personal', 'group'))) {
        return array('identifier' => 'settings', 'segments' => array('notifications', $user->username));
    }
}
/**
 * Route profile edit page
 * 
 * @param string $hook   "route"
 * @param string $type   "profile"
 * @param array  $return Route
 * @param array  $params Hook params
 * @return array
 */
function user_settings_profile_router($hook, $type, $return, $params)
{
    if (!is_array($return)) {
        return;
    }
    $identifier = elgg_extract('identifier', $return);
    $segments = elgg_extract('segments', $return);
    $username = array_shift($segments);
    $page = array_shift($segments);
    if ($page == 'edit') {
        return array('identifier' => 'settings', 'segments' => array('profile', $username));
    }
}
/**
 * Route profile edit page
 *
 * @param string $hook   "route"
 * @param string $type   "avatar"
 * @param array  $return Route
 * @param array  $params Hook params
 * @return array
 */
function user_settings_avatar_router($hook, $type, $return, $params)
{
    if (!is_array($return)) {
        return;
    }
    $identifier = elgg_extract('identifier', $return);
    $segments = elgg_extract('segments', $return);
    $page = array_shift($segments);
    $username = array_shift($segments);
    if ($page == 'edit') {
        return array('identifier' => 'settings', 'segments' => array('avatar', $username));
    }
}