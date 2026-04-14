<?php

/**
 * Elgg notifications
 *
 * @package ElggNotifications
 */

$current_user = elgg_get_logged_in_user_entity();

$guid = (int) get_input('guid', 0);
if (!$guid || !($user = get_entity($guid))) {
	return elgg_error_response();
}
if (($user->guid != $current_user->guid) && !$current_user->isAdmin()) {
	return elgg_error_response();
}

$NOTIFICATION_HANDLERS = _elgg_services()->notifications->getMethods();
$subscriptions = array();
foreach ($NOTIFICATION_HANDLERS as $method => $foo) {
	$personal[$method] = get_input($method.'personal');
	$user->setNotificationSetting($method, ($personal[$method] == '1') ? true : false);

	$collections[$method] = get_input($method.'collections');
	$metaname = 'collections_notifications_preferences_' . $method;
	$user->$metaname = $collections[$method];

	$subscriptions[$method] = get_input($method.'subscriptions');
	remove_entity_relationships($user->guid, 'notify' . $method, false, 'user');
	remove_entity_relationships($user->guid, 'notify' . $method, false, 'group');
}

// Add new ones
foreach ($subscriptions as $method => $subscription) {
	if (is_array($subscription) && !empty($subscription)) {
		foreach ($subscription as $subscriptionperson) {
			$user->addRelationship($subscriptionperson, 'notify' . $method);
		}
	}
}

return elgg_ok_response('', elgg_echo('notifications:subscriptions:success'));
