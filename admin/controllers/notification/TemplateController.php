<?php
namespace cmsgears\notify\admin\controllers\notification;

// Yii Imports
use \Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\notify\common\config\NotifyGlobal;

use cmsgears\notify\admin\models\forms\NotificationConfig;

class TemplateController extends \cmsgears\core\admin\controllers\base\TemplateController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

 	public function init() {

        parent::init();

		$this->sidebar 		= [ 'parent' => 'sidebar-notify', 'child' => 'notification-template' ];

		$this->type			= NotifyGlobal::TYPE_NOTIFICATION;

		$this->returnUrl	= Url::previous( 'templates' );
		$this->returnUrl	= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/notify/notification/template/all' ], true );
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

		Url::remember( [ 'notification/template/all' ], 'templates' );

		return parent::actionAll();
	}

	public function actionCreate() {

		$this->setViewPath( '@cmsgears/module-notify/admin/views/notification/template' );

		$modelClass		= $this->modelService->getModelClass();
		$model			= new $modelClass;
		$model->type 	= $this->type;

		$config			= new NotificationConfig();

		if( $model->load( Yii::$app->request->post(), $model->getClassName() )  && $config->load( Yii::$app->request->post(), 'NotificationConfig' ) &&
			$model->validate() && $config->validate() ) {

			$this->modelService->create( $model );

			$model->refresh();

			$model->updateDataAttribute( CoreGlobal::DATA_CONFIG, $config );

			return $this->redirect( $this->returnUrl );
		}

    	return $this->render( 'create', [
    		'model' => $model,
    		'config' => $config
    	]);
	}

	public function actionUpdate( $id ) {

		$this->setViewPath( '@cmsgears/module-notify/admin/views/notification/template' );

		// Find Model
		$model		= $this->modelService->getById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			$config	= new NotificationConfig( $model->getDataAttribute( CoreGlobal::DATA_CONFIG ) );

			if( $model->load( Yii::$app->request->post(), $model->getClassName() )  && $config->load( Yii::$app->request->post(), 'NotificationConfig' ) &&
				$model->validate() && $config->validate() ) {

				$this->modelService->update( $model );

				$model->updateDataAttribute( CoreGlobal::DATA_CONFIG, $config );

				return $this->redirect( $this->returnUrl );
			}

	    	return $this->render( 'update', [
	    		'model' => $model,
	    		'config' => $config
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
