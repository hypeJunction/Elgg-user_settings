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
			\elgg_unregister_plugin_hook_handler('usersettings:save', 'user', 'Elgg\Users\Settings::setLanguage');
			\elgg_unextend_view('forms/account/settings', 'core/settings/account/language');
		}
	}
}
