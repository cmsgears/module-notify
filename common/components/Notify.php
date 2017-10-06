<?php
namespace cmsgears\notify\common\components;

// Yii Imports
use Yii;

class Notify extends \yii\base\Component {

	// Global -----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	/**
	 * Initialise the CMG Core Component.
	 */
	public function init() {

		parent::init();

		// Register application components and objects i.e. CMG and Project
		$this->registerComponents();
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// Cms -----------------------------------

	// Properties

	// Components and Objects

	public function registerComponents() {

		// Register services
		$this->registerResourceServices();
		$this->registerEntityServices();

		// Init services
		$this->initResourceServices();
		$this->initEntityServices();
	}

	public function registerResourceServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cmsgears\notify\common\services\interfaces\resources\IEventReminderService', 'cmsgears\notify\common\services\resources\EventReminderService' );
	}

	public function registerEntityServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cmsgears\notify\common\services\interfaces\entities\IActivityService', 'cmsgears\notify\common\services\entities\ActivityService' );
		$factory->set( 'cmsgears\notify\common\services\interfaces\entities\INotificationService', 'cmsgears\notify\common\services\entities\NotificationService' );
		$factory->set( 'cmsgears\notify\common\services\interfaces\entities\IEventService', 'cmsgears\notify\common\services\entities\EventService' );
	}

	public function initResourceServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'reminderService', 'cmsgears\notify\common\services\resources\EventReminderService' );
	}

	public function initEntityServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'activityService', 'cmsgears\notify\common\services\entities\ActivityService' );
		$factory->set( 'notificationService', 'cmsgears\notify\common\services\entities\NotificationService' );
		$factory->set( 'eventService', 'cmsgears\notify\common\services\entities\EventService' );
	}
}
