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
 * Read mark the model read.
 *
 * @since 1.0.0
 */
class Stats extends \cmsgears\core\common\base\Action {

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

	// ToggleRead ----------------------------

	public function run( $type = null ) {

		$data = null;

		// Stats for specific parent
		if( isset( $this->parentType ) && isset( $this->parentId ) ) {

			$data = Yii::$app->eventManager->getModelStats( $this->parentId, $this->parentType, $type );
		}
		// Stats for admin
		else if( $this->admin ) {

			$data = Yii::$app->eventManager->getAdminStats( $type );
		}
		// Stats for User
		else if( $this->user ) {

			$data = Yii::$app->eventManager->getUserStats( $type );
		}

		// Trigger Ajax Success
		return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
	}

}
