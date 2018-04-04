<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

// CMG Imports
use cmsgears\core\common\base\Migration;

/**
 * The notify migration inserts the database tables of notify module. It also insert the foreign
 * keys if FK flag of migration component is true.
 *
 * @since 1.0.0
 */
class m160622_015405_notify extends Migration {

	// Public Variables

	public $fk;
	public $options;

	// Private Variables

	private $prefix;

	public function init() {

		// Table prefix
		$this->prefix		= Yii::$app->migration->cmgPrefix;

		// Get the values via config
		$this->fk			= Yii::$app->migration->isFk();
		$this->options		= Yii::$app->migration->getTableOptions();

		// Default collation
		if( $this->db->driverName === 'mysql' ) {

			$this->options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
	}

	public function up() {

		// Event
		$this->upEvent();
		$this->upEventParticipant();
		$this->upEventReminder();

		// Notification
		$this->upNotification();

		// Announcement
		$this->upAnnouncement();

		// Activity
		$this->upActivity();

		if( $this->fk ) {

			$this->generateForeignKeys();
		}
	}

	private function upEvent() {

		$this->createTable( $this->prefix . 'notify_event', [
			'id' => $this->bigPrimaryKey( 20 ),
			'siteId' => $this->bigInteger( 20 )->notNull(),
			'templateId' => $this->bigInteger( 20 ),
			'userId' => $this->bigInteger( 20 ),
			'avatarId' => $this->bigInteger( 20 ),
			'bannerId' => $this->bigInteger( 20 ),
			'videoId' => $this->bigInteger( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'parentId' => $this->bigInteger( 20 ),
			'parentType' => $this->string( Yii::$app->core->mediumText ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'slug' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'icon' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'title' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'description' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
			'preReminderCount' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'preReminderInterval' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'preIntervalUnit' => $this->smallInteger( 6 )->defaultValue( 2 ),
			'postReminderCount' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'postReminderInterval' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'postIntervalUnit' => $this->smallInteger( 6 )->defaultValue( 2 ),
			'admin' => $this->boolean()->notNull()->defaultValue( false ),
			'multiUser' => $this->boolean()->notNull()->defaultValue( false ),
			'status' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'scheduledAt' => $this->dateTime()->notNull(),
			'triggeredAt' => $this->dateTime(),
			'content' => $this->mediumText(),
			'data' => $this->mediumText(),
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
		], $this->options );

		// Index for columns site, creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'event_site', $this->prefix . 'notify_event', 'siteId' );
		$this->createIndex( 'idx_' . $this->prefix . 'event_template', $this->prefix . 'notify_event', 'templateId' );
		$this->createIndex( 'idx_' . $this->prefix . 'event_user', $this->prefix . 'notify_event', 'userId' );
		$this->createIndex( 'idx_' . $this->prefix . 'event_avatar', $this->prefix . 'notify_event', 'avatarId' );
		$this->createIndex( 'idx_' . $this->prefix . 'event_banner', $this->prefix . 'notify_event', 'bannerId' );
		$this->createIndex( 'idx_' . $this->prefix . 'event_video', $this->prefix . 'notify_event', 'videoId' );
		$this->createIndex( 'idx_' . $this->prefix . 'event_creator', $this->prefix . 'notify_event', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'event_modifier', $this->prefix . 'notify_event', 'modifiedBy' );
	}

	private function upEventParticipant() {

		$this->createTable( $this->prefix . 'notify_event_participant', [
			'id' => $this->bigPrimaryKey( 20 ),
			'eventId' => $this->bigInteger( 20 )->notNull(),
			'userId' => $this->bigInteger( 20 )->notNull(),
			'active' => $this->boolean()->notNull()->defaultValue( true ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime()
		], $this->options );

		// Index for columns user
		$this->createIndex( 'idx_' . $this->prefix . 'event_participant_parent', $this->prefix . 'notify_event_participant', 'eventId' );
		$this->createIndex( 'idx_' . $this->prefix . 'event_participant_user', $this->prefix . 'notify_event_participant', 'userId' );
	}

	private function upEventReminder() {

		$this->createTable( $this->prefix . 'notify_event_reminder', [
			'id' => $this->bigPrimaryKey( 20 ),
			'siteId' => $this->bigInteger( 20 )->notNull(),
			'eventId' => $this->bigInteger( 20 )->notNull(),
			'userId' => $this->bigInteger( 20 ),
			'title' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'description' => $this->string( Yii::$app->core->xtraLargeText )->notNull(),
			'link' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'admin' => $this->boolean()->notNull()->defaultValue( false ),
			'adminLink' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'consumed' => $this->boolean()->notNull()->defaultValue( false ),
			'trash' => $this->boolean()->notNull()->defaultValue( false ),
			'scheduledAt' => $this->dateTime(),
			'content' => $this->mediumText(),
			'data' => $this->mediumText(),
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
		], $this->options );

		// Index for columns user
		$this->createIndex( 'idx_' . $this->prefix . 'event_reminder_site', $this->prefix . 'notify_event_reminder', 'siteId' );

		$this->createIndex( 'idx_' . $this->prefix . 'event_reminder_parent', $this->prefix . 'notify_event_reminder', 'eventId' );
		$this->createIndex( 'idx_' . $this->prefix . 'event_reminder_user', $this->prefix . 'notify_event_reminder', 'userId' );
	}

	private function upNotification() {

		$this->createTable( $this->prefix . 'notify_notification', [
			'id' => $this->bigPrimaryKey( 20 ),
			'siteId' => $this->bigInteger( 20 )->notNull(),
			'userId' => $this->bigInteger( 20 ),
			'createdBy' => $this->bigInteger( 20 ),
			'modifiedBy' => $this->bigInteger( 20 ),
			'parentId' => $this->bigInteger( 20 ),
			'parentType' => $this->string( Yii::$app->core->mediumText ),
			'title' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'description' => $this->string( Yii::$app->core->xtraLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull()->defaultValue( 'default' ),
			'ip' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'ipNum' => $this->integer( 11 )->defaultValue( 0 ),
			'agent' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'link' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'admin' => $this->boolean()->notNull()->defaultValue( false ),
			'adminLink' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'consumed' => $this->boolean()->notNull()->defaultValue( false ),
			'trash' => $this->boolean()->notNull()->defaultValue( false ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'content' => $this->mediumText(),
			'data' => $this->mediumText(),
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
		], $this->options );

		// Index for columns site, creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'notification_site', $this->prefix . 'notify_notification', 'siteId' );
		$this->createIndex( 'idx_' . $this->prefix . 'notification_user', $this->prefix . 'notify_notification', 'userId' );
		$this->createIndex( 'idx_' . $this->prefix . 'notification_creator', $this->prefix . 'notify_notification', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'notification_modifier', $this->prefix . 'notify_notification', 'modifiedBy' );
	}

	private function upAnnouncement() {

		$this->createTable( $this->prefix . 'notify_announcement', [
			'id' => $this->bigPrimaryKey( 20 ),
			'siteId' => $this->bigInteger( 20 )->notNull(),
			'bannerId' => $this->bigInteger( 20 ),
			'createdBy' => $this->bigInteger( 20 ),
			'modifiedBy' => $this->bigInteger( 20 ),
			'parentId' => $this->bigInteger( 20 ),
			'parentType' => $this->string( Yii::$app->core->mediumText ),
			'title' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'description' => $this->string( Yii::$app->core->xtraLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull()->defaultValue( 'default' ),
			'status' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'access' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'link' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'adminLink' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'content' => $this->mediumText(),
			'data' => $this->mediumText(),
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
		], $this->options );

		// Index for columns site, creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'announcement_site', $this->prefix . 'notify_announcement', 'siteId' );
		$this->createIndex( 'idx_' . $this->prefix . 'announcement_banner', $this->prefix . 'notify_announcement', 'bannerId' );
		$this->createIndex( 'idx_' . $this->prefix . 'notification_creator', $this->prefix . 'notify_announcement', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'notification_modifier', $this->prefix . 'notify_announcement', 'modifiedBy' );
	}

	private function upActivity() {

		$this->createTable( $this->prefix . 'notify_activity', [
			'id' => $this->bigPrimaryKey( 20 ),
			'siteId' => $this->bigInteger( 20 )->notNull(),
			'userId' => $this->bigInteger( 20 )->notNull(),
			'parentId' => $this->bigInteger( 20 ),
			'parentType' => $this->string( Yii::$app->core->mediumText ),
			'title' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'description' => $this->string( Yii::$app->core->xtraLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull()->defaultValue( 'default' ),
			'ip' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'ipNum' => $this->integer( 11 )->defaultValue( 0 ),
			'agent' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'link' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'admin' => $this->boolean()->notNull()->defaultValue( false ),
			'adminLink' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'consumed' => $this->boolean()->notNull()->defaultValue( false ),
			'trash' => $this->boolean()->notNull()->defaultValue( false ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'content' => $this->mediumText(),
			'data' => $this->mediumText(),
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
		], $this->options );

		// Index for columns user
		$this->createIndex( 'idx_' . $this->prefix . 'activity_site', $this->prefix . 'notify_activity', 'siteId' );
		$this->createIndex( 'idx_' . $this->prefix . 'activity_user', $this->prefix . 'notify_activity', 'userId' );
	}

	private function generateForeignKeys() {

		// Event
		$this->addForeignKey( 'fk_' . $this->prefix . 'event_site', $this->prefix . 'notify_event', 'siteId', $this->prefix . 'core_site', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'event_template', $this->prefix . 'notify_event', 'templateId', $this->prefix . 'core_template', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'event_user', $this->prefix . 'notify_event', 'userId', $this->prefix . 'core_user', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'event_avatar', $this->prefix . 'notify_event', 'avatarId', $this->prefix . 'core_file', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'event_banner', $this->prefix . 'notify_event', 'bannerId', $this->prefix . 'core_file', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'event_video', $this->prefix . 'notify_event', 'videoId', $this->prefix . 'core_file', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'event_creator', $this->prefix . 'notify_event', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'event_modifier', $this->prefix . 'notify_event', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Event Participant
		$this->addForeignKey( 'fk_' . $this->prefix . 'event_participant_parent', $this->prefix . 'notify_event_participant', 'eventId', $this->prefix . 'notify_event', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'event_participant_user', $this->prefix . 'notify_event_participant', 'userId', $this->prefix . 'core_user', 'id', 'CASCADE' );

		// Event Reminder
		$this->addForeignKey( 'fk_' . $this->prefix . 'event_reminder_site', $this->prefix . 'notify_event_reminder', 'siteId', $this->prefix . 'core_site', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'event_reminder_parent', $this->prefix . 'notify_event_reminder', 'eventId', $this->prefix . 'notify_event', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'event_reminder_user', $this->prefix . 'notify_event_reminder', 'userId', $this->prefix . 'core_user', 'id', 'CASCADE' );

		// Notification
		$this->addForeignKey( 'fk_' . $this->prefix . 'notification_site', $this->prefix . 'notify_notification', 'siteId', $this->prefix . 'core_site', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'notification_user', $this->prefix . 'notify_notification', 'userId', $this->prefix . 'core_user', 'id', 'CASCADE' );
		//$this->addForeignKey( 'fk_' . $this->prefix . 'notification_creator', $this->prefix . 'notify_notification', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'notification_creator', $this->prefix . 'notify_notification', 'createdBy', $this->prefix . 'core_user', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'notification_modifier', $this->prefix . 'notify_notification', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Announcement
		$this->addForeignKey( 'fk_' . $this->prefix . 'announcement_site', $this->prefix . 'notify_announcement', 'siteId', $this->prefix . 'core_site', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'announcement_banner', $this->prefix . 'notify_announcement', 'bannerId', $this->prefix . 'core_file', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'announcement_creator', $this->prefix . 'notify_announcement', 'createdBy', $this->prefix . 'core_user', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'announcement_modifier', $this->prefix . 'notify_announcement', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Activity
		$this->addForeignKey( 'fk_' . $this->prefix . 'activity_site', $this->prefix . 'notify_activity', 'siteId', $this->prefix . 'core_site', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'activity_user', $this->prefix . 'notify_activity', 'userId', $this->prefix . 'core_user', 'id', 'CASCADE' );
	}

	public function down() {

		if( $this->fk ) {

			$this->dropForeignKeys();
		}

		$this->dropTable( $this->prefix . 'notify_event' );
		$this->dropTable( $this->prefix . 'notify_event_participant' );
		$this->dropTable( $this->prefix . 'notify_event_reminder' );

		$this->dropTable( $this->prefix . 'notify_notification' );
		$this->dropTable( $this->prefix . 'notify_announcement' );

		$this->dropTable( $this->prefix . 'notify_activity' );
	}

	private function dropForeignKeys() {

		// Event
		$this->dropForeignKey( 'fk_' . $this->prefix . 'event_site', $this->prefix . 'notify_event' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'event_template', $this->prefix . 'notify_event' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'event_user', $this->prefix . 'notify_event' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'event_avatar', $this->prefix . 'notify_event' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'event_banner', $this->prefix . 'notify_event' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'event_video', $this->prefix . 'notify_event' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'event_creator', $this->prefix . 'notify_event' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'event_modifier', $this->prefix . 'notify_event' );

		// Event Participant
		$this->dropForeignKey( 'fk_' . $this->prefix . 'event_participant_parent', $this->prefix . 'notify_event_participant' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'event_participant_user', $this->prefix . 'notify_event_participant' );

		// Event Reminder
		$this->dropForeignKey( 'fk_' . $this->prefix . 'event_reminder_site', $this->prefix . 'notify_event_reminder' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'event_reminder_parent', $this->prefix . 'notify_event_reminder' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'event_reminder_user', $this->prefix . 'notify_event_reminder' );

		// Notification
		$this->dropForeignKey( 'fk_' . $this->prefix . 'notification_site', $this->prefix . 'notify_notification' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'notification_user', $this->prefix . 'notify_notification' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'notification_creator', $this->prefix . 'notify_notification' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'notification_modifier', $this->prefix . 'notify_notification' );

		// Announcement
		$this->dropForeignKey( 'fk_' . $this->prefix . 'announcement_site', $this->prefix . 'notify_announcement' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'announcement_banner', $this->prefix . 'notify_announcement' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'announcement_creator', $this->prefix . 'notify_announcement' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'announcement_modifier', $this->prefix . 'notify_announcement' );

		// Activity
		$this->dropForeignKey( 'fk_' . $this->prefix . 'activity_site', $this->prefix . 'notify_activity' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'activity_user', $this->prefix . 'notify_activity' );
	}

}
