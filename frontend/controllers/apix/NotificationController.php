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
					'delete' => [ 'permission' => $this->crudPermission, 'filters' => [ 'owner' ] ],
					'bulk' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'toggleRead' => [ 'post' ],
					'trash' => [ 'post' ],
					'delete' => [ 'post' ],
					'bulk' => [ 'post' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	public function actions() {

		return [
			'toggle-read' => [ 'class' => 'cmsgears\notify\common\actions\notification\ToggleRead' ],
			'trash' => [ 'class' => 'cmsgears\notify\common\actions\notification\Trash' ],
			'delete' => [ 'class' => 'cmsgears\notify\common\actions\notification\Delete' ],
			'bulk' => [ 'class' => 'cmsgears\notify\common\actions\notification\Bulk' ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// NotificationController ----------------
}
