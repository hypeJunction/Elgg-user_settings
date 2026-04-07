<?php

$entity = elgg_extract('entity', $vars, elgg_get_page_owner_entity());

if (!$entity instanceof ElggUser || !$entity->canEdit()) {
	elgg_register_error_message(elgg_echo("profile:noaccess"));
	return;
}

elgg_push_context('settings/user');
elgg_push_context('profile_edit');

elgg_push_breadcrumb(elgg_echo('settings'), "settings");
elgg_push_breadcrumb($entity->getDisplayName(), "settings/user/$entity->username");

$title = elgg_echo('user:settings:avatar');

$content = elgg_view('core/avatar/upload', array('entity' => $entity));

// only offer the crop view if an avatar has been uploaded
if (isset($entity->icontime)) {
	$content .= elgg_view('core/avatar/crop', array('entity' => $entity));
}

$params = array(
	'content' => $content,
	'title' => $title,
	'filter' => elgg_view('filters/settings', array(
		'filter_context' => 'avatar',
		'entity' => $entity,
	)),
);

$body = elgg_view_layout('default', $params);

echo elgg_view_page($title, $body);
