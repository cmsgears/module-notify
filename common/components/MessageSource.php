<?php
namespace cmsgears\notify\common\components;

// Yii Imports
use \Yii;
use yii\base\Component;

// CMG Imports
use cmsgears\notify\common\config\NotifyGlobal;

class MessageSource extends Component {

	// Variables ---------------------------------------------------

	// Global -----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $messageDb = [
		// Generic Fields
		NotifyGlobal::FIELD_EVENT => 'Event',
		NotifyGlobal::FIELD_FOLLOW => 'Follow',
		NotifyGlobal::FIELD_FOLLOW_ADMIN => 'Admin Follow'
	];

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// MessageSource -------------------------

	public function getMessage( $messageKey, $params = [], $language = null ) {

		return $this->messageDb[ $messageKey ];
	}
}
