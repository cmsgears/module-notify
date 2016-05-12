<?php
namespace cmsgears\notify\common\components;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\services\entities\UserService;
use cmsgears\notify\common\models\mappers\ModelNotification;

use cmsgears\notify\common\services\mappers\ModelNotificationService;

class NotificationManager extends \yii\base\Component {

	// Variables ---------------------------------------------------

	public $email	= true;	// Check whether emails are enabled for notifications.

	// Init --------------------------------------------------------

    public function init() {

        parent::init();
    }

	// NotificationManager -----------------------------------------

	public function triggerNotification( $template, $message, $models, $config = [] ) {

		$templateConfig		= $template->getDataAttribute( CoreGlobal::DATA_CONFIG );

		$notification		= new ModelNotification();

		$notification->status	= ModelNotification::STATUS_NEW;
		$notification->content	= $message;

		if( isset( $config[ 'parentId' ] ) ) {

			$notification->parentId = $config[ 'parentId' ];
		}

		if( isset( $config[ 'parentType' ] ) ) {

			$notification->parentType = $config[ 'parentType' ];
		}

		if( isset( $config[ 'follow' ] ) ) {

			$notification->follow = $config[ 'follow' ];
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

			if( isset( $config[ 'adminFollow' ] ) ) {

				$notification->adminFollow = $config[ 'adminFollow' ];
			}

			// Create Notification
			ModelNotificationService::create( $notification );

			if( $templateConfig->adminEmail ) {

				// Trigger Mail
				Yii::$app->cmgNotifyMailer->sendAdminMail( $message );
			}
		}

		// Trigger for Users
		if( $templateConfig->user ) {

			$users 	= $config[ 'users' ];

			foreach ( $users as $userId ) {

				$userNotification			= new ModelNotification();

				$userNotification->copyForUpdateFrom( $notification, [ 'parentId', 'parentType', 'title', 'status', 'follow', 'content' ] );

				$userNotification->userId	= $userId;
				$userNotification->admin	= false;

				// Create Notification
				ModelNotificationService::create( $userNotification );

				if( $templateConfig->userEmail ) {

					// Trigger Mail
					Yii::$app->cmgNotifyMailer->sendUserMail( $message, UserService::findById( $userId ) );
				}
			}
		}

		// Trigger for Model
		if( !$templateConfig->admin && !$templateConfig->user ) {

			// Create Notification
			ModelNotificationService::create( $notification );

			if( isset( $config[ 'email' ] ) ) {

				// Trigger Mail
				Yii::$app->cmgNotifyMailer->sendDirectMail( $message, $config[ 'email' ] );
			}
		}
	}
}

?>