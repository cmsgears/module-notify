<?php
namespace cmsgears\notify\admin\controllers\reminder;

// Yii Imports
use Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\notify\common\config\NotifyGlobal;

use cmsgears\notify\admin\models\forms\ReminderConfig;

class TemplateController extends \cmsgears\core\admin\controllers\base\TemplateController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Type
		$this->type			= NotifyGlobal::TYPE_REMINDER;

		// Sidebar
		$this->sidebar 		= [ 'parent' => 'sidebar-reminder', 'child' => 'template' ];

		// Return Url
		$this->returnUrl	= Url::previous( 'templates' );
		$this->returnUrl	= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/notify/reminder/template/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs	= [
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

	public function actionAll() {

		Url::remember( [ 'reminder/template/all' ], 'templates' );

		return parent::actionAll();
	}

	public function actionCreate() {

		$this->setViewPath( '@cmsgears/module-notify/admin/views/reminder/template' );

		$modelClass		= $this->modelService->getModelClass();
		$model			= new $modelClass;
		$model->type 	= $this->type;

		$config			= new ReminderConfig();

		if( $model->load( Yii::$app->request->post(), $model->getClassName() )  && $config->load( Yii::$app->request->post(), 'ReminderConfig' ) &&
			$model->validate() && $config->validate() ) {

			$this->modelService->create( $model );

			$model->refresh();

			$model->updateDataMeta( CoreGlobal::DATA_CONFIG, $config );

			return $this->redirect( "update?id=$model->id" );
		}

		return $this->render( 'create', [
			'model' => $model,
			'config' => $config
		]);
	}

	public function actionUpdate( $id ) {

		$this->setViewPath( '@cmsgears/module-notify/admin/views/reminder/template' );

		// Find Model
		$model		= $this->modelService->getById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			$config	= new ReminderConfig( $model->getDataMeta( CoreGlobal::DATA_CONFIG ) );

			if( $model->load( Yii::$app->request->post(), $model->getClassName() )  && $config->load( Yii::$app->request->post(), 'ReminderConfig' ) &&
				$model->validate() && $config->validate() ) {

				$this->modelService->update( $model );

				$model->updateDataMeta( CoreGlobal::DATA_CONFIG, $config );

				return $this->refresh();
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
