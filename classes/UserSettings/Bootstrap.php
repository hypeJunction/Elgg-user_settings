<?php

namespace UserSettings;

use Elgg\DefaultPluginBootstrap;

class Bootstrap extends DefaultPluginBootstrap {

	/**
	 * {@inheritdoc}
	 */
	public function init() {
		$plugin = $this->plugin;

		$show_language = $plugin->getSetting('show_language');
		if ($show_language !== null && !(bool) $show_language) {
			\elgg_unregister_event_handler('usersettings:save', 'user', '_elgg_set_user_language');
			\elgg_unextend_view('forms/account/settings', 'core/settings/account/language');
		}
	}
}
