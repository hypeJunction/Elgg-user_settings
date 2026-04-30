<a name="2.2.0"></a>
# [2.2.0] - 2026-04-30

### Migration: Elgg 7.x

* **composer.json**: `php >=8.3`, `elgg/elgg ~7.0.0`, added `ext-intl: *`
* **No code changes**: plugin's public APIs (route events, view extensions,
  settings actions) are unaffected by 7.x breaking changes (ElggObject
  abstract, CSS Crush removal, Symfony Mailer, notification handler renames,
  Font Awesome v7) — none apply to user_settings
* **Docker stack**: added `docker/elgg7/` (PHP 8.3, MySQL 8.0, Elgg ~7.0.0
  dev-stability, PHPUnit ^11). Install script uses
  `_elgg_services()->systemCache->clear()` (replaces removed
  `elgg_reset_system_cache()`); admin & testuser passwords ≥16 chars for
  7.x's enforced minimum length
* **Verification**: 23 PHPUnit tests / 166 assertions pass on Elgg 7.x with
  zero adaptation; PHP syntax clean; homepage/login render; no Apache PHP
  errors; security sweep clean; post-migration verifier finds no
  future-version API leakage

<a name="2.1.0"></a>
# [2.1.0] - 2026-04-30

### Migration: Elgg 6.x

* **composer.json**: `elgg/elgg ^6.0`
* **collections.js**: AMD `define(function(require){...})` → ES module `import $ from 'jquery'`
* **resources/settings/avatar.php**: `isset($entity->icontime)` → `$entity->hasIcon('small')` (`icontime` metadata removed in 6.x)
* **Docker stack**: added `docker/elgg6/` stack (PHP 8.2, MySQL 8.0, Elgg 6.x, PHPUnit ^10)

<a name="2.0.0"></a>
# [2.0.0] - 2026-04-24

### Migration: Elgg 5.x

* **elgg-plugin.php**: `'hooks'` key renamed to `'events'` (hooks and events merged in 5.x)
* **Router.php**: `\Elgg\Hook` → `\Elgg\Event`, `get_user_by_username()` → `elgg_get_user_by_username()`
* **Bootstrap.php**: `elgg_unregister_plugin_hook_handler()` → `elgg_unregister_event_handler()`
* **composer.json**: `php >=8.2`, `elgg/elgg ^5.0`, version `2.0.0`
* **Docker stack**: PHP 8.2, MySQL 8.0, Elgg 5.1.x, Playwright v1.59.1, `ELGG_SITE_URL=http://elgg/`
* **Tests**: `Elgg\HooksRegistrationService\Hook` → `Elgg\Event`; session management via `session_manager` service; `remove_entity_relationships()` → `removeAllRelationships()`

<a name="1.2.0"></a>
# [1.2.0] - 2026-04-14

### Migration: Elgg 4.x

* Removed `manifest.xml` — plugin metadata moved to `elgg-plugin.php` `'plugin'` key
* Updated `composer.json`: `php >=7.4`, `elgg/elgg ^4.0`, `composer/installers ^2.0`
* **actions/notificationsettings/save.php**: `set_user_notification_setting()` removed in 4.x → `$user->setNotificationSetting()`
* **views/notifications/subscriptions/rows/personal.php**: `get_user_notification_settings()` removed → `$user->getNotificationSettings()` (returns array, not object)
* **views/plugins/user_settings/settings.php**: `elgg_view_input()` removed → `elgg_view_field()` with `#type`/`#label` keys
* Dropped `forms_api` runtime dependency — plugin now uses core `elgg_view_field()` directly
* Tests updated: `\Elgg\Hook` is now an interface; use `\Elgg\HooksRegistrationService\Hook(elgg(), name, type, value, params)`

<a name="1.1.2"></a>
## [1.1.2](https://github.com/hypeJunction/Elgg-user_settings/compare/1.1.1...v1.1.2) (2016-03-25)


### Bug Fixes

* **notifications:** removing group subscriptions works again ([2b0aec7](https://github.com/hypeJunction/Elgg-user_settings/commit/2b0aec7))



<a name="1.1.1"></a>
## [1.1.1](https://github.com/hypeJunction/Elgg-user_settings/compare/1.1.0...v1.1.1) (2016-03-20)


### Bug Fixes

* **avatar:** use custom avatar resource ([1b0973a](https://github.com/hypeJunction/Elgg-user_settings/commit/1b0973a))



<a name="1.1.0"></a>
# [1.1.0](https://github.com/hypeJunction/Elgg-user_settings/compare/1.0.3...v1.1.0) (2016-02-24)


### Bug Fixes

* **forms:** fix access input ([9be4ae4](https://github.com/hypeJunction/Elgg-user_settings/commit/9be4ae4))

### Features

* **pages:** add profile edit and avatar edit pages to settings interface ([9054463](https://github.com/hypeJunction/Elgg-user_settings/commit/9054463))



<a name="1.0.3"></a>
## [1.0.3](https://github.com/hypeJunction/Elgg-user_settings/compare/1.0.2...v1.0.3) (2016-02-08)




<a name="1.0.2"></a>
## [1.0.2](https://github.com/hypeJunction/Elgg-user_settings/compare/1.0.1...v1.0.2) (2016-02-08)




<a name="1.0.1"></a>
## 1.0.1 (2016-02-08)


### Bug Fixes

* **pages:** fix plugin settings label ([de34d03](https://github.com/hypeJunction/Elgg-user_settings/commit/de34d03))
* **pages:** fix typo in router ([594b989](https://github.com/hypeJunction/Elgg-user_settings/commit/594b989))
* **views:** add missing th in table header ([5e44f4c](https://github.com/hypeJunction/Elgg-user_settings/commit/5e44f4c))

### Features

* **releases:** initial commit ([0ae98f7](https://github.com/hypeJunction/Elgg-user_settings/commit/0ae98f7))
* **tools:** do not display empty plugin settings forms ([b5452a9](https://github.com/hypeJunction/Elgg-user_settings/commit/b5452a9))



<a name="1.0.0"></a>
# 1.0.0 (2016-02-03)


### Features

* **releases:** initial commit ([0ae98f7](https://github.com/hypeJunction/Elgg-user_settings/commit/0ae98f7))



