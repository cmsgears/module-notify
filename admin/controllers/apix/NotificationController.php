<?php
namespace cmsgears\notify\admin\controllers\apix;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class NotificationController extends \cmsgears\notify\common\controllers\apix\NotificationController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->admin	= CoreGlobal::STATUS_YES;
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

    public function behaviors() {

		$behaviors	= parent::behaviors();

        $behaviors[ 'rbac' ][ 'actions' ][ 'toggleRead' ] = [ 'permission' => CoreGlobal::PERM_CORE ];
	    $behaviors[ 'rbac' ][ 'actions' ][ 'trash' ] = [ 'permission' => CoreGlobal::PERM_CORE ];
		$behaviors[ 'rbac' ][ 'actions' ][ 'delete' ] = [ 'permission' => CoreGlobal::PERM_CORE ];

		return $behaviors;
    }

	// NotificationController ------------
}

?>