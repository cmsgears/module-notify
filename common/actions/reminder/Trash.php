<?php
namespace cmsgears\notify\common\actions\reminder;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

class Trash extends \cmsgears\core\common\base\Action {

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

	protected $reminderService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->reminderService	= Yii::$app->factory->get( 'reminderService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Trash ---------------------------------

	public function run( $id ) {

		$reminder	= $this->reminderService->getById( $id );

		if( isset( $reminder ) ) {

			$new	= 0;

			if( isset( $this->parentType ) && isset( $this->parentId ) ) {

				if( $reminder->parentType == $this->parentType && $reminder->parentId == $this->parentId ) {

					$reminder	= $this->reminderService->markTrash( $reminder );
				}

				$new 	= $this->reminderService->getCountByParent( $this->parentId, $this->parentType, false, false );
			}
			else if( $this->admin ) {

				$reminder	= $this->reminderService->markTrash( $reminder );
				$new 			= $this->reminderService->getCount( false, $this->admin );
			}
			else if( $this->user ) {

				$user	= Yii::$app->user->getIdentity();

				if( $reminder->userId == $user->id ) {

					$reminder	= $this->reminderService->markTrash( $reminder );
				}

				$new 	= $this->reminderService->getUserCount( $user->id, false, false );
			}

			$data	= [ 'unread' => $new, 'consumed' => $reminder->isConsumed() ];

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
	}
}
