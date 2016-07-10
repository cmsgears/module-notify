<?php
namespace cmsgears\notify\admin\controllers\apix;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class NotificationController extends \cmsgears\notify\common\controllers\apix\NotificationController {

	// Constructor and Initialisation ------------------------------

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->core->getRbacFilterClass(),
                'actions' => [
	                'toggleRead' => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'trash' => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'delete' => [ 'permission' => CoreGlobal::PERM_CORE ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'toggleRead' => [ 'post' ],
	                'trash' => [ 'post' ],
	                'delete' => [ 'post' ]
                ]
            ]
        ];
    }

	// yii\base\Controller ----

    public function actions() {

        return [
        	'toggle-read' => [ 'class' => 'cmsgears\notify\common\actions\notification\ToggleRead', 'admin' => true ],
        	'trash' => [ 'class' => 'cmsgears\notify\common\actions\notification\Trash', 'admin' => true ],
        	'delete' => [ 'class' => 'cmsgears\notify\common\actions\notification\Delete', 'admin' => true ]
		];
    }

	// NotificationController ------------
}

?>