<?php
namespace cmsgears\notify\common\components;

// Yii Imports
use \Yii;
use yii\di\Container;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

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
		$this->registerEntityServices();

		// Init services
		$this->initEntityServices();
	}

	public function registerEntityServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cmsgears\notify\common\services\interfaces\entities\INotificationService', 'cmsgears\notify\common\services\entities\NotificationService' );
		$factory->set( 'cmsgears\notify\common\services\interfaces\entities\IEventService', 'cmsgears\notify\common\services\entities\EventService' );
		$factory->set( 'cmsgears\notify\common\services\interfaces\entities\IReminderService', 'cmsgears\notify\common\services\entities\ReminderService' );
	}

	public function initEntityServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'notificationService', 'cmsgears\notify\common\services\entities\NotificationService' );
		$factory->set( 'eventService', 'cmsgears\notify\common\services\entities\EventService' );
		$factory->set( 'reminderService', 'cmsgears\notify\common\services\entities\ReminderService' );
	}
}
