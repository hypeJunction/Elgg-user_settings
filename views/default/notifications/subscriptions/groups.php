<?php

$title = elgg_echo('notifications:subscriptions:changesettings:groups');
$rows = elgg_view('notifications/subscriptions/rows/groups', $vars);
if (!$rows) {
	return;
}

$desc = elgg_format_element('p', [
	'class' => 'elgg-text-help man',
], elgg_echo('notifications:subscriptions:groups:description'));

$table = elgg_view('notifications/subscriptions/table', [
	'rows' => $rows,
]);

echo elgg_view_module('info', $title, $desc . $table, [
	'class' => 'elgg-subscriptions-module',
]);
