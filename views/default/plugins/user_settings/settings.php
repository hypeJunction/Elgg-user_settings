<?php

$entity = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'select',
	'name' => 'params[show_statistics]',
	'value' => isset($entity->show_statistics) ? $entity->show_statistics : true,
	'#label' => elgg_echo('user:settings:show_statistics'),
	'options_values' => [
		0 => elgg_echo('option:no'),
		1 => elgg_echo('option:yes'),
	],
]);

echo elgg_view_field([
	'#type' => 'select',
	'name' => 'params[show_language]',
	'value' => isset($entity->show_language) ? $entity->show_language : true,
	'#label' => elgg_echo('user:settings:show_language'),
	'options_values' => [
		0 => elgg_echo('option:no'),
		1 => elgg_echo('option:yes'),
	],
]);
