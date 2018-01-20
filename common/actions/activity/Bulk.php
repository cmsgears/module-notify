<?php
namespace cmsgears\notify\common\actions\activity;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

class Bulk extends \cmsgears\core\common\base\Action {

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

	public function run() {

		$action	= Yii::$app->request->post( 'action' );
		$column	= Yii::$app->request->post( 'column' );
		$target	= Yii::$app->request->post( 'target' );

		if( isset( $action ) && isset( $column ) && isset( $target ) ) {

			if( isset( $this->parentType ) && isset( $this->parentId ) ) {

				$target	= preg_split( '/,/', $target );

				$this->activityService->applyBulkByParent( $column, $action, $target, $this->parentId, $this->parentType );
			}
			else if( $this->admin ) {

				$target	= preg_split( '/,/', $target );

				$this->activityService->applyBulkByAdmin( $column, $action, $target );
			}
			else if( $this->user ) {

				$user	= Yii::$app->user->getIdentity();
				$target	= preg_split( '/,/', $target );

				$this->activityService->applyBulkByUserId( $column, $action, $target, $user->id );
			}

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
	}
}