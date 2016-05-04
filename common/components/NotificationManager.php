<?php
namespace cmsgears\notify\common\components;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class NotificationManager extends \yii\base\Component {

	// Variables ---------------------------------------------------

	public $email	= true;	// Check whether emails are enabled for notifications.

	// Init --------------------------------------------------------

    public function init() {

        parent::init();
    }

	// NotificationManager -----------------------------------------

	public function triggerNotification( $message, $models, $config = [] ) {

		// TODO: Trigger Notification
	}
}

?>