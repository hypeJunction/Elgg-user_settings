<?php

namespace UserSettings;

class Router {

	/**
	 * Routes notifications pages to the user settings pages
	 *
	 * @param \Elgg\Event $event "route", "notifications"
	 * @return array|void
	 */
	public static function notificationsRoute(\Elgg\Event $event) {
		$return = $event->getValue();

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
			$user = \elgg_get_user_by_username($username);
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
	 * @param \Elgg\Event $event "route", "profile"
	 * @return array|void
	 */
	public static function profileRoute(\Elgg\Event $event) {
		$return = $event->getValue();

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
	 * @param \Elgg\Event $event "route", "avatar"
	 * @return array|void
	 */
	public static function avatarRoute(\Elgg\Event $event) {
		$return = $event->getValue();

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
