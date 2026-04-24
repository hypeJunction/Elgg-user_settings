# user_settings — Plugin Architecture (Elgg 5.x)

## Summary

Improves the UI/UX of user settings and notification preferences pages by unifying them under a single `/settings/` route. Also rewrites `/notifications/`, `/profile/<name>/edit`, and `/avatar/edit/` routes into the settings layout.

**Plugin ID**: `user_settings`  
**Version**: `2.0.0`  
**Category**: notifications

## Bootstrap

`UserSettings\Bootstrap` (registered via `'bootstrap'` key in `elgg-plugin.php`) — sets up any runtime initialization.

## Routes

| Route name | Path | Resource view |
|-----------|------|---------------|
| `settings` | `/settings/{segments}` | `resources/settings` |

## Registered Events (5.x `'events'` key)

| Event name | Type | Handler |
|-----------|------|---------|
| `route` | `notifications` | `UserSettings\Router::notificationsRoute` |
| `route` | `profile` | `UserSettings\Router::profileRoute` |
| `route` | `avatar` | `UserSettings\Router::avatarRoute` |

### Route rewriting logic

- `/notifications/personal` and `/notifications/group/<username>` → `/settings/notifications/<username>`
- `/profile/<username>/edit` → `/settings/profile/<username>`
- `/avatar/edit/<username>` → `/settings/avatar/<username>`

## Actions

| Action name | File |
|-------------|------|
| `notificationsettings/save` | `actions/notificationsettings/save.php` |

## Views

| View | Purpose |
|------|---------|
| `resources/settings/user` | Main settings page |
| `resources/settings/notifications` | Notification settings page |
| `resources/settings/profile` | Profile edit page |
| `resources/settings/avatar` | Avatar edit page |
| `notifications/subscriptions/rows/personal.php` | Personal notification delivery method row |
| `notifications/subscriptions/rows/collections.php` | Collection notification preferences row |
| `plugins/user_settings/settings.php` | Plugin settings form (show_statistics, show_language) |

## View Extensions

| Base view | Extension |
|-----------|-----------|
| `elgg.css` | `elements/tables/notifications.css` |

## Plugin Settings

| Setting | Default | Description |
|---------|---------|-------------|
| `show_statistics` | `true` | Show statistics section in user settings |
| `show_language` | `true` | Show language preference in user settings |

## Migration Notes (4.x → 5.x)

### Hooks merged into events system

| Change | Before (4.x) | After (5.x) |
|--------|-------------|-------------|
| `elgg-plugin.php` key | `'hooks'` | `'events'` |
| Callback type hint | `\Elgg\Hook` | `\Elgg\Event` |
| Bootstrap unregister | `elgg_unregister_plugin_hook_handler()` | `elgg_unregister_event_handler()` |

### Removed functions (5.x)

| Removed (4.x) | Replacement (5.x) |
|--------------|-------------------|
| `get_user_by_username()` | `elgg_get_user_by_username()` |
| `remove_entity_relationships()` | `$entity->removeAllRelationships($relationship)` |
| `ElggSession::setLoggedInUser()` | `_elgg_services()->session_manager->setLoggedInUser()` |
| `Elgg\HooksRegistrationService\Hook` | `Elgg\Event` (class removed, use base Event) |

### Test adaptations

`\Elgg\HooksRegistrationService\Hook` class was removed. Use `\Elgg\Event` instead:
```php
use Elgg\Event;
$event = new Event(elgg(), 'route', 'notifications', $value, []);
```

Session management in tests moved to `session_manager` service:
```php
_elgg_services()->session_manager->setLoggedInUser($user);
_elgg_services()->session_manager->removeLoggedInUser();
```

## Migration Notes (3.x → 4.x)

### Removed functions

| Removed (3.x) | Replacement (4.x) |
|--------------|-------------------|
| `set_user_notification_setting($guid, $method, $bool)` | `$user->setNotificationSetting($method, $bool)` |
| `get_user_notification_settings($guid)` | `$user->getNotificationSettings()` — returns **array** (not object) |
| `elgg_view_input($type, $vars)` | `elgg_view_field(['#type' => ..., '#label' => ...])` |

### forms_api dependency removed

`forms_api` was previously required because it provided the `elgg_view_input()` polyfill. In Elgg 4.x, core provides `elgg_view_field()` directly, so the dependency is no longer needed at runtime.

### Test fixes

`\Elgg\Hook` is an **interface** in Elgg 4.x — cannot be instantiated. Use:
```php
use Elgg\HooksRegistrationService\Hook;
// Constructor: (PublicContainer $dic, $name, $type, $value, $params)
$hook = new Hook(elgg(), 'route', 'notifications', $value, []);
```

Note: argument order changed from 3.x — `$value` (return value) comes **before** `$params`.
