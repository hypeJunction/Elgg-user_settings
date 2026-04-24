<?php

namespace UserSettings;

use Elgg\IntegrationTestCase;

/**
 * Tests for notification settings persistence.
 *
 * The notificationsettings/save action wires three effects per delivery method:
 *   1. set_user_notification_setting()  (private settings)
 *   2. user->{collections_notifications_preferences_$method} metadata
 *   3. notify$method relationships from user to other users/groups
 *
 * We can't drive the action directly from IntegrationTestCase
 * (no executeAction()), so we exercise the same primitives.
 */
class NotificationSettingsTest extends IntegrationTestCase {

    public function up() {
    }

    public function down() {
    }

    /**
     * @return string
     */
    public function getPluginID(): string {
        return '';
    }

    /**
     * @return void
     */
    public function testSetUserNotificationSettingPersists(): void {
        $user = $this->createUser();

        $user->setNotificationSetting('email', true);
        $this->assertTrue((bool) ($user->getNotificationSettings()['email'] ?? false));
    }

    /**
     * @return void
     */
    public function testCollectionPreferencesMetadataPersists(): void {
        $user = $this->createUser();
        $user->collections_notifications_preferences_email = '-1';

        _elgg_services()->entityCache->delete($user->guid);
        $loaded = get_entity($user->guid);
        $this->assertEquals('-1', $loaded->collections_notifications_preferences_email);
    }

    /**
     * @return void
     */
    public function testSubscriptionRelationshipCreated(): void {
        $subscriber = $this->createUser();
        $target = $this->createUser();

        $subscriber->addRelationship($target->guid, 'notifyemail');
        $this->assertTrue($subscriber->hasRelationship($target->guid, 'notifyemail'));
    }

    /**
     * @return void
     */
    public function testRemoveEntityRelationshipsClearsSubscriptions(): void {
        $subscriber = $this->createUser();
        $target = $this->createUser();

        $subscriber->addRelationship($target->guid, 'notifyemail');
        $this->assertTrue($subscriber->hasRelationship($target->guid, 'notifyemail'));

        $subscriber->removeAllRelationships('notifyemail');
        $this->assertFalse($subscriber->hasRelationship($target->guid, 'notifyemail'));
    }

    /**
     * @return void
     */
    public function testNonOwnerCannotModifyOtherUserSubscriptions(): void {
        $owner = $this->createUser();
        $other = $this->createUser();

        // Iron law: a non-admin non-owner must not be able to edit the user
        // entity whose notifications settings they're attempting to change.
        _elgg_services()->session_manager->setLoggedInUser($other);
        $this->assertFalse($owner->canEdit($other->guid));
        _elgg_services()->session_manager->removeLoggedInUser();
    }

    /**
     * @return void
     */
    public function testOwnerCanModifyOwnSubscriptions(): void {
        $owner = $this->createUser();

        _elgg_services()->session_manager->setLoggedInUser($owner);
        $this->assertTrue($owner->canEdit($owner->guid));
        _elgg_services()->session_manager->removeLoggedInUser();
    }

    /**
     * @return void
     */
    public function testAdminCanModifyOtherUserSubscriptions(): void {
        $owner = $this->createUser();
        $admin = $this->createUser();
        $admin->makeAdmin();

        _elgg_services()->session_manager->setLoggedInUser($admin);
        $this->assertTrue($owner->canEdit($admin->guid));
        _elgg_services()->session_manager->removeLoggedInUser();
    }
}
