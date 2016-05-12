<?php
namespace cmsgears\notify\frontend\controllers\apix;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\notify\common\services\mappers\ModelNotificationService;

class NotificationController extends \cmsgears\notify\common\controllers\apix\NotificationController {

	private $modelService;

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->modelService = new ModelNotificationService();
		$user				= Yii::$app->user->getIdentity();
		$this->conditions	= [ 'userId' => $user->id ];
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

    public function behaviors() {

		$behaviors	= parent::behaviors();

        $behaviors[ 'rbac' ][ 'actions' ][ 'toggleRead' ] = [ 'permission' => CoreGlobal::PERM_USER, 'filters' => [ 'owner' => [ 'id' => true, 'service' => $this->modelService ] ] ];
	    $behaviors[ 'rbac' ][ 'actions' ][ 'trash' ] = [ 'permission' => CoreGlobal::PERM_USER, 'filters' => [ 'owner' => [ 'id' => true, 'service' => $this->modelService ] ] ];
		$behaviors[ 'rbac' ][ 'actions' ][ 'delete' ] = [ 'permission' => CoreGlobal::PERM_USER, 'filters' => [ 'owner' => [ 'id' => true, 'service' => $this->modelService ] ] ];

		return $behaviors;
    }

	// NotificationController ------------

}

?>