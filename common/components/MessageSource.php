<?php
namespace cmsgears\notify\common\components;

// Yii Imports
use \Yii;
use yii\base\Component;

// CMG Imports
use cmsgears\cms\common\config\CmsGlobal;

class MessageSource extends Component {

	// Variables ---------------------------------------------------

	private $messageDb = [
		// Generic Fields
		CmsGlobal::FIELD_EVENT => 'Event'
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