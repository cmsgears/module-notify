<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\notify\admin\controllers\activity;

// Yii Imports
use Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\notify\common\config\NotifyGlobal;

use cmsgears\notify\admin\models\forms\ActivityConfig;

/**
 * TemplateController provide actions specific to Activity templates.
 *
 * @since 1.0.0
 */
class TemplateController extends \cmsgears\core\admin\controllers\base\TemplateController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permission
		$this->crudPermission = NotifyGlobal::PERM_NOTIFY_ADMIN;

		// Config
		$this->type		= NotifyGlobal::TYPE_ACTIVITY;
		$this->apixBase	= 'notify/template';

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-activity', 'child' => 'template' ];

		// Return Url
		$this->returnUrl = Url::previous( 'activity-templates' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/notify/activity/template/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs = [
			'base' => [
				[ 'label' => 'Home', 'url' => Url::toRoute( '/dashboard' ) ]
			],
			'all' => [ [ 'label' => 'Activity Templates' ] ],
			'create' => [ [ 'label' => 'Activity Templates', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Activity Templates', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Activity Templates', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// TemplateController --------------------

	public function actionAll( $config = [] ) {

		Url::remember( Yii::$app->request->getUrl(), 'activity-templates' );

		return parent::actionAll( $config );
	}

	public function actionCreate( $config = [] ) {

		$this->setViewPath( '@cmsgears/module-notify/admin/views/activity/template' );

		$model = $this->modelService->getModelObject();

		$model->type = $this->type;

		$modelConfig = new ActivityConfig();

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $modelConfig->load( Yii::$app->request->post(), 'ActivityConfig' ) &&
			$model->validate() && $modelConfig->validate() ) {

			$this->model = $this->modelService->create( $model, [ 'admin' => true ] );

			$this->model->refresh();

			$this->modelService->updateDataMeta( $model, CoreGlobal::DATA_CONFIG, $modelConfig );

			return $this->redirect( 'all' );
		}

		return $this->render( 'create', [
			'model' => $model,
			'config' => $modelConfig
		]);
	}

	public function actionUpdate( $id, $config = [] ) {

		$this->setViewPath( '@cmsgears/module-notify/admin/views/activity/template' );

		// Find Model
		$model = $this->modelService->getById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			$modelConfig = new ActivityConfig( $model->getDataMeta( CoreGlobal::DATA_CONFIG ) );

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $modelConfig->load( Yii::$app->request->post(), 'ActivityConfig' ) &&
				$model->validate() && $modelConfig->validate() ) {

				$this->model = $this->modelService->update( $model, [ 'admin' => true ] );

				$this->model->refresh();

				$this->modelService->updateDataMeta( $model, CoreGlobal::DATA_CONFIG, $modelConfig );

				return $this->redirect( $this->returnUrl );
			}

			return $this->render( 'update', [
				'model' => $model,
				'config' => $modelConfig
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
