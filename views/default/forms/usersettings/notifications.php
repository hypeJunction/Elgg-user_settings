<?php

$user = elgg_extract('user', $vars);
if (!$user instanceof ElggUser) {
	return;
}

echo elgg_view('notifications/subscriptions/personal', $vars);
echo elgg_view('notifications/subscriptions/collections', $vars);

if (elgg_is_active_plugin('groups')) {
	echo elgg_view('notifications/subscriptions/groups', $vars);
}

echo elgg_view('input/hidden', array(
	'name' => 'guid',
	'value' => $user->guid
));
echo elgg_view('input/submit', array(
	'value' => elgg_echo('save'),
	'field_class' => 'elgg-foot',
));
