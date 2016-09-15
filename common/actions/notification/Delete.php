<?php
namespace cmsgears\notify\common\actions\notification;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\notify\common\models\entities\Notification;

use cmsgears\core\common\utilities\AjaxUtil;

class Delete extends \cmsgears\core\common\base\Action {

    // Variables ---------------------------------------------------

    // Globals -------------------------------

    // Constants --------------

    // Public -----------------

    public $admin		= false;

    public $conditions	= [];

    // Protected --------------

    // Variables -----------------------------

    // Public -----------------

    // Protected --------------

    protected $notificationService;

    // Private ----------------

    // Traits ------------------------------------------------------

    // Constructor and Initialisation ------------------------------

    public function init() {

        parent::init();

        $this->notificationService	= Yii::$app->factory->get( 'notificationService' );
    }

    // Instance methods --------------------------------------------

    // Yii interfaces ------------------------

    // Yii parent classes --------------------

    // CMG interfaces ------------------------

    // CMG parent classes --------------------

    // Delete --------------------------------

    public function run( $id ) {

        $notification	= $this->notificationService->getById( $id );

        if( isset( $notification ) ) {

            $notification	= $this->notificationService->delete( $notification );

            $counts			= $this->notificationService->getStatusCounts( $this->admin, $this->conditions );

            $data			= [ 'unread' => $counts[ Notification::STATUS_NEW ] ];

            // Trigger Ajax Success
            return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
        }

        // Trigger Ajax Failure
        return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
    }
}
