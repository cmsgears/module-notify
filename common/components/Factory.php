<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\notify\common\components;

// Yii Imports
use Yii;

/**
 * The Notify Factory component initialise the services available in Notify Module.
 *
 * @since 1.0.0
 */
class Factory extends \cmsgears\core\common\base\Component {

	// Global -----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Register services
		$this->registerServices();

		// Register service alias
		$this->registerServiceAlias();
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// Factory -------------------------------

	public function registerServices() {

		$this->registerResourceServices();
		$this->registerMapperServices();
	}

	public function registerServiceAlias() {

		$this->registerResourceAliases();
		$this->registerMapperAliases();
	}

	/**
	 * Registers resource services.
	 */
	public function registerResourceServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cmsgears\notify\common\services\interfaces\resources\IEventService', 'cmsgears\notify\common\services\resources\EventService' );
		$factory->set( 'cmsgears\notify\common\services\interfaces\resources\IEventReminderService', 'cmsgears\notify\common\services\resources\EventReminderService' );

		$factory->set( 'cmsgears\notify\common\services\interfaces\resources\IActivityService', 'cmsgears\notify\common\services\resources\ActivityService' );
		$factory->set( 'cmsgears\notify\common\services\interfaces\resources\IAnnouncementService', 'cmsgears\notify\common\services\resources\AnnouncementService' );
		$factory->set( 'cmsgears\notify\common\services\interfaces\resources\INotificationService', 'cmsgears\notify\common\services\resources\NotificationService' );
	}

	/**
	 * Registers mapper services.
	 */
	public function registerMapperServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cmsgears\notify\common\services\interfaces\mappers\IEventParticipantService', 'cmsgears\notify\common\services\mappers\EventParticipantService' );
	}

	/**
	 * Registers resource aliases.
	 */
	public function registerResourceAliases() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'eventService', 'cmsgears\notify\common\services\resources\EventService' );
		$factory->set( 'reminderService', 'cmsgears\notify\common\services\resources\EventReminderService' );

		$factory->set( 'activityService', 'cmsgears\notify\common\services\resources\ActivityService' );
		$factory->set( 'announcementService', 'cmsgears\notify\common\services\resources\AnnouncementService' );
		$factory->set( 'notificationService', 'cmsgears\notify\common\services\resources\NotificationService' );
	}

	/**
	 * Registers mapper aliases.
	 */
	public function registerMapperAliases() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'eventParticipantService', 'cmsgears\notify\common\services\mappers\EventParticipantService' );
	}

}
