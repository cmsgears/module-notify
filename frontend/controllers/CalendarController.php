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

use cmsgears\core\common\utilities\DateUtil;

/**
 * CalendarController provides actions specific to calendar events.
 *
 * @since 1.0.0
 */
class CalendarController extends \cmsgears\notify\frontend\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Config
		$this->layout	= Yii::$app->notify->customLayout[ 'calendar' ] ?? $this->layout;
		$this->apixBase	= 'notify/calendar';

		// Services
		$this->modelService	= Yii::$app->factory->get( 'calendarEventService' );

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
					'index' => [ 'permission' => $this->crudPermission ],
					'all' => [ 'permission' => $this->crudPermission ],
					'full' => [ 'permission' => $this->crudPermission ],
					'add' => [ 'permission' => $this->crudPermission ],
					'update' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'index' => [ 'get' ],
					'all' => [ 'get' ],
					'full' => [ 'get' ],
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

		$modelClass = $this->modelService->getModelClass();

		$user = Yii::$app->core->getUser();

		$dataProvider = $this->modelService->getPageByUserId( $user->id );

		return $this->render( 'all', [
			'dataProvider' => $dataProvider,
			'statusMap' => $modelClass::$statusMap
		]);
	}

	public function actionFull() {

		Url::remember( Yii::$app->request->getUrl(), 'calendar' );

		return $this->render( 'full' );
	}

	public function actionAdd() {

		$user = Yii::$app->core->getUser();

		$modelClass	= $this->modelService->getModelClass();
		$model		= new $modelClass();

		$avatar	= File::loadFile( $model->avatar, 'Avatar' );
		$banner	= File::loadFile( $model->banner, 'Banner' );
		$video	= File::loadFile( $model->banner, 'Video' );

		$model->siteId	= Yii::$app->core->siteId;
		$model->userId	= $user->id;
		$model->type	= CoreGlobal::TYPE_USER;

		$model->preIntervalUnit		= DateUtil::DURATION_HOUR;
		$model->postIntervalUnit	= DateUtil::DURATION_HOUR;

		$model->preReminderCount		= 0;
		$model->preReminderInterval		= 0;
		$model->preTriggerCount			= 0;
		$model->postReminderCount		= 0;
		$model->postReminderInterval	= 0;
		$model->postTriggerCount		= 0;

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

			$model->admin = false;

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
			'statusMap' => $modelClass::$minStatusMap
		]);
	}

	public function actionUpdate( $id ) {

		$user = Yii::$app->core->getUser();

		$modelClass	= $this->modelService->getModelClass();
		$model		= $this->modelService->getById( $id );
		$admin		= $model->isOwner( $user ); // Admin own events

		$avatar	= File::loadFile( $model->avatar, 'Avatar' );
		$banner	= File::loadFile( $model->banner, 'Banner' );
		$video	= File::loadFile( $model->banner, 'Video' );

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

			$this->model = $this->modelService->create( $model, [
				'admin' => $admin, 'avatar' => $avatar,
				'banner' => $banner, 'video' => $video
			]);

			return $this->redirect( $this->returnUrl );
		}

		return $this->render( 'update', [
			'model' => $model,
			'avatar' => $avatar,
			'banner' => $banner,
			'video' => $video,
			'statusMap' => $modelClass::$minStatusMap
		]);
	}

}
