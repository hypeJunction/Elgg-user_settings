<?php

$title = elgg_echo('notifications:subscriptions:personal:title');
$rows = elgg_view('notifications/subscriptions/rows/personal', $vars);
if (!$rows) {
	return;
}

$table = elgg_view('notifications/subscriptions/table', [
	'rows' => $rows,
]);
echo elgg_view_module('info', $title, $table, [
	'class' => 'elgg-subscriptions-module',
]);
