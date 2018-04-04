<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\notify\admin\controllers;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\admin\controllers\base\Controller as BaseController;

/**
 * ReminderController provide actions specific to Reminder model.
 *
 * @since 1.0.0
 */
class ReminderController extends BaseController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permission
		$this->crudPermission = CoreGlobal::PERM_CORE;

		// Services
		$this->modelService = Yii::$app->factory->get( 'reminderService' );

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-reminder', 'child' => 'reminder' ];

		// Return Url
		$this->returnUrl = Url::previous( 'reminders' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/notify/reminder/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs = [
			'base' => [ [ 'label' => 'Events', 'url' =>  [ '/notify/event/all' ] ] ],
			'all' => [ [ 'label' => 'Reminders' ] ]
		];
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
				'class' => VerbFilter::class,
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

		Url::remember( Yii::$app->request->getUrl(), 'reminders' );

		$dataProvider = $this->modelService->getPageForAdmin();

		return $this->render( 'all', [
			'dataProvider' => $dataProvider
		]);
	}

}
