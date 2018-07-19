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

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\File;

use cmsgears\core\frontend\controllers\base\Controller;

/**
 * CalendarController provides actions specific to user events.
 *
 * @since 1.0.0
 */
class CalendarController extends Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Config
		$this->apixBase	= 'notify/calendar';

		// Services
		$this->modelService	= Yii::$app->factory->get( 'eventService' );

		// Return Url
		$this->returnUrl = Url::previous( 'calendar' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/notify/calendar/all' ], true );
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
					'all'  => [ 'permission' => $this->crudPermission ],
					'full'  => [ 'permission' => $this->crudPermission ],
					'add'  => [ 'permission' => $this->crudPermission ],
					'update'  => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'index' => [ 'get' ],
					'all'  => [ 'get' ],
					'full'  => [ 'get' ],
					'add' => [ 'get', 'post' ],
					'update' => [ 'get', 'post' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// EventController -----------------------

	public function actionIndex() {

		return $this->redirect( [ 'all' ] );
	}

	public function actionAll() {

		Url::remember( Yii::$app->request->getUrl(), 'calendar' );

		$modelClass		= $this->modelService->getModelClass();

		$user			= Yii::$app->user->getIdentity();
		$dataProvider	= $this->modelService->getPageByUserId( $user->id );

		return $this->render( 'all', [
			'dataProvider' => $dataProvider,
			'statusMap' => $modelClass::$statusMap
		]);
	}

	public function actionFull() {

		return $this->render( 'full' );
	}

	public function actionAdd() {

		$user	= Yii::$app->user->getIdentity();

		$modelClass	= $this->modelService->getModelClass();
		$model		= new $modelClass();

		$avatar	= File::loadFile( $model->avatar, 'Avatar' );
		$banner	= File::loadFile( $model->banner, 'Banner' );
		$video	= File::loadFile( $model->banner, 'Video' );

		$model->siteId	= Yii::$app->core->site->id;
		$model->userId	= $user->id;
		$model->type	= CoreGlobal::TYPE_DEFAULT;

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

			$this->model = $this->modelService->create( $model, [
				'admin' => true, 'avatar' => $avatar,
				'banner' => $banner, 'video' => $video
			]);

			return $this->redirect( $this->returnUrl );
		}

		return $this->render( 'create', [
			'model' => $model,
			'avatar' => $avatar,
			'banner' => $banner,
			'video' => $video,
			'statusMap' => $modelClass::$statusMinMap
		]);
	}

	public function actionUpdate( $id ) {

		$user	= Yii::$app->user->getIdentity();

		$modelClass	= $this->modelService->getModelClass();
		$model		= $this->modelService->getById( $id );

		$avatar	= File::loadFile( $model->avatar, 'Avatar' );
		$banner	= File::loadFile( $model->banner, 'Banner' );
		$video	= File::loadFile( $model->banner, 'Video' );

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

			$this->model = $this->modelService->create( $model, [
				'admin' => true, 'avatar' => $avatar,
				'banner' => $banner, 'video' => $video
			]);

			return $this->redirect( $this->returnUrl );
		}

		return $this->render( 'update', [
			'model' => $model,
			'avatar' => $avatar,
			'banner' => $banner,
			'video' => $video,
			'statusMap' => $modelClass::$statusMinMap
		]);
	}

}
