<?php

return [
	'plugin' => [
		'id' => 'user_settings',
		'name' => 'User Settings',
		'version' => '7.0.0',
		'description' => 'Improves UI/UX of user settings and notification preferences pages.',
		'author' => 'Ismayil Khayredinov',
		'category' => 'notifications',
		// The account/notification setting views call elgg_view_input(), a BC shim
		// that ships with forms_api. Without this dependency those pages fatal with
		// "Call to undefined function elgg_view_input()" wherever forms_api is
		// inactive (bd elgg-migrate-ckn0c).
		'dependencies' => [
			'forms_api' => [
				'position' => 'after',
			],
		],
	],

	'bootstrap' => \UserSettings\Bootstrap::class,

	'actions' => [
		'notificationsettings/save' => [],
	],

	'routes' => [
		'settings' => [
			'path' => '/settings/{segments}',
			'resource' => 'settings',
			'requirements' => [
				'segments' => '.+',
			],
			'defaults' => [
				'segments' => '',
			],
		],
	],

	'events' => [
		'route' => [
			'notifications' => [
				\UserSettings\Router::class . '::notificationsRoute' => [],
			],
			'profile' => [
				\UserSettings\Router::class . '::profileRoute' => [],
			],
			'avatar' => [
				\UserSettings\Router::class . '::avatarRoute' => [],
			],
		],
	],

	'view_extensions' => [
		'elgg.css' => [
			'elements/tables/notifications.css' => [],
		],
	],
];
