<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\notify\frontend\controllers;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;

/**
 * ActivityController provides actions specific to user reminders.
 *
 * @since 1.0.0
 */
class ActivityController extends \cmsgears\notify\frontend\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Config
		$this->layout = Yii::$app->notify->customLayout[ 'activity' ] ?? $this->layout;

		// Services
		$this->modelService	= Yii::$app->factory->get( 'activityService' );

		// Return Url
		$this->returnUrl = Url::previous( 'activities' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/notify/activity/all' ], true );
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
					'index'	 => [ 'permission' => $this->crudPermission ],
					'all'  => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'index' => [ 'get', 'post' ],
					'all'  => [ 'get' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ActivityController --------------------

	public function actionIndex() {

		return $this->redirect( [ 'all' ] );
	}

	public function actionAll() {

		Url::remember( Yii::$app->request->getUrl(), 'activities' );

		$user = Yii::$app->core->getUser();

		$dataProvider = $this->modelService->getPageByUserId( $user->id );

		return $this->render( 'all', [
			'dataProvider' => $dataProvider
		]);
	}

}
