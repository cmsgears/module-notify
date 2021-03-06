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
 * The newsletter index migration inserts the recommended indexes for better performance. It
 * also list down other possible index commented out. These indexes can be created using
 * project based migration script.
 *
 * @since 1.0.0
 */
class m160628_015513_notify_index extends Migration {

	// Public Variables

	// Private Variables

	private $prefix;

	public function init() {

		// Table prefix
		$this->prefix = Yii::$app->migration->cmgPrefix;
	}

	public function up() {

		$this->upPrimary();
	}

	private function upPrimary() {

		// Event
		$this->createIndex( 'idx_' . $this->prefix . 'event_name', $this->prefix . 'notify_event', 'name' );
		$this->createIndex( 'idx_' . $this->prefix . 'event_slug', $this->prefix . 'notify_event', 'slug' );
		$this->createIndex( 'idx_' . $this->prefix . 'event_type', $this->prefix . 'notify_event', 'type' );
		//$this->createIndex( 'idx_' . $this->prefix . 'event_icon', $this->prefix . 'notify_event', 'icon' );
		$this->createIndex( 'idx_' . $this->prefix . 'event_type_p', $this->prefix . 'notify_event', 'parentType' );

		// Notification
		//$this->createIndex( 'idx_' . $this->prefix . 'notification_title', $this->prefix . 'notify_notification', 'title' );
		$this->createIndex( 'idx_' . $this->prefix . 'notification_type', $this->prefix . 'notify_notification', 'type' );
		//$this->createIndex( 'idx_' . $this->prefix . 'notification_ip', $this->prefix . 'notify_notification', 'ip' );
		//$this->createIndex( 'idx_' . $this->prefix . 'notification_agent', $this->prefix . 'notify_notification', 'agent' );
		$this->createIndex( 'idx_' . $this->prefix . 'notification_type_p', $this->prefix . 'notify_notification', 'parentType' );

		// Announcement
		//$this->createIndex( 'idx_' . $this->prefix . 'announcement_title', $this->prefix . 'notify_announcement', 'title' );
		$this->createIndex( 'idx_' . $this->prefix . 'announcement_type', $this->prefix . 'notify_announcement', 'type' );
		//$this->createIndex( 'idx_' . $this->prefix . 'announcement_ip', $this->prefix . 'notify_announcement', 'ip' );
		//$this->createIndex( 'idx_' . $this->prefix . 'announcement_agent', $this->prefix . 'notify_announcement', 'agent' );
		$this->createIndex( 'idx_' . $this->prefix . 'announcement_type_p', $this->prefix . 'notify_announcement', 'parentType' );

		// Activity
		$this->createIndex( 'idx_' . $this->prefix . 'activity_title', $this->prefix . 'notify_activity', 'title' );
		$this->createIndex( 'idx_' . $this->prefix . 'activity_type', $this->prefix . 'notify_activity', 'type' );
		//$this->createIndex( 'idx_' . $this->prefix . 'activity_ip', $this->prefix . 'notify_activity', 'ip' );
		//$this->createIndex( 'idx_' . $this->prefix . 'activity_agent', $this->prefix . 'notify_activity', 'agent' );
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
		//$this->dropIndex( 'idx_' . $this->prefix . 'event_icon', $this->prefix . 'notify_event' );
		$this->dropIndex( 'idx_' . $this->prefix . 'event_type_p', $this->prefix . 'notify_event' );

		// Notification
		//$this->dropIndex( 'idx_' . $this->prefix . 'notification_title', $this->prefix . 'notify_notification' );
		$this->dropIndex( 'idx_' . $this->prefix . 'notification_type', $this->prefix . 'notify_notification' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'notification_ip', $this->prefix . 'notify_notification' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'notification_agent', $this->prefix . 'notify_notification' );
		$this->dropIndex( 'idx_' . $this->prefix . 'notification_type_p', $this->prefix . 'notify_notification' );

		// Announcement
		//$this->dropIndex( 'idx_' . $this->prefix . 'announcement_title', $this->prefix . 'notify_announcement' );
		$this->dropIndex( 'idx_' . $this->prefix . 'announcement_type', $this->prefix . 'notify_announcement' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'announcement_ip', $this->prefix . 'notify_announcement' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'announcement_agent', $this->prefix . 'notify_announcement' );
		$this->dropIndex( 'idx_' . $this->prefix . 'announcement_type_p', $this->prefix . 'notify_announcement' );

		// Activity
		$this->dropIndex( 'idx_' . $this->prefix . 'activity_title', $this->prefix . 'notify_activity' );
		$this->dropIndex( 'idx_' . $this->prefix . 'activity_type', $this->prefix . 'notify_activity' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'activity_ip', $this->prefix . 'notify_activity' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'activity_agent', $this->prefix . 'notify_activity' );
		$this->dropIndex( 'idx_' . $this->prefix . 'activity_type_p', $this->prefix . 'notify_activity' );
	}

}
