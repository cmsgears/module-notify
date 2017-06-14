<?php
namespace cmsgears\notify\admin\controllers;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class EventController extends \cmsgears\core\admin\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->crudPermission 	= CoreGlobal::PERM_CORE;
		$this->modelService		= Yii::$app->factory->get( 'eventService' );

		$this->sidebar 			= [ 'parent' => 'sidebar-reminder', 'child' => 'event' ];

		$this->returnUrl		= Url::previous( 'events' );
		$this->returnUrl		= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/notify/event/all' ], true );
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
					'index'  => [ 'permission' => $this->crudPermission ],
					'all'    => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'index'  => [ 'get' ],
					'all'   => [ 'get' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// NotificationController ----------------

	public function actionIndex() {

		return $this->redirect( [ 'all' ] );
	}

	public function actionAll() {

		Url::remember( [ 'event/all' ], 'events' );

		$dataProvider = $this->modelService->getPageForAdmin();

		return $this->render( 'all', [
			 'dataProvider' => $dataProvider
		]);
	}
}