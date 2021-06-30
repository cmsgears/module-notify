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

use cmsgears\notify\common\config\NotifyGlobal;

use cmsgears\core\common\models\resources\File;

use cmsgears\core\common\utilities\DateUtil;

use cmsgears\core\common\behaviors\ActivityBehavior;

/**
 * EventController provide actions specific to admin events.
 *
 * @since 1.0.0
 */
class EventController extends \cmsgears\core\admin\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $templateService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permission
		$this->crudPermission = NotifyGlobal::PERM_NOTIFY_ADMIN;

		// Config
		$this->apixBase = 'notify/event';

		// Services
		$this->modelService		= Yii::$app->factory->get( 'calendarEventService' );
		$this->templateService	= Yii::$app->factory->get( 'templateService' );

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-reminder', 'child' => 'event' ];

		// Return Url
		$this->returnUrl = Url::previous( 'events' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/notify/event/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs = [
			'base' => [
				[ 'label' => 'Home', 'url' => Url::toRoute( '/dashboard' ) ]
			],
			'all' => [ [ 'label' => 'Events' ] ],
			'create' => [ [ 'label' => 'Events', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Events', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Events', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ],
			'data' => [ [ 'label' => 'Events', 'url' => $this->returnUrl ], [ 'label' => 'Data' ] ],
			'attributes' => [ [ 'label' => 'Events', 'url' => $this->returnUrl ], [ 'label' => 'Attributes' ] ],
			'config' => [ [ 'label' => 'Events', 'url' => $this->returnUrl ], [ 'label' => 'Config' ] ],
			'settings' => [ [ 'label' => 'Events', 'url' => $this->returnUrl ], [ 'label' => 'Settings' ] ]
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
					'all' => [ 'permission' => $this->crudPermission ],
					'create' => [ 'permission' => $this->crudPermission ],
					'update' => [ 'permission' => $this->crudPermission ],
					'delete' => [ 'permission' => $this->crudPermission ],
					'data' => [ 'permission' => $this->crudPermission ],
					'attributes' => [ 'permission' => $this->crudPermission ],
					'config' => [ 'permission' => $this->crudPermission ],
					'settings' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'index' => [ 'get', 'post' ],
					'all' => [ 'get' ],
					'create' => [ 'get', 'post' ],
					'update' => [ 'get', 'post' ],
					'delete' => [ 'get', 'post' ],
					'data' => [ 'get', 'post' ],
					'attributes' => [ 'get', 'post' ],
					'config' => [ 'get', 'post' ],
					'settings' => [ 'get', 'post' ]
				]
			],
			'activity' => [
				'class' => ActivityBehavior::class,
				'admin' => true,
				'create' => [ 'create' ],
				'update' => [ 'update' ],
				'delete' => [ 'delete' ]
			]
		];
	}

	// yii\base\Controller ----

	public function actions() {

		$actions = parent::actions();

		$actions[ 'data' ] = [ 'class' => 'cmsgears\core\common\actions\data\data\Form' ];
		$actions[ 'attributes' ] = [ 'class' => 'cmsgears\core\common\actions\data\attributes\Form' ];
		$actions[ 'config' ] = [ 'class' => 'cmsgears\core\common\actions\data\config\Form' ];
		$actions[ 'settings' ] = [ 'class' => 'cmsgears\core\common\actions\data\setting\Form' ];

		return $actions;
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// EventController -----------------------

	public function actionIndex() {

		return $this->redirect( [ 'all' ] );
	}

	public function actionAll() {

		Url::remember( Yii::$app->request->getUrl(), 'events' );

		$modelClass = $this->modelService->getModelClass();

		$dataProvider = $this->modelService->getPageForAdmin();

		return $this->render( 'all', [
			'dataProvider' => $dataProvider,
			'statusMap' => $modelClass::$statusMap,
			'filterStatusMap' => $modelClass::$filterStatusMap
		]);
	}

	public function actionCreate( $config = [] ) {

		$modelClass = $this->modelService->getModelClass();

		$model = new $modelClass();

		$avatar	= File::loadFile( null, 'Avatar' );
		$banner	= File::loadFile( null, 'Banner' );
		$video	= File::loadFile( null, 'Video' );

		$model->siteId	= Yii::$app->core->siteId;
		$model->admin	= true;
		$model->type	= CoreGlobal::TYPE_ADMIN;

		$model->preIntervalUnit		= DateUtil::DURATION_HOUR;
		$model->postIntervalUnit	= DateUtil::DURATION_HOUR;

		$model->preReminderCount		= 0;
		$model->preReminderInterval		= 0;
		$model->preTriggerCount			= 0;
		$model->postReminderCount		= 0;
		$model->postReminderInterval	= 0;
		$model->postTriggerCount		= 0;

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

			$this->model = $this->modelService->add( $model, [
				'admin' => true, 'avatar' => $avatar, 'banner' => $banner, 'video' => $video
			]);

			if( $this->model ) {

				return $this->redirect( 'all' );
			}
		}

		$templatesMap = $this->templateService->getIdNameMapByType( NotifyGlobal::TYPE_EVENT, [ 'default' => true ] );

		return $this->render( 'create', [
			'model' => $model,
			'avatar' => $avatar,
			'banner' => $banner,
			'video' => $video,
			'statusMap' => $modelClass::$statusMap,
			'unitMap' => DateUtil::$durationMap,
			'templatesMap' => $templatesMap
		]);
	}

	public function actionUpdate( $id, $config = [] ) {

		$modelClass = $this->modelService->getModelClass();

		// Find Model
		$model = $this->modelService->getById( $id );

		// Update if exist
		if( isset( $model ) ) {

			$avatar	= File::loadFile( $model->avatar, 'Avatar' );
			$banner	= File::loadFile( $model->banner, 'Banner' );
			$video	= File::loadFile( $model->video, 'Video' );

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				$this->model = $this->modelService->update( $model, [
					'admin' => true, 'avatar' => $avatar, 'banner' => $banner, 'video' => $video
				]);

				return $this->redirect( $this->returnUrl );
			}

			$templatesMap = $this->templateService->getIdNameMapByType( NotifyGlobal::TYPE_EVENT, [ 'default' => true ] );

			// Render view
			return $this->render( 'update', [
				'model' => $model,
				'avatar' => $avatar,
				'banner' => $banner,
				'video' => $video,
				'statusMap' => $modelClass::$statusMap,
				'unitMap' => DateUtil::$durationMap,
				'templatesMap' => $templatesMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id, $config = [] ) {

		$modelClass = $this->modelService->getModelClass();

		// Find Model
		$model = $this->modelService->getById( $id );

		// Delete if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				try {

					$this->model = $model;

					$this->modelService->delete( $model, [ 'admin' => true ] );

					return $this->redirect( $this->returnUrl );
				}
				/**
				 * Throw errors since the model is required by other models and it cannot be deleted
				 * till all the dependent models are deleted.
				 */
				catch( Exception $e ) {

					throw new HttpException( 409, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_DEPENDENCY )  );
				}
			}

			$templatesMap = $this->templateService->getIdNameMapByType( NotifyGlobal::TYPE_EVENT, [ 'default' => true ] );

			// Render view
			return $this->render( 'delete', [
				'model' => $model,
				'avatar' => $model->avatar,
				'banner' => $model->banner,
				'video' => $model->video,
				'statusMap' => $modelClass::$statusMap,
				'unitMap' => DateUtil::$durationMap,
				'templatesMap' => $templatesMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
