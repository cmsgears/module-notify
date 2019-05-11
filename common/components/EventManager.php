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
use cmsgears\core\common\config\CoreProperties;
use cmsgears\notify\common\config\NotifyGlobal;

use cmsgears\core\common\utilities\DateUtil;

/**
 * EventManager triggers notifications, reminders and logs activities.
 *
 * @since 1.0.0
 */
class EventManager extends \cmsgears\core\common\components\EventManager {

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
	public function getAdminStats( $type = null ) {

		// Results
		$stats = parent::getAdminStats( $type );

		if( empty( $type ) || $type == 'notification' ) {

			$stats[ 'notifications' ]		= $this->notificationService->getRecent( 5, [ 'conditions' => [ 'admin' => true ] ] );
			$stats[ 'notificationCount' ]	= $this->notificationService->getCount( false, true );
		}

		if( empty( $type ) || $type == 'reminder' ) {

			$stats[ 'reminders' ]		= $this->reminderService->getRecent( 5, [ 'conditions' => [ 'admin' => true ] ] );
			$stats[ 'reminderCount' ]	= $this->reminderService->getCount( false, true );
		}

		if( empty( $type ) || $type == 'activity' ) {

			$stats[ 'activities' ]		= $this->activityService->getRecent( 5, [ 'conditions' => [ 'admin' => true ] ] );
			$stats[ 'activityCount' ]	= count( $stats[ 'activities' ] );

			//$stats[ 'activityCount' ]	= $this->activityService->getCount( false, true );
		}

		return $stats;
	}

	/**
	 * @inheritdoc
	 */
	public function getUserStats( $type = null ) {

		$user = Yii::$app->core->getUser();
		$site = Yii::$app->core->site;

		// Results
		$stats = parent::getUserStats( $type );

		if( empty( $type ) || $type == 'notification' ) {

			$stats[ 'notifications' ]		= $this->notificationService->getRecent( 5, [ 'conditions' => [ 'admin' => false, 'userId' => $user->id ] ] );
			$stats[ 'notificationCount' ]	= $this->notificationService->getUserCount( $user->id, false, false );
		}

		if( empty( $type ) || $type == 'reminder' ) {

			$stats[ 'reminders' ]		= $this->reminderService->getRecent( 5, [ 'conditions' => [ 'admin' => false, 'userId' => $user->id ] ] );
			$stats[ 'reminderCount' ]	= $this->reminderService->getUserCount( $user->id, false, false );
		}

		// Show only default activities
		if( empty( $type ) || $type == 'activity' ) {

			$stats[ 'activities' ]		= $this->activityService->getRecent( 5, [ 'conditions' => [ 'admin' => false, 'userId' => $user->id, 'type' => CoreGlobal::TYPE_DEFAULT ] ] );
			$stats[ 'activityCount' ]	= count( $stats[ 'activities' ] );

			//$stats[ 'activityCount' ]	= $this->activityService->getUserCount( $user->id, false, false );
		}

		if( empty( $type ) || $type == 'announcement' ) {

			$stats[ 'announcements' ]		= $this->announcementService->getRecentByParent( $site->id, CoreGlobal::TYPE_SITE );
			$stats[ 'announcementCount' ]	= count( $stats[ 'announcements' ] );
		}

		return $stats;
	}

	public function getModelStats( $parentId, $parentType, $type = null ) {

		$site = Yii::$app->core->site;

		// Results
		$stats = parent::getModelStats( $parentId, $parentType, $type );

		if( empty( $type ) || $type == 'notification' ) {

			$stats[ 'notifications' ]		= $this->notificationService->getRecent( 5, [ 'conditions' => [ 'admin' => false, 'parentId' => $parentId, 'parentType' => $parentType ] ] );
			$stats[ 'notificationCount' ]	= $this->notificationService->getCountByParent( $parentId, $parentType, false, false );
		}

		if( empty( $type ) || $type == 'reminder' ) {

			$stats[ 'reminders' ]		= $this->reminderService->getRecent( 5, [ 'conditions' => [ 'admin' => false, 'parentId' => $parentId, 'parentType' => $parentType ] ] );
			$stats[ 'reminderCount' ]	= $this->reminderService->getCountByParent( $parentId, $parentType, false, false );
		}

		// Show only default activities
		if( empty( $type ) || $type == 'activity' ) {

			$stats[ 'activities' ]		= $this->activityService->getRecent( 5, [ 'conditions' => [ 'admin' => false, 'parentId' => $parentId, 'parentType' => $parentType, 'type' => CoreGlobal::TYPE_DEFAULT ] ] );
			$stats[ 'activityCount' ]	= count( $stats[ 'activities' ] );

			//$stats[ 'activityCount' ]	= $this->activityService->getCountByParent( $parentId, $parentType, false, false );
		}

		if( empty( $type ) || $type == 'announcement' ) {

			$stats[ 'announcements' ]		= $this->announcementService->getRecentByParent( $parentId, $parentType );
			$stats[ 'announcementCount' ]	= count( $stats[ 'announcements' ] );
		}

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

		$admin	= isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;
		$users	= isset( $config[ 'users' ] ) ? $config[ 'users' ] : [];
		$direct	= isset( $config[ 'direct' ] ) ? $config[ 'direct' ] : false;

		$coreProperties = CoreProperties::getInstance();

		// Return in case notifications are disabled at system level.
		if( !Yii::$app->core->isNotifications() ) {

			return false;
		}

		// Generate Message

		// Site Template
		$template = $this->templateService->getBySlugType( $slug, NotifyGlobal::TYPE_NOTIFICATION );

		// Do nothing if template not found or disabled
		if( empty( $template ) || !$template->isActive() ) {

			return;
		}

		// Trigger Notification

		$templateConfig = $template->getDataMeta( CoreGlobal::DATA_CONFIG );

		$notification = $this->notificationService->getModelObject();

		$notification->consumed	= false;
		$notification->trash	= false;

		$notification->type = $config[ 'type' ] ?? CoreGlobal::TYPE_DEFAULT;

		if( isset( $config[ 'createdBy' ] ) ) {

			$notification->createdBy = $config[ 'createdBy' ];
		}

		if( isset( $config[ 'parentId' ] ) ) {

			$notification->parentId = $config[ 'parentId' ];
		}

		if( isset( $config[ 'parentType' ] ) ) {

			$notification->parentType = $config[ 'parentType' ];
		}

		if( !empty( $template->message ) ) {

			$notification->title = Yii::$app->templateManager->renderTitle( $template, $data, $config );
		}

		if( empty( $notification->title ) ) {

			if( isset( $config[ 'title' ] ) ) {

				$notification->title = $config[ 'title' ];
			}
			else {

				$notification->title = $template->name;
			}
		}

		$nconfig = $config;

		unset( $nconfig[ 'link' ] ); // remove frontend link from notification content
		unset( $nconfig[ 'adminLink' ] ); // remove admin link from notification content

		$message = Yii::$app->templateManager->renderMessage( $template, $data, $nconfig );

		$notification->content = $message;

		// Trigger for Admin
		if( $templateConfig->admin && $admin ) {

			$notification->admin = true;

			if( isset( $config[ 'adminLink' ] ) ) {

				$notification->adminLink = $config[ 'adminLink' ];

				$config[ 'adminLink' ] = $coreProperties->getAdminUrl() . $config[ 'adminLink' ];
			}

			$nconfig = $config;

			unset( $nconfig[ 'link' ] ); // remove frontend link from admin notification email

			$message = Yii::$app->templateManager->renderMessage( $template, $data, $nconfig );

			// Create Notification
			$this->notificationService->create( $notification, $config );

			if( $templateConfig->adminEmail ) {

				// Trigger Mail
				Yii::$app->notifyMailer->sendAdminMail( $message, $template, $data );
			}
		}

		if( isset( $config[ 'link' ] ) ) {

			$notification->link = $config[ 'link' ];

			$config[ 'link' ] = $coreProperties->getSiteUrl() . $config[ 'link' ];
		}

		$nconfig = $config;

		unset( $nconfig[ 'adminLink' ] ); // remove admin link from frontend notification email

		$message = Yii::$app->templateManager->renderMessage( $template, $data, $nconfig );

		// Trigger for Users
		if( $templateConfig->user && count( $users ) > 0 ) {

			foreach( $users as $userId ) {

				$userNotification = $this->notificationService->getModelObject();

				$userNotification->copyForUpdateFrom( $notification, [ 'createdBy', 'parentId', 'parentType', 'title', 'description', 'type', 'consumed', 'trash', 'link', 'content', 'data' ] );

				$userNotification->userId	= $userId;
				$userNotification->admin	= false;

				// Create Notification
				$this->notificationService->create( $userNotification, $config );

				if( $templateConfig->userEmail ) {

					// Trigger Mail
					Yii::$app->notifyMailer->sendUserMail( $message, $this->userService->getById( $userId ), $template, $data );
				}
			}
		}

		// Trigger for Model
		if( $templateConfig->direct && $direct ) {

			$nconfig = $config;

			$modelNotification = $this->notificationService->getModelObject();

			$modelNotification->copyForUpdateFrom( $notification, [ 'createdBy', 'parentId', 'parentType', 'title', 'description', 'type', 'consumed', 'trash', 'link', 'content', 'data' ] );

			// Create Notification
			$this->notificationService->create( $modelNotification, $config );

			// Detect Email
			$model		= $data[ 'model' ];
			$service	= $data[ 'service' ];
			$email		= method_exists( $service, 'getEmail' ) ? $service->getEmail : ( isset( $model->email ) ? $model->email : null );

			if( $templateConfig->directEmail && isset( $email ) ) {

				// Trigger Mail
				Yii::$app->notifyMailer->sendDirectMail( $message, $config[ 'email' ], $template, $data );
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

		$user =	Yii::$app->core->getUser();

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

		$gridData[ 'content' ]	= isset( $templateConfig->storeContent ) && $model->hasAttribute( 'content' ) ? $model->content : null;
		$gridData[ 'data' ]		= isset( $templateConfig->storeData ) && $model->hasAttribute( 'data' ) ? $model->data : null;
		$gridData[ 'cache' ]	= isset( $templateConfig->storeCache ) && $model->hasAttribute( 'cache' ) ? $model->cache : null;

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

		$user =	Yii::$app->core->getUser();

		$user->lastActivityAt = DateUtil::getDateTime();

		$user->update();
	}

}
