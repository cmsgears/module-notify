<?php
namespace cmsgears\notify\common\actions\activity;

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

	protected $activityService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->activityService	= Yii::$app->factory->get( 'activityService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Delete --------------------------------

	public function run( $id ) {

		$activity	= $this->activityService->getById( $id );

		if( isset( $activity ) ) {

			$new	= 0;

			if( isset( $this->parentType ) && isset( $this->parentId ) ) {

				if( $activity->parentType == $this->parentType && $activity->parentId == $this->parentId ) {

					$activity	= $this->activityService->delete( $activity );
				}

				$new 	= $this->activityService->getCountByParent( $this->parentId, $this->parentType, false, false );
			}
			else if( $this->admin ) {

				$activity	= $this->activityService->delete( $activity );
				$new 			= $this->activityService->getCount( false, $this->admin );
			}
			else if( $this->user ) {

				$user	= Yii::$app->user->getIdentity();

				if( $activity->userId == $user->id ) {

					$activity	= $this->activityService->delete( $activity );
				}

				$new 	= $this->activityService->getUserCount( $user->id, false, false );
			}

			$data	= [ 'unread' => $new ];

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
	}
}
