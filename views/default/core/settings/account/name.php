<?php

$user = elgg_extract('entity', $vars, elgg_get_page_owner_entity());

if (!$user instanceof ElggUser) {
	return;
}

$title = elgg_echo('user:name:label');
$content = elgg_view_input('text', [
	'name' => 'name',
	'value' => $user->name,
	'label' => elgg_echo('name'),
]);

echo elgg_view_module('info', $title, $content);
