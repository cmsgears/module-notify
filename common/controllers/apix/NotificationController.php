<?php
namespace cmsgears\notify\common\controllers\apix;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\notify\common\models\mappers\ModelNotification;

use cmsgears\notify\common\services\mappers\ModelNotificationService;

use cmsgears\core\common\utilities\AjaxUtil;

class NotificationController extends \cmsgears\core\common\controllers\Controller {

	protected $admin		= CoreGlobal::STATUS_NO;
	protected $conditions	= [];

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
                	// rbac actions
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'toggleRead' => [ 'post' ],
	                'trash' => [ 'post' ],
	                'delete' => [ 'post' ]
                ]
            ]
        ];
    }

	// NotificationController ------------

	public function actionToggleRead( $id ) {

		$notification	= ModelNotificationService::getById( $id );

		if( isset( $notification ) ) {

			$notification	= ModelNotificationService::toggleRead( $notification );

			$counts			= ModelNotificationService::getStatusCounts( $this->admin, $this->conditions );
			$data			= [ 'unread' => $counts[ ModelNotification::STATUS_NEW ], 'consumed' => $notification->isConsumed() ];

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
	    }

		// Trigger Ajax Failure
        return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
	}

	public function actionTrash( $id ) {

		$notification	= ModelNotificationService::getById( $id );

		if( isset( $notification ) ) {

			$notification	= ModelNotificationService::markTrash( $notification );

			$counts			= ModelNotificationService::getStatusCounts( $this->admin, $this->conditions );
			$data			= [ 'unread' => $counts[ ModelNotification::STATUS_NEW ], 'consumed' => $notification->isConsumed() ];

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
	    }

		// Trigger Ajax Failure
        return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
	}

	public function actionDelete( $id ) {

		$notification	= ModelNotificationService::getById( $id );

		if( isset( $notification ) ) {

			ModelNotificationService::delete( $notification );

			$counts			= ModelNotificationService::getStatusCounts( $this->admin, $this->conditions );
			$data			= [ 'unread' => $counts[ 0 ] ];

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
	    }

		// Trigger Ajax Failure
        return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
	}
}

?>