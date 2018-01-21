<?php
namespace cmsgears\notify\common\actions\notification;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

class Delete extends \cmsgears\core\common\base\Action {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public $user	= true;

	public $admin	= false;

	public $parentType;

	public $parentId;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $notificationService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->notificationService	= Yii::$app->factory->get( 'notificationService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Delete --------------------------------

	public function run( $id ) {

		$notification	= $this->notificationService->getById( $id );

		if( isset( $notification ) ) {

			$new	= 0;

			if( isset( $this->parentType ) && isset( $this->parentId ) ) {

				if( $notification->parentType == $this->parentType && $notification->parentId == $this->parentId ) {

					$notification	= $this->notificationService->delete( $notification );
				}

				$new 	= $this->notificationService->getCountByParent( $this->parentId, $this->parentType, false, false );
			}
			else if( $this->admin ) {

				$notification	= $this->notificationService->delete( $notification );
				$new 			= $this->notificationService->getCount( false, $this->admin );
			}
			else if( $this->user ) {

				$user	= Yii::$app->user->getIdentity();

				if( $notification->userId == $user->id ) {

					$notification	= $this->notificationService->delete( $notification );
				}

				$new 	= $this->notificationService->getUserCount( $user->id, false, false );
			}

			$data	= [ 'unread' => $new ];

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
	}
}
