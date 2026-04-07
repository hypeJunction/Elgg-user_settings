<?php

$entity = elgg_extract('entity', $vars, elgg_get_page_owner_entity());

if (!$entity instanceof ElggUser || !$entity->canEdit()) {
	return;
}

elgg_push_context('settings/notifications');

elgg_push_breadcrumb(elgg_echo('settings'), "settings");
elgg_push_breadcrumb($entity->getDisplayName(), "settings/user/$entity->username");
elgg_push_breadcrumb(elgg_echo('user:settings:notifications'), "settings/notifications/$entity->username");

$title = elgg_echo('user:settings:notifications');

$form = elgg_view_form('usersettings/notifications', array(
	'action' => 'action/notificationsettings/save',
		), array(
	'user' => $entity,
		));

$layout = elgg_view_layout('default', array(
	'content' => $form,
	'title' => $title,
	'filter' => elgg_view('filters/settings', array(
		'filter_context' => 'notifications',
		'entity' => $entity,
	))
		));

echo elgg_view_page($title, $layout);
