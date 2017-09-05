<?php
namespace cmsgears\notify\common\components;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\notify\common\config\NotifyGlobal;

use cmsgears\notify\common\models\entities\Notification;

class EventManager extends \cmsgears\core\common\components\EventManager {

	// Variables ---------------------------------------------------

	// Global -----------------

	// Public -----------------

	public $email	= true;	// Check whether emails are enabled for notifications.

	// Protected --------------

	protected $userService;
	protected $notificationService;
	protected $reminderService;

	protected $templateService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->userService				= Yii::$app->factory->get( 'userService' );
		$this->notificationService		= Yii::$app->factory->get( 'notificationService' );
		$this->reminderService			= Yii::$app->factory->get( 'reminderService' );

		$this->templateService			= Yii::$app->factory->get( 'templateService' );
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// EventManager --------------------------

	// Stats Collection -------

	public function getAdminStats() {

		// Query
		$notifications		= $this->notificationService->getRecent( 5, [ 'conditions' => [ 'admin' => true ] ] );
		$newNotifications	= $this->notificationService->getCount( false, true );

		$reminders			= $this->reminderService->getRecent( 5, [ 'conditions' => [ 'admin' => true ] ] );
		$newReminders		= $this->reminderService->getCount( false, true );

		// Results
		$stats							= parent::getAdminStats();
		$stats[ 'notifications' ]		= $notifications;
		$stats[ 'notificationCount' ]	= $newNotifications;
		$stats[ 'reminders' ]			= $reminders;
		$stats[ 'reminderCount' ]		= $newReminders;

		return $stats;
	}

	public function getUserStats() {

		// Query
		$user			= Yii::$app->user->getIdentity();
		$notifications	= $this->notificationService->getRecent( 5, [ 'conditions' => [ 'admin' => false, 'userId' => $user->id ] ] );
		$new			= $this->notificationService->getUserCount( $user->id, false, false );

		// Results
		$stats							= parent::getAdminStats();
		$stats[ 'notifications' ]		= $notifications;
		$stats[ 'notificationCount' ]	= $new;

		return $stats;
	}

	// Notification Trigger ---

	/**
	 * It trigger nitification and also send mail based on the configuration.
	 *
	 * * Generates notification message using given template slug, models and config. Template manager will be used to generate this message.
	 *
	 * * Load the template config from it's data attribute.
	 *
	 * * Configure notification attributes i.e. parentId, parentType, link and title.
	 *
	 * * Trigger notification for admin if template config for admin is set and also trigger mail to admin if required.
	 *
	 * * Trigger notification for multiple users and also trigger user mail if required.
	 *
	 * * Trgger notification for model in case admin or user are turned off. The provided email will be used to trigger mail.
	 */
	public function triggerNotification( $templateSlug, $models, $config = [] ) {

		// Return in case notifications are disabled at system level.
		if( !Yii::$app->core->isNotifications() ) {

			return false;
		}

		// Generate Message

		$template	= $this->templateService->getBySlugType( $templateSlug, NotifyGlobal::TYPE_NOTIFICATION );
		$message	= Yii::$app->templateManager->renderMessage( $template, $models, $config );

		// Trigger Notification

		$templateConfig			= $template->getDataMeta( CoreGlobal::DATA_CONFIG );

		$notification			= new Notification();
		$notification->consumed	= false;
		$notification->trash	= false;
		$notification->content	= $message;

		if( isset( $config[ 'createdBy' ] ) ) {

			$notification->createdBy	= $config[ 'createdBy' ];
		}

		if( isset( $config[ 'parentId' ] ) ) {

			$notification->parentId = $config[ 'parentId' ];
		}

		if( isset( $config[ 'parentType' ] ) ) {

			$notification->parentType = $config[ 'parentType' ];
		}

		if( isset( $config[ 'link' ] ) ) {

			$notification->link = $config[ 'link' ];
		}

		if( isset( $config[ 'title' ] ) ) {

			$notification->title = $config[ 'title' ];
		}
		else {

			$notification->title = $template->name;
		}

		// Trigger for Admin
		if( $templateConfig->admin ) {

			$notification->admin	= true;

			if( isset( $config[ 'adminLink' ] ) ) {

				$notification->adminLink = $config[ 'adminLink' ];
			}

			// Create Notification
			$this->notificationService->create( $notification );

			if( $templateConfig->adminEmail ) {

				// Trigger Mail
				Yii::$app->notifyMailer->sendAdminMail( $message );
			}
		}

		// Trigger for Users
		if( $templateConfig->user ) {

			$users 	= $config[ 'users' ];

			foreach ( $users as $userId ) {

				$userNotification			= new Notification();

				$userNotification->copyForUpdateFrom( $notification, [ 'parentId', 'parentType', 'title', 'consumed', 'trash', 'link', 'content', 'createdBy' ] );

				$userNotification->userId	= $userId;
				$userNotification->admin	= false;

				// Create Notification
				$this->notificationService->create( $userNotification );

				if( $templateConfig->userEmail ) {

					// Trigger Mail
					Yii::$app->notifyMailer->sendUserMail( $message, $this->userService->getById( $userId ) );
				}
			}
		}

		// Trigger for Model
		if( !$templateConfig->admin && !$templateConfig->user ) {

			// Create Notification
			$this->notificationService->create( $notification );

			if( isset( $config[ 'email' ] ) ) {

				// Trigger Mail
				Yii::$app->notifyMailer->sendDirectMail( $message, $config[ 'email' ] );
			}
		}
	}

	// Reminder Trigger -------

	public function triggerReminder( $template, $message, $config = [] ) {

		// Trigger reminders using given template, message and config
	}

	// Activity Logger --------

	public function logActivity( $template, $message, $config = [] ) {

		// Trigger notifications using given template, message and config
	}
        
        // Delete Notifications
        public function deleteNotificationsByUserId( $userId ) {
            
             return $this->notificationService->deleteNotificationsByUserId( $userId );
        } 
}
