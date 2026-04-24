<?php

return [
	'plugin' => [
		'id' => 'user_settings',
		'name' => 'User Settings',
		'version' => '1.2.0',
		'description' => 'Improves UI/UX of user settings and notification preferences pages.',
		'author' => 'Ismayil Khayredinov',
		'category' => 'notifications',
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
