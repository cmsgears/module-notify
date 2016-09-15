<?php
namespace cmsgears\notify\common\components;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\notify\common\config\NotifyGlobal;

class MessageSource extends \yii\base\Component {

    // Variables ---------------------------------------------------

    // Global -----------------

    // Public -----------------

    // Protected --------------

    protected $messageDb = [
        // Generic Fields
        NotifyGlobal::FIELD_EVENT => 'Event',
        NotifyGlobal::FIELD_FOLLOW => 'Follow',
        NotifyGlobal::FIELD_FOLLOW_ADMIN => 'Admin Follow'
    ];

    // Private ----------------

    // Constructor and Initialisation ------------------------------

    // Instance methods --------------------------------------------

    // Yii parent classes --------------------

    // CMG parent classes --------------------

    // MessageSource -------------------------

    public function getMessage( $messageKey, $params = [], $language = null ) {

        return $this->messageDb[ $messageKey ];
    }
}
