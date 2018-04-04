<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\notify\admin\controllers\reminder;

// Yii Imports
use Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\notify\common\config\NotifyGlobal;

use cmsgears\notify\admin\models\forms\ReminderConfig;

use cmsgears\core\admin\controllers\base\TemplateController as BaseTemplateController;

/**
 * TemplateController provide actions specific to Reminder templates.
 *
 * @since 1.0.0
 */
class TemplateController extends BaseTemplateController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Template Type
		$this->type = NotifyGlobal::TYPE_REMINDER;

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-reminder', 'child' => 'rtemplate' ];

		// Return Url
		$this->returnUrl = Url::previous( 'templates' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/notify/reminder/template/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs = [
			'base' => [
				[ 'label' => 'Events', 'url' =>  [ '/notify/event/all' ] ],
				[ 'label' => 'Reminders', 'url' =>  [ '/notify/reminder/all' ] ]
			],
			'all' => [ [ 'label' => 'Templates' ] ],
			'create' => [ [ 'label' => 'Templates', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Templates', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Templates', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
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

		Url::remember( Yii::$app->request->getUrl(), 'templates' );

		return parent::actionAll( $config );
	}

	public function actionCreate( $config = [] ) {

		$this->setViewPath( '@cmsgears/module-notify/admin/views/reminder/template' );

		$modelClass		= $this->modelService->getModelClass();
		$model			= new $modelClass;

		$model->type	= $this->type;
		$model->siteId	= Yii::$app->core->siteId;

		$config = new ReminderConfig();

		if( $model->load( Yii::$app->request->post(), $model->getClassName() )  && $config->load( Yii::$app->request->post(), 'ReminderConfig' ) &&
			$model->validate() && $config->validate() ) {

			$this->modelService->create( $model );

			$model->refresh();

			$model->updateDataMeta( CoreGlobal::DATA_CONFIG, $config );

			return $this->redirect( 'all' );
		}

		return $this->render( 'create', [
			'model' => $model,
			'config' => $config
		]);
	}

	public function actionUpdate( $id, $config = [] ) {

		$this->setViewPath( '@cmsgears/module-notify/admin/views/reminder/template' );

		// Find Model
		$model = $this->modelService->getById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			$config	= new ReminderConfig( $model->getDataMeta( CoreGlobal::DATA_CONFIG ) );

			if( $model->load( Yii::$app->request->post(), $model->getClassName() )  && $config->load( Yii::$app->request->post(), 'ReminderConfig' ) &&
				$model->validate() && $config->validate() ) {

				$this->modelService->update( $model );

				$model->updateDataMeta( CoreGlobal::DATA_CONFIG, $config );

				return $this->redirect( $this->returnUrl );
			}

			return $this->render( 'update', [
				'model' => $model,
				'config' => $config
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
