<?php

namespace UserSettings;

use Elgg\DefaultPluginBootstrap;

class Bootstrap extends DefaultPluginBootstrap {

	/**
	 * {@inheritdoc}
	 */
	public function init() {
		$plugin = $this->plugin;

		if ($plugin->getSetting('show_language') === 'no') {
			\elgg_unregister_event_handler('usersettings:save', 'user', '_elgg_set_user_language');
			\elgg_unextend_view('forms/account/settings', 'core/settings/account/language');
		}
	}
}
