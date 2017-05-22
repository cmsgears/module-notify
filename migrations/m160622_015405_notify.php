<?php

class m160622_015405_notify extends \yii\db\Migration {

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
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'parentId' => $this->bigInteger( 20 ),
			'parentType' => $this->string( Yii::$app->core->mediumText ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'slug' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'icon' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'description' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'preReminderCount' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'preReminderInterval' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'preIntervalUnit' => $this->smallInteger( 6 )->defaultValue( 2 ),
			'postIntervalUnit' => $this->smallInteger( 6 )->defaultValue( 2 ),
			'postReminderCount' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'postReminderInterval' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'multiUser' => $this->boolean()->notNull()->defaultValue( false ),
			'status' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'scheduledAt' => $this->dateTime()->notNull(),
			'content' => $this->text(),
			'data' => $this->text()
		], $this->options );

		// Index for columns site, creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'event_site', $this->prefix . 'notify_event', 'siteId' );
		$this->createIndex( 'idx_' . $this->prefix . 'event_creator', $this->prefix . 'notify_event', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'event_modifier', $this->prefix . 'notify_event', 'modifiedBy' );
	}

	private function upEventParticipant() {

		$this->createTable( $this->prefix . 'notify_event_participant', [
			'id' => $this->bigPrimaryKey( 20 ),
			'eventId' => $this->bigInteger( 20 )->notNull(),
			'userId' => $this->bigInteger( 20 )->notNull(),
			'active' => $this->boolean()->notNull()->defaultValue( true )
		], $this->options );

		// Index for columns user
		$this->createIndex( 'idx_' . $this->prefix . 'event_participant_parent', $this->prefix . 'notify_event_participant', 'eventId' );
		$this->createIndex( 'idx_' . $this->prefix . 'event_participant_user', $this->prefix . 'notify_event_participant', 'userId' );
	}

	private function upEventReminder() {

		$this->createTable( $this->prefix . 'notify_event_reminder', [
			'id' => $this->bigPrimaryKey( 20 ),
			'eventId' => $this->bigInteger( 20 )->notNull(),
			'userId' => $this->bigInteger( 20 )->notNull(),
			'scheduledAt' => $this->dateTime(),
			'status' => $this->smallInteger( 6 )->defaultValue( 0 ),
		], $this->options );

		// Index for columns user
		$this->createIndex( 'idx_' . $this->prefix . 'event_reminder_parent', $this->prefix . 'notify_event_reminder', 'eventId' );
		$this->createIndex( 'idx_' . $this->prefix . 'event_reminder_user', $this->prefix . 'notify_event_reminder', 'userId' );
	}

	private function upNotification() {

		$this->createTable( $this->prefix . 'notify_notification', [
			'id' => $this->bigPrimaryKey( 20 ),
			'userId' => $this->bigInteger( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'parentId' => $this->bigInteger( 20 ),
			'parentType' => $this->string( Yii::$app->core->mediumText ),
			'title' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull()->defaultValue( 'default' ),
			'ip' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'agent' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'link' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'admin' => $this->boolean()->notNull()->defaultValue( false ),
			'adminLink' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'consumed' => $this->boolean()->notNull()->defaultValue( false ),
			'trash' => $this->boolean()->notNull()->defaultValue( false ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'content' => $this->text(),
			'data' => $this->text()
		], $this->options );

		// Index for columns site, creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'notification_user', $this->prefix . 'notify_notification', 'userId' );
		$this->createIndex( 'idx_' . $this->prefix . 'notification_creator', $this->prefix . 'notify_notification', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'notification_modifier', $this->prefix . 'notify_notification', 'modifiedBy' );
	}

	private function upActivity() {

		$this->createTable( $this->prefix . 'notify_activity', [
			'id' => $this->bigPrimaryKey( 20 ),
			'userId' => $this->bigInteger( 20 )->notNull(),
			'parentId' => $this->bigInteger( 20 ),
			'parentType' => $this->string( Yii::$app->core->mediumText ),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull()->defaultValue( 'default' ),
			'ip' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'agent' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'content' => $this->text(),
			'data' => $this->text()
		], $this->options );

		// Index for columns user
		$this->createIndex( 'idx_' . $this->prefix . 'activity_user', $this->prefix . 'notify_activity', 'userId' );
	}

	private function generateForeignKeys() {

		// Event
		$this->addForeignKey( 'fk_' . $this->prefix . 'event_site', $this->prefix . 'notify_event', 'siteId', $this->prefix . 'core_site', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'event_creator', $this->prefix . 'notify_event', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'event_modifier', $this->prefix . 'notify_event', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Event Participant
		$this->addForeignKey( 'fk_' . $this->prefix . 'event_participant_parent', $this->prefix . 'notify_event_participant', 'eventId', $this->prefix . 'notify_event', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'event_participant_user', $this->prefix . 'notify_event_participant', 'userId', $this->prefix . 'core_user', 'id', 'CASCADE' );

		// Event Reminder
		$this->addForeignKey( 'fk_' . $this->prefix . 'event_reminder_parent', $this->prefix . 'notify_event_reminder', 'eventId', $this->prefix . 'notify_event', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'event_reminder_user', $this->prefix . 'notify_event_reminder', 'userId', $this->prefix . 'core_user', 'id', 'CASCADE' );

		// Notification
		$this->addForeignKey( 'fk_' . $this->prefix . 'notification_user', $this->prefix . 'notify_notification', 'userId', $this->prefix . 'core_user', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'notification_creator', $this->prefix . 'notify_notification', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'notification_modifier', $this->prefix . 'notify_notification', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Activity
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

		$this->dropTable( $this->prefix . 'notify_activity' );
	}

	private function dropForeignKeys() {

		// Event
		$this->dropForeignKey( 'fk_' . $this->prefix . 'event_site', $this->prefix . 'notify_event' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'event_creator', $this->prefix . 'notify_event' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'event_modifier', $this->prefix . 'notify_event' );

		// Event Participant
		$this->dropForeignKey( 'fk_' . $this->prefix . 'event_participant_parent', $this->prefix . 'notify_event_participant' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'event_participant_user', $this->prefix . 'notify_event_participant' );

		// Event Reminder
		$this->dropForeignKey( 'fk_' . $this->prefix . 'event_reminder_parent', $this->prefix . 'notify_event_reminder' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'event_reminder_user', $this->prefix . 'notify_event_reminder' );

		// Notification
		$this->dropForeignKey( 'fk_' . $this->prefix . 'notification_user', $this->prefix . 'notify_notification' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'notification_creator', $this->prefix . 'notify_notification' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'notification_modifier', $this->prefix . 'notify_notification' );

		// Activity
		$this->dropForeignKey( 'fk_' . $this->prefix . 'activity_user', $this->prefix . 'notify_activity' );
	}
}
