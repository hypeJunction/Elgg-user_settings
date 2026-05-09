<?php

$entity = elgg_extract('entity', $vars, elgg_get_page_owner_entity());

if (!$entity instanceof ElggUser || !$entity->canEdit()) {
	elgg_register_error_message(elgg_echo('profile:noaccess'));
	return;
}

elgg_push_context('settings/user');
elgg_push_context('profile_edit');

elgg_push_breadcrumb(elgg_echo('settings'), 'settings');
elgg_push_breadcrumb($entity->getDisplayName(), "settings/user/$entity->username");

$title = elgg_echo('user:settings:profile');

$content = elgg_view_form('profile/edit', [
	'validate' => true,
], [
	'entity' => $entity
]);

$params = [
	'content' => $content,
	'title' => $title,
	'filter' => elgg_view('filters/settings', [
		'filter_context' => 'profile',
		'entity' => $entity,
	]),
];

$body = elgg_view_layout('default', $params);

echo elgg_view_page($title, $body);
