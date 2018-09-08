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

use cmsgears\core\common\base\Action;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * ToggleTrash mark the model trash or valid.
 *
 * @since 1.0.0
 */
abstract class ToggleTrash extends Action {

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

	protected $notifyService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ToggleTrash ---------------------------

	public function run( $id ) {

		$model = $this->notifyService->getById( $id );

		if( isset( $model ) ) {

			$new = 0;

			// Toggle for specific parent
			if( isset( $this->parentType ) && isset( $this->parentId ) ) {

				if( $model->parentType == $this->parentType && $model->parentId == $this->parentId ) {

					$model = $this->notifyService->toggleTrash( $model );
				}

				$new = $this->notifyService->getCountByParent( $this->parentId, $this->parentType, false, false );
			}
			// Toggle for admin
			else if( $this->admin ) {

				$model = $this->notifyService->toggleTrash( $model );

				$new = $this->notifyService->getCount( false, $this->admin );
			}
			// Toggle for user
			else if( $this->user ) {

				$user = Yii::$app->user->getIdentity();

				if( $model->userId == $user->id ) {

					$model = $this->notifyService->toggleTrash( $model );
				}

				$new = $this->notifyService->getUserCount( $user->id, false, false );
			}

			$data = [ 'unread' => $new, 'trash' => $model->isTrash() ];

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
	}

}
