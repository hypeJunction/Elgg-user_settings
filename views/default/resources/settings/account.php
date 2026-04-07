<?php

$entity = elgg_extract('entity', $vars, elgg_get_page_owner_entity());

if (!$entity instanceof ElggUser || !$entity->canEdit()) {
	return;
}

elgg_push_context('settings/user');

elgg_push_breadcrumb(elgg_echo('settings'), "settings");
elgg_push_breadcrumb($entity->getDisplayName(), "settings/user/$entity->username");

$title = elgg_echo('user:settings:account');

$content = elgg_view('core/settings/account', $vars);

$params = array(
	'content' => $content,
	'title' => $title,
	'filter' => elgg_view('filters/settings', array(
		'filter_context' => 'account',
		'entity' => $entity,
	)),
);

$body = elgg_view_layout('default', $params);

echo elgg_view_page($title, $body);
