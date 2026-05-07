<?php

if (!elgg_get_plugin_setting('show_statistics', 'user_settings', true)) {
	return;
}

$entity = elgg_extract('entity', $vars, elgg_get_page_owner_entity());

if (!$entity instanceof ElggUser || !$entity->canEdit()) {
	return;
}

elgg_push_context('settings/statistics');

elgg_push_breadcrumb(elgg_echo('settings'), 'settings');
elgg_push_breadcrumb($entity->getDisplayName(), "settings/user/$entity->username");
elgg_push_breadcrumb(elgg_echo('user:settings:statistics'), "settings/statistics/$entity->username");

$title = elgg_echo('user:settings:statistics');

$content = elgg_view('core/settings/statistics');

$layout = elgg_view_layout('default', [
	'content' => $content,
	'title' => $title,
	'filter' => elgg_view('filters/settings', [
		'filter_context' => 'statistics',
		'entity' => $entity,
	])
]);

echo elgg_view_page($title, $layout);
