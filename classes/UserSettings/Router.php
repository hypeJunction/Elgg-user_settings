<?php

namespace UserSettings;

class Router {

	/**
	 * Routes notifications pages to the user settings pages
	 *
	 * @param \Elgg\Hook $hook "route", "notifications"
	 * @return array|void
	 */
	public static function notificationsRoute(\Elgg\Hook $hook) {
		$return = $hook->getValue();

		$identifier = \elgg_extract('identifier', $return);
		$segments = (array) \elgg_extract('segments', $return, []);

		if ($identifier != 'notifications') {
			return;
		}

		$page = array_shift($segments);
		$username = array_shift($segments);

		if (!$page) {
			$page = 'personal';
		}

		if (!$username) {
			$user = \elgg_get_logged_in_user_entity();
		} else {
			$user = \get_user_by_username($username);
		}

		if (in_array($page, ['personal', 'group'])) {
			return [
				'identifier' => 'settings',
				'segments' => ['notifications', $user->username],
			];
		}
	}

	/**
	 * Route profile edit page
	 *
	 * @param \Elgg\Hook $hook "route", "profile"
	 * @return array|void
	 */
	public static function profileRoute(\Elgg\Hook $hook) {
		$return = $hook->getValue();

		if (!is_array($return)) {
			return;
		}

		$identifier = \elgg_extract('identifier', $return);
		$segments = \elgg_extract('segments', $return);

		$username = array_shift($segments);
		$page = array_shift($segments);

		if ($page == 'edit') {
			return [
				'identifier' => 'settings',
				'segments' => ['profile', $username],
			];
		}
	}

	/**
	 * Route avatar edit page
	 *
	 * @param \Elgg\Hook $hook "route", "avatar"
	 * @return array|void
	 */
	public static function avatarRoute(\Elgg\Hook $hook) {
		$return = $hook->getValue();

		if (!is_array($return)) {
			return;
		}

		$identifier = \elgg_extract('identifier', $return);
		$segments = \elgg_extract('segments', $return);

		$page = array_shift($segments);
		$username = array_shift($segments);

		if ($page == 'edit') {
			return [
				'identifier' => 'settings',
				'segments' => ['avatar', $username],
			];
		}
	}
}
