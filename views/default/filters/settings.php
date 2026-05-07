<?php

$entity = elgg_extract('entity', $vars, elgg_get_page_owner_entity());
$filter_context = elgg_extract('filter_context', $vars, 'user');

$tabs = [
	'profile' => "profile/$entity->username/edit",
	'avatar' => "avatar/edit/$entity->username",
	'account' => "settings/account/$entity->username",
];

if (elgg_is_active_plugin('notifications')) {
	$tabs['notifications'] = "settings/notifications/$entity->username";
}

$active_plugins = elgg_get_plugins();
foreach ($active_plugins as $plugin) {
	$plugin_id = $plugin->getID();
	if (elgg_view_exists("usersettings/$plugin_id/edit") || elgg_view_exists("plugins/$plugin_id/usersettings")) {
		$tabs['tools'] = "settings/tools/$entity->username";
		break;
	}
}

if (elgg_get_plugin_setting('show_statistics', 'user_settings')) {
	$tabs['statistics'] = "settings/statistics/$entity->username";
}

foreach ($tabs as $tab => $url) {
	elgg_register_menu_item('filter', [
		'name' => "user:settings:$tab",
		'text' => elgg_echo("user:settings:$tab"),
		'href' => elgg_normalize_url($url),
		'selected' => $tab == $filter_context,
	]);
}

echo elgg_view_menu('filter', [
	'sort_by' => 'priority',
	'entity' => $entity,
	'filter_context' => $filter_context,
]);
