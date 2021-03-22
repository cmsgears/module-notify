<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\notify\common\actions\notify;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * The Bulk Action for models.
 *
 * @since 1.0.0
 */
abstract class Bulk extends \cmsgears\core\common\base\Action {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public $admin	= false;
	public $user	= false;

	public $parentType;

	public $parentId;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $notifyService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Bulk ----------------------------------

	public function run() {

		$action	= Yii::$app->request->post( 'action' );
		$column	= Yii::$app->request->post( 'column' );
		$target	= Yii::$app->request->post( 'target' );

		if( isset( $action ) && isset( $column ) && isset( $target ) ) {

			$target	= preg_split( '/,/', $target );

			// Apply bulk action on parent specific models
			if( isset( $this->parentType ) && isset( $this->parentId ) ) {

				$this->notifyService->applyBulkByTargetIdParent( $column, $action, $target, $this->parentId, $this->parentType );
			}
			// Apply bulk action on admin specific models
			else if( $this->admin ) {

				$this->notifyService->applyBulkByTargetId( $column, $action, $target );
			}
			// Apply bulk action on user specific models
			else if( $this->user ) {

				$user = Yii::$app->core->getUser();

				$this->notifyService->applyBulkByTargetIdUserId( $column, $action, $target, $user->id );
			}

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
	}

}
