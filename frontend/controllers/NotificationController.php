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
 * NotificationController provides actions specific to notifications.
 *
 * @since 1.0.0
 */
class NotificationController extends \cmsgears\notify\frontend\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Config
		$this->layout	= Yii::$app->notify->customLayout[ 'notification' ] ?? $this->layout;
		$this->apixBase	= 'notify/notification';

		// Services
		$this->modelService	= Yii::$app->factory->get( 'notificationService' );

		// Return Url
		$this->returnUrl = Url::previous( 'notifications' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/notify/notification/all' ], true );
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
					'index' => [ 'get' ],
					'all'  => [ 'get' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// NotificationController ----------------

	public function actionIndex() {

		return $this->redirect( [ 'all?status=inbox' ] );
	}

	public function actionAll( $status = null ) {

		Url::remember( Yii::$app->request->getUrl(), 'notifications' );

		$user = Yii::$app->core->getUser();

		$dataProvider = null;

		switch( $status ) {

			case 'inbox': {

				$dataProvider = $this->modelService->getPageByUserId( $user->id, [ 'status' => 'inbox' ] );

				break;
			}
			case 'new': {

				$dataProvider = $this->modelService->getPageByUserId( $user->id, [ 'status' => 'new' ] );

				break;
			}
			case 'read': {

				$dataProvider = $this->modelService->getPageByUserId( $user->id, [ 'status' => 'read' ] );

				break;
			}
			case 'trash': {

				$dataProvider = $this->modelService->getPageByUserId( $user->id, [ 'status' => 'trash' ] );

				break;
			}
			default: {

				$dataProvider = $this->modelService->getPageByUserId( $user->id );
			}
		}

		return $this->render( 'all', [
			'dataProvider' => $dataProvider,
			'status' => $status
		]);
	}

}
