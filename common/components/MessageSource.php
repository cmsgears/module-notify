<?php
namespace cmsgears\notify\common\components;

// Yii Imports
use \Yii;
use yii\base\Component;

// CMG Imports
use cmsgears\notify\common\config\NotifyGlobal;

class MessageSource extends Component {

	// Variables ---------------------------------------------------

	private $messageDb = [
		// Generic Fields
		NotifyGlobal::FIELD_EVENT => 'Event',
		NotifyGlobal::FIELD_FOLLOW => 'Follow',
		NotifyGlobal::FIELD_FOLLOW_ADMIN => 'Admin Follow'
	];

	/**
	 * Initialise the Cms Message DB Component.
	 */
    public function init() {

        parent::init();
    }

	public function getMessage( $messageKey, $params = [], $language = null ) {

		return $this->messageDb[ $messageKey ];
	}
}

?>