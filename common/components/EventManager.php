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

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\notify\common\config\NotifyGlobal;

use cmsgears\core\common\components\EventManager as BaseEventManager;

use cmsgears\core\common\utilities\DateUtil;

/**
 * EventManager triggers notifications, reminders and logs activities.
 *
 * @since 1.0.0
 */
class EventManager extends BaseEventManager {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	public $email = true; // Check whether emails are enabled

	// Protected --------------

	protected $userService;
	protected $templateService;

	protected $notificationService;
	protected $reminderService;
	protected $activityService;
	protected $announcementService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->userService			= Yii::$app->factory->get( 'userService' );
		$this->templateService		= Yii::$app->factory->get( 'templateService' );

		$this->notificationService	= Yii::$app->factory->get( 'notificationService' );
		$this->reminderService		= Yii::$app->factory->get( 'reminderService' );
		$this->activityService		= Yii::$app->factory->get( 'activityService' );
		$this->announcementService	= Yii::$app->factory->get( 'announcementService' );
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// EventManager --------------------------

	// Stats Collection -------

	/**
	 * @inheritdoc
	 */
	public function getAdminStats() {

		// Query
		$notifications		= $this->notificationService->getRecent( 5, [ 'conditions' => [ 'admin' => true ] ] );
		$newNotifications	= $this->notificationService->getCount( false, true );

		$reminders		= $this->reminderService->getRecent( 5, [ 'conditions' => [ 'admin' => true ] ] );
		$newReminders	= $this->reminderService->getCount( false, true );

		$activities		= $this->activityService->getRecent( 5, [ 'conditions' => [ 'admin' => true ] ] );
		$newActivities	= $this->activityService->getCount( false, true );

		// Results
		$stats	= parent::getAdminStats();

		$stats[ 'notifications' ]		= $notifications;
		$stats[ 'notificationCount' ]	= $newNotifications;

		$stats[ 'reminders' ]		= $reminders;
		$stats[ 'reminderCount' ]	= $newReminders;

		$stats[ 'activities' ]		= $activities;
		$stats[ 'activityCount' ]	= $newActivities;

		return $stats;
	}

	/**
	 * @inheritdoc
	 */
	public function getUserStats() {

		$user = Yii::$app->user->getIdentity();
		$site = Yii::$app->core->site;

		$notifications		= $this->notificationService->getRecent( 5, [ 'conditions' => [ 'admin' => false, 'userId' => $user->id ] ] );
		$newNotifications	= $this->notificationService->getUserCount( $user->id, false, false );

		$reminders		= $this->reminderService->getRecent( 5, [ 'conditions' => [ 'admin' => false, 'userId' => $user->id ] ] );
		$newReminders	= $this->reminderService->getUserCount( $user->id, false, false );

		$activities		= $this->activityService->getRecent( 5, [ 'conditions' => [ 'admin' => true ] ] );
		$newActivities	= $this->activityService->getCount( false, true );

		$announcements	= $this->announcementService->getRecentByParent( $site->id, CoreGlobal::TYPE_SITE );

		// Results
		$stats	= parent::getUserStats();

		$stats[ 'notifications' ]		= $notifications;
		$stats[ 'notificationCount' ]	= $newNotifications;

		$stats[ 'reminders' ]		= $reminders;
		$stats[ 'reminderCount' ]	= $newReminders;

		$stats[ 'activities' ]		= $activities;
		$stats[ 'activityCount' ]	= $newActivities;

		$stats[ 'announcements' ] = $announcements;

		return $stats;
	}

	// Notification Trigger ---

	/**
	 * It trigger notification and also send mail based on the configuration.
	 *
	 * * Generates notification message using given template slug, models and config. Template manager will be used to generate this message.
	 *
	 * * Load the template configuration from it's data attribute.
	 *
	 * * Configure notification attributes i.e. parentId, parentType, link and title.
	 *
	 * * Trigger notification for admin if template configuration for admin is set and also trigger mail to admin if required.
	 *
	 * * Trigger notification for multiple users and also trigger user mail if required.
	 *
	 * * Trigger notification for model in case admin or user are turned off. The provided email will be used to trigger mail.
	 *
	 * @param string $slug - template slug
	 * @param array $data - data passed to the template engine for generating the message
	 * @param array $config - configuration to generate the notification
	 * @return boolean
	 */
	public function triggerNotification( $slug, $data, $config = [] ) {

		// Return in case notifications are disabled at system level.
		if( !Yii::$app->core->isNotifications() ) {

			return false;
		}

		// Generate Message

		$template = $this->templateService->getBySlugType( $slug, NotifyGlobal::TYPE_NOTIFICATION );

		// Do nothing if template not found or disabled
		if( empty( $template ) || !$template->isActive() ) {

			return;
		}

		$message = Yii::$app->templateManager->renderMessage( $template, $data, $config );

		// Trigger Notification

		$templateConfig = $template->getDataMeta( CoreGlobal::DATA_CONFIG );

		$notification = $this->notificationService->getModelObject();

		$notification->consumed	= false;
		$notification->trash	= false;
		$notification->content	= $message;

		$notification->type = $config['type'] ?? 'default';

		if( isset( $config[ 'createdBy' ] ) ) {

			$notification->createdBy = $config[ 'createdBy' ];
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
		if( $templateConfig->admin && $config[ 'admin' ] ) {

			$notification->admin = true;

			if( isset( $config[ 'adminLink' ] ) ) {

				$notification->adminLink = $config[ 'adminLink' ];
			}

			// Create Notification
			$this->notificationService->create( $notification, $config );

			if( $templateConfig->adminEmail ) {

				// Trigger Mail
				Yii::$app->notifyMailer->sendAdminMail( $message );
			}
		}

		// Trigger for Users
		if( $templateConfig->user && count( $config[ 'users' ] ) > 0 ) {

			$users = $config[ 'users' ];

			foreach( $users as $userId ) {

				$userNotification = $this->notificationService->getModelObject();

				$userNotification->copyForUpdateFrom( $notification, [ 'createdBy', 'parentId', 'parentType', 'title', 'description', 'type', 'consumed', 'trash', 'link', 'content', 'data' ] );

				$userNotification->userId	= $userId;
				$userNotification->admin	= false;

				// Create Notification
				$this->notificationService->create( $userNotification, $config );

				if( $templateConfig->userEmail ) {

					// Trigger Mail
					Yii::$app->notifyMailer->sendUserMail( $message, $this->userService->getById( $userId ) );
				}
			}
		}

		// Trigger for Model
		if( $templateConfig->detectEmail && $config[ 'direct' ] ) {

			$modelNotification = $this->notificationService->getModelObject();

			$modelNotification->copyForUpdateFrom( $notification, [ 'createdBy', 'parentId', 'parentType', 'title', 'description', 'type', 'consumed', 'trash', 'link', 'content', 'data' ] );

			// Create Notification
			$this->notificationService->create( $modelNotification, $config );

			// Detect Email
			$model		= $data[ 'model' ];
			$service	= $data[ 'service' ];
			$email		= method_exists( $service, 'getEmail' ) ? $service->getEmail : ( isset( $model->email ) ? $model->email : null );

			if( isset( $email ) ) {

				// Trigger Mail
				Yii::$app->notifyMailer->sendDirectMail( $message, $config[ 'email' ] );
			}
		}

		return true;
	}

	public function deleteNotificationsByUserId( $userId, $config = [] ) {

		$this->notificationService->deleteByUserId( $userId, $config );
	}

	public function deleteNotificationsByParent( $parentId, $parentType, $config = [] ) {

		$this->notificationService->deleteByParent( $parentId, $parentType, $config );
	}

	// Reminder Trigger -------

	public function triggerReminder( $slug, $data, $config = [] ) {

		// Trigger reminders using given template, message and config
	}

	// Activity Logger --------

	/**
	 * @inheritdoc
	 */
	public function logCreate( $model, $service, $config = [] ) {

		$title	= isset( $model->name ) ? $model->getClassName() . ' | ' . $model->name : $model->getClassName();
		$title	= "Create - $title";
		$slug	= isset( $config[ 'slug' ] ) ? $config[ 'slug' ] : NotifyGlobal::TEMPLATE_LOG_CREATE;

		$this->logActivity( $model, $service, $slug, $title, $config );
	}

	/**
	 * @inheritdoc
	 */
	public function logUpdate( $model, $service, $config = [] ) {

		$title	= isset( $model->name ) ? $model->getClassName() . ' | ' . $model->name : $model->getClassName();
		$title	= "Update - $title";
		$slug	= isset( $config[ 'slug' ] ) ? $config[ 'slug' ] : NotifyGlobal::TEMPLATE_LOG_UPDATE;

		$this->logActivity( $model, $service, $slug, $title, $config );
	}

	/**
	 * @inheritdoc
	 */
	public function logDelete( $model, $service, $config = [] ) {

		$title	= isset( $model->name ) ? $model->getClassName() . ' | ' . $model->name : $model->getClassName();
		$title	= "Delete - $title";
		$slug	= isset( $config[ 'slug' ] ) ? $config[ 'slug' ] : NotifyGlobal::TEMPLATE_LOG_DELETE;

		$this->logActivity( $model, $service, $slug, $title, $config );
	}

	// Activity
	private function logActivity( $model, $service, $slug, $title, $config = [] ) {

		$user =	Yii::$app->user->getIdentity();

		$config['parentId'] = $model->id;
		$config['parentType'] = $service->getParentType();
		$config['userId'] = $user->getId();
		$config['title'] = $title;

		$this->triggerActivity(
			$slug,
			[ 'model' => $model, 'service' => $service, 'user' => $user ],
			$config
		);
	}

	/**
	 * Trigger Activity using given template, message and config
	 *
	 * @param string $slug
	 * @param array $data
	 * @param array $config
	 * @return boolean
	 */
	public function triggerActivity( $slug, $data, $config = [] ) {

		// Return in case activity logging is disabled at system level.
		if( !Yii::$app->core->isActivities() ) {

			return false;
		}

		// Generate Message

		$template = $this->templateService->getBySlugType( $slug, NotifyGlobal::TYPE_ACTIVITY );

		// Do nothing if template not found or disabled
		if( empty( $template ) || !$template->isActive() ) {

			return;
		}

		$message = Yii::$app->templateManager->renderMessage( $template, $data, $config );

		// Trigger Activity

		$templateConfig = $template->getDataMeta( CoreGlobal::DATA_CONFIG );

		$model		= $data[ 'model' ];
		$gridData	= [];

		$gridData[ 'content' ]	= $templateConfig->storeContent && $model->hasAttribute( 'content' ) ? $model->content : null;
		$gridData[ 'data' ]		= $templateConfig->storeData && $model->hasAttribute( 'data' ) ? $model->data : null;
		$gridData[ 'cache' ]	= $templateConfig->storeCache && $model->hasAttribute( 'cache' ) ? $model->cache : null;

		$activity = $this->activityService->getModelObject();

		$activity->userId		= $config[ 'userId' ];
		$activity->parentId		= isset( $config[ 'parentId' ] ) ? $config[ 'parentId' ] : null;
		$activity->parentType	= isset( $config[ 'parentType' ] ) ? $config[ 'parentType' ] : null;
		$activity->admin		= isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$activity->consumed	= false;
		$activity->trash	= false;

		$activity->content			= $message;
		$activity->gridCache		= json_encode(  $gridData );
		$activity->gridCacheValid	= true;
		$activity->gridCachedAt		= DateUtil::getDateTime();

		$activity->type = "log";

		if( isset( $config[ 'title' ] ) ) {

			$activity->title = $config[ 'title' ];
		}
		else {

			$activity->title = $template->name;
		}

		// Create Activity
		$this->activityService->create( $activity, $config );

		$user =	Yii::$app->user->getIdentity();

		$user->lastActivityAt = DateUtil::getDateTime();

		$user->update();
	}

}
