<?php
namespace cmsgears\notify\common\actions\notification;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\notify\common\models\mappers\ModelNotification;

use cmsgears\core\common\utilities\AjaxUtil;

class ToggleRead extends \cmsgears\core\common\base\Action {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public $admin		= false;

	public $conditions	= [];

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelNotificationService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		$this->modelNotificationService	= Yii::$app->factory->get( 'modelNotificationService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ToggleRead ----------------------------

	public function run( $directory, $type ) {

		$notification	= $this->modelNotificationService->getById( $id );

		if( isset( $notification ) ) {

			$notification	= $this->modelNotificationService->toggleRead( $notification );

			$counts			= $this->modelNotificationService->getStatusCounts( $this->admin, $this->conditions );

			$data			= [ 'unread' => $counts[ ModelNotification::STATUS_NEW ], 'consumed' => $notification->isConsumed() ];

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
	    }

		// Trigger Ajax Failure
        return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
	}
}
