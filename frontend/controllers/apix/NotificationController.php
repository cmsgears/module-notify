<?php
namespace cmsgears\notify\frontend\controllers\apix;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class NotificationController extends \cmsgears\core\common\controllers\base\Controller {

    // Variables ---------------------------------------------------

    // Globals ----------------

    // Public -----------------

    // Protected --------------

    // Private ----------------

    // Constructor and Initialisation ------------------------------

    public function init() {

        parent::init();

        $this->crudPermission	= CoreGlobal::PERM_USER;
        $this->modelService 	= Yii::$app->factory->get( 'notificationService' );
    }

    // Instance methods --------------------------------------------

    // Yii interfaces ------------------------

    // Yii parent classes --------------------

    // yii\base\Component -----

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->core->getRbacFilterClass(),
                'actions' => [
                    'toggleRead' => [ 'permission' => $this->crudPermission, 'filters' => [ 'owner' ] ],
                    'trash' => [ 'permission' => $this->crudPermission, 'filters' => [ 'owner' ] ],
                    'delete' => [ 'permission' => $this->crudPermission, 'filters' => [ 'owner' ] ]
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

        $user		= Yii::$app->user->getIdentity();
        $conditions	= [ 'userId' => $user->id ];

        return [
            'toggle-read' => [ 'class' => 'cmsgears\notify\common\actions\notification\ToggleRead', 'conditions' => $conditions ],
            'trash' => [ 'class' => 'cmsgears\notify\common\actions\notification\Trash', 'conditions' => $conditions ],
            'delete' => [ 'class' => 'cmsgears\notify\common\actions\notification\Delete', 'conditions' => $conditions ]
        ];
    }

    // CMG interfaces ------------------------

    // CMG parent classes --------------------

    // NotificationController ----------------
}
