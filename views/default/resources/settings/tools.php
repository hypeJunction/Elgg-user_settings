<?php

$entity = elgg_extract('entity', $vars, elgg_get_page_owner_entity());

if (!$entity instanceof ElggUser || !$entity->canEdit()) {
	return;
}

elgg_push_context('settings/plugins');

elgg_push_breadcrumb(elgg_echo('settings'), "settings");
elgg_push_breadcrumb($entity->getDisplayName(), "settings/user/$entity->username");

$plugin_id = elgg_extract('section', $vars);

if ($plugin_id) {
	$plugin = elgg_get_plugin_from_id($plugin_id);

	if (!$plugin) {
		elgg_register_error_message(elgg_echo('PluginException:InvalidID', array($plugin_id)));
		throw new \Elgg\Exceptions\Http\EntityNotFoundException();
	}

	if (elgg_language_key_exists($plugin_id . ':usersettings:title')) {
		$title = elgg_echo($plugin_id . ':usersettings:title');
	} else {
		$title = $plugin->getManifest()->getName();
	}

	elgg_push_breadcrumb(elgg_echo('user:settings:tools'), "settings/plugins/$entity->username");

	$content = elgg_view_form('plugins/usersettings/save', array(), array('entity' => $plugin));
	$filter = false;
} else {
	$mod = array();
	$active_plugins = elgg_get_plugins();
	foreach ($active_plugins as $plugin) {
		$plugin_id = $plugin->getID();
		if (elgg_view_exists("usersettings/$plugin_id/edit") || elgg_view_exists("plugins/$plugin_id/usersettings")) {
			if (elgg_language_key_exists($plugin_id . ':usersettings:title')) {
				$mod_title = elgg_echo($plugin_id . ':usersettings:title');
			} else {
				$mod_title = $plugin->getManifest()->getName();
			}
			$mod_body = elgg_view_form('plugins/usersettings/save', array(), array('entity' => $plugin));
			if (empty($mod_body)) {
				continue;
			}
			$mod[$mod_title] = elgg_view_module('info', $mod_title, $mod_body);
		}
	}
	ksort($mod);
	$title = elgg_echo('user:settings:tools');
	$content = implode('', $mod);
	if (empty($mod)) {
		$content = elgg_format_element('p', ['class' => 'elgg-no-reuslts'], elgg_echo('user:settings:tools:no_results'));
	}
	$filter = elgg_view('filters/settings', array(
		'filter_context' => 'tools',
		'entity' => $entity,
	));
}

if (!$content) {
	return;
}

$layout = elgg_view_layout('default', array(
	'content' => $content,
	'title' => $title,
	'filter' => $filter,
		));

echo elgg_view_page($title, $layout);
