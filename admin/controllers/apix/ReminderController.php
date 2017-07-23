<?php
namespace cmsgears\notify\admin\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

class ReminderController extends \cmsgears\core\admin\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permissions
		$this->crudPermission 	= CoreGlobal::PERM_CORE;

		// Services
		$this->modelService		= Yii::$app->factory->get( 'reminderService' );
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
					'toggle-read' => [ 'permission' => $this->crudPermission ],
					'trash' => [ 'permission' => $this->crudPermission ],
					'delete' => [ 'permission' => $this->crudPermission ],
					'bulk' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'toggle-read' => [ 'post' ],
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
			'toggle-read' => [ 'class' => 'cmsgears\notify\common\actions\reminder\ToggleRead', 'admin' => true ],
			'trash' => [ 'class' => 'cmsgears\notify\common\actions\reminder\Trash', 'admin' => true ],
			'delete' => [ 'class' => 'cmsgears\notify\common\actions\reminder\Delete', 'admin' => true ],
			'bulk' => [ 'class' => 'cmsgears\notify\common\actions\reminder\Bulk', 'admin' => true ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ReminderController --------------------

}
