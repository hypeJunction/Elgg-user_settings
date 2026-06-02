<?php

$user = elgg_extract('entity', $vars, elgg_get_page_owner_entity());

if (!$user instanceof ElggUser) {
	return;
}

echo elgg_view('forms/account/settings', $vars);

// we need to include the user GUID so that admins can edit the settings of other users
echo elgg_view('input/hidden', [
	'name' => 'guid',
	'value' => $user->guid
]);

echo elgg_view('input/submit', [
	'value' => elgg_echo('save'),
	'field_class' => 'elgg-foot'
]);
