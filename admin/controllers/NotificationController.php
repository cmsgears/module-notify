<?php
namespace cmsgears\notify\admin\controllers;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class NotificationController extends \cmsgears\core\admin\controllers\base\Controller {

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
		$this->modelService 	= Yii::$app->factory->get( 'notificationService' );

		$this->sidebar 		= [ 'parent' => 'sidebar-notify', 'child' => 'notification' ];

		$this->returnUrl 	= Url::previous( 'notifications' );
		$this->returnUrl 	= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/notify/notification/all' ], true );
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
						'index' => [ 'permission' => $this->crudPermission ],
						'all' => [ 'permission' => $this->crudPermission ]
					]
				],
				'verbs' => [
					'class' => VerbFilter::className(),
					'actions' => [
						'index' => [ 'get' ],
						'all' => [ 'get' ]
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

		Url::remember( [ 'notification/all' ], 'notifications' );

		$dataProvider = $this->modelService->getPageForAdmin();

		return $this->render( 'all', [ 'dataProvider' => $dataProvider ] );
	}
}