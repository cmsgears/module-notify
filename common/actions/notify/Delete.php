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
 * Delete action deletes the model.
 *
 * @since 1.0.0
 */
abstract class Delete extends \cmsgears\core\common\base\Action {

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

	// Delete --------------------------------

	public function run( $id ) {

		$model = $this->notifyService->getById( $id );

		if( isset( $model ) ) {

			$new = 0;

			// Delete for specific parent
			if( isset( $this->parentType ) && isset( $this->parentId ) ) {

				if( $model->parentType == $this->parentType && $model->parentId == $this->parentId ) {

					$this->notifyService->delete( $model );
				}

				$config[ 'admin' ] = $this->admin;

				$new = $this->notifyService->getNotifyCountByParent( $this->parentId, $this->parentType, $config );
			}
			// Delete for admin
			else if( $this->admin ) {

				$this->notifyService->delete( $model );

				$new = $this->notifyService->getNotifyCount();
			}
			// Delete for User
			else if( $this->user ) {

				$user = Yii::$app->core->getUser();

				if( $model->userId == $user->id ) {

					$this->notifyService->delete( $model );
				}

				$new = $this->notifyService->getNotifyCountByUserId( $user->id );
			}

			$data = [ 'unread' => $new ];

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
	}

}
