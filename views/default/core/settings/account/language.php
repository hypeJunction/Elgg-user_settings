<?php

$user = elgg_extract('entity', $vars, elgg_get_page_owner_entity());

if (!$user instanceof ElggUser) {
	return;
}

$title = elgg_echo('user:set:language');
$content = elgg_view_input('select', [
	'name' => 'language',
	'value' => $user->language,
	'options_values' => elgg()->translator->getInstalledTranslations(),
	'label' => elgg_echo('user:language:label'),
]);

echo elgg_view_module('info', $title, $content);
