<?php

namespace UserSettings\Upgrades;

use Elgg\Upgrade\AsynchronousUpgrade;
use Elgg\Upgrade\Result;

/**
 * Normalize legacy yes/no string plugin settings to booleans.
 *
 * Elgg 5.x switch inputs store boolean values. Pre-5.x stored 'yes'/'no'
 * strings, which evaluate truthy under a (bool) cast and break the
 * show_language toggle. This upgrade rewrites stored values in place.
 *
 * @since 1.3.0
 */
class MigrateSwitchSettings extends AsynchronousUpgrade {

	/**
	 * Plugin settings that were stored as yes/no strings.
	 *
	 * @var string[]
	 */
	protected const SETTINGS = [
		'show_language',
	];

	/**
	 * {@inheritDoc}
	 */
	public function getVersion(): int {
		return 2026060201;
	}

	/**
	 * {@inheritDoc}
	 */
	public function needsIncrementOffset(): bool {
		return false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function shouldBeSkipped(): bool {
		return $this->countItems() === 0;
	}

	/**
	 * {@inheritDoc}
	 */
	public function countItems(): int {
		$plugin = elgg_get_plugin_from_id('user_settings');
		if (!$plugin instanceof \ElggPlugin) {
			return 0;
		}

		$count = 0;
		foreach (self::SETTINGS as $name) {
			$value = $plugin->getSetting($name);
			if (is_string($value) && in_array(strtolower($value), ['yes', 'no'], true)) {
				$count++;
			}
		}

		return $count;
	}

	/**
	 * {@inheritDoc}
	 */
	public function run(Result $result, $offset): Result {
		$plugin = elgg_get_plugin_from_id('user_settings');
		if (!$plugin instanceof \ElggPlugin) {
			$result->markComplete();
			return $result;
		}

		foreach (self::SETTINGS as $name) {
			$value = $plugin->getSetting($name);
			if (!is_string($value) || !in_array(strtolower($value), ['yes', 'no'], true)) {
				continue;
			}

			if ($plugin->setSetting($name, strtolower($value) === 'yes')) {
				$result->addSuccesses(1);
			} else {
				$result->addError("Failed to migrate setting: {$name}");
			}
		}

		return $result;
	}
}
