<?php

$user = elgg_extract('entity', $vars, elgg_get_page_owner_entity());

if (!$user instanceof ElggUser) {
	return;
}

$title = elgg_echo('email:settings');
$content = elgg_view_input('email', array(
	'name' => 'email',
	'value' => $user->email,
	'label' => elgg_echo('email:address:label'),
));

echo elgg_view_module('info', $title, $content);