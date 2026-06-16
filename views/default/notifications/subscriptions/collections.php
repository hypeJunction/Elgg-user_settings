<?php
$title = elgg_echo('notifications:subscriptions:friends:title');
$rows = elgg_view('notifications/subscriptions/rows/collections', $vars);
if (!$rows) {
	return;
}

$desc = elgg_format_element('p', [
	'class' => 'elgg-text-help man',
], elgg_echo('notifications:subscriptions:friends:description'));

$table = elgg_view('notifications/subscriptions/table', [
	'rows' => $rows,
]);

elgg_import_esm('notifications/subscriptions/collections');

echo elgg_view_module('info', $title, $desc . $table, [
	'class' => 'elgg-subscriptions-module',
]);
