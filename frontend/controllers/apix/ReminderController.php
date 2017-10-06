<?php
namespace cmsgears\notify\frontend\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class ReminderController extends \cmsgears\core\common\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->crudPermission	= CoreGlobal::PERM_USER;
		$this->modelService 	= Yii::$app->factory->get( 'reminderService' );
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
					'toggle-read' => [ 'permission' => $this->crudPermission, 'filters' => [ 'owner' ] ],
					'trash' => [ 'permission' => $this->crudPermission, 'filters' => [ 'owner' ] ],
					'delete' => [ 'permission' => $this->crudPermission, 'filters' => [ 'owner' ] ],
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
			'toggle-read' => [ 'class' => 'cmsgears\notify\common\actions\reminder\ToggleRead' ],
			'trash' => [ 'class' => 'cmsgears\notify\common\actions\reminder\Trash' ],
			'delete' => [ 'class' => 'cmsgears\notify\common\actions\reminder\Delete' ],
			'bulk' => [ 'class' => 'cmsgears\notify\common\actions\reminder\Bulk' ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ReminderController --------------------

}
