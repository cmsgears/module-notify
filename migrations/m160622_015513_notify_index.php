<?php

class m160622_015513_notify_index extends \yii\db\Migration {

	// Public Variables

	// Private Variables

	private $prefix;

	public function init() {

		// Table prefix
		$this->prefix		= Yii::$app->migration->cmgPrefix;
	}

	public function up() {

		$this->upPrimary();
	}

	private function upPrimary() {

		// Event
		$this->createIndex( 'idx_' . $this->prefix . 'event_name', $this->prefix . 'notify_event', 'name' );
		$this->createIndex( 'idx_' . $this->prefix . 'event_slug', $this->prefix . 'notify_event', 'slug' );
		$this->createIndex( 'idx_' . $this->prefix . 'event_type', $this->prefix . 'notify_event', 'type' );
		$this->createIndex( 'idx_' . $this->prefix . 'event_icon', $this->prefix . 'notify_event', 'icon' );
		$this->createIndex( 'idx_' . $this->prefix . 'event_type_p', $this->prefix . 'notify_event', 'parentType' );

		// Notification
		$this->createIndex( 'idx_' . $this->prefix . 'notification_title', $this->prefix . 'notify_notification', 'title' );
		$this->createIndex( 'idx_' . $this->prefix . 'notification_type', $this->prefix . 'notify_notification', 'type' );
		$this->createIndex( 'idx_' . $this->prefix . 'notification_ip', $this->prefix . 'notify_notification', 'ip' );
		$this->createIndex( 'idx_' . $this->prefix . 'notification_agent', $this->prefix . 'notify_notification', 'agent' );
		$this->createIndex( 'idx_' . $this->prefix . 'notification_type_p', $this->prefix . 'notify_notification', 'parentType' );

		// Activity
		$this->createIndex( 'idx_' . $this->prefix . 'activity_type', $this->prefix . 'notify_activity', 'type' );
		$this->createIndex( 'idx_' . $this->prefix . 'activity_ip', $this->prefix . 'notify_activity', 'ip' );
		$this->createIndex( 'idx_' . $this->prefix . 'activity_agent', $this->prefix . 'notify_activity', 'agent' );
		$this->createIndex( 'idx_' . $this->prefix . 'activity_type_p', $this->prefix . 'notify_activity', 'parentType' );
	}

	public function down() {

		$this->downPrimary();
	}

	private function downPrimary() {

		// Event
		$this->dropIndex( 'idx_' . $this->prefix . 'event_name', $this->prefix . 'notify_event' );
		$this->dropIndex( 'idx_' . $this->prefix . 'event_slug', $this->prefix . 'notify_event' );
		$this->dropIndex( 'idx_' . $this->prefix . 'event_type', $this->prefix . 'notify_event' );
		$this->dropIndex( 'idx_' . $this->prefix . 'event_icon', $this->prefix . 'notify_event' );
		$this->dropIndex( 'idx_' . $this->prefix . 'event_type_p', $this->prefix . 'notify_event' );

		// Notification
		$this->dropIndex( 'idx_' . $this->prefix . 'notification_title', $this->prefix . 'notify_notification' );
		$this->dropIndex( 'idx_' . $this->prefix . 'notification_type', $this->prefix . 'notify_notification' );
		$this->dropIndex( 'idx_' . $this->prefix . 'notification_ip', $this->prefix . 'notify_notification' );
		$this->dropIndex( 'idx_' . $this->prefix . 'notification_agent', $this->prefix . 'notify_notification' );
		$this->dropIndex( 'idx_' . $this->prefix . 'notification_type_p', $this->prefix . 'notify_notification' );

		// Activity
		$this->dropIndex( 'idx_' . $this->prefix . 'activity_type', $this->prefix . 'notify_activity' );
		$this->dropIndex( 'idx_' . $this->prefix . 'activity_ip', $this->prefix . 'notify_activity' );
		$this->dropIndex( 'idx_' . $this->prefix . 'activity_agent', $this->prefix . 'notify_activity' );
		$this->dropIndex( 'idx_' . $this->prefix . 'activity_type_p', $this->prefix . 'notify_activity' );
	}
}
