<?php
namespace cmsgears\notify\admin\controllers\notification;

// Yii Imports
use \Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\notify\common\config\NotifyGlobal;

use cmsgears\core\common\models\entities\Template;
use cmsgears\notify\admin\models\forms\NotificationConfig;

use cmsgears\core\admin\services\entities\TemplateService;

class TemplateController extends \cmsgears\core\admin\controllers\base\TemplateController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->sidebar 	= [ 'parent' => 'sidebar-notify', 'child' => 'notification-template' ];

		$this->type			= NotifyGlobal::TYPE_NOTIFICATION;
	}

	// Instance Methods ------------------

	// yii\base\Component ----------------

	// TemplateController ----------------

	public function actionAll() {

		Url::remember( [ 'notification/template/all' ], 'templates' );

		return parent::actionAll();
	}

	public function actionCreate() {

		$this->setViewPath( '@cmsgears/module-notify/admin/views/notification/template' );

		$model			= new Template();
		$config			= new NotificationConfig();
		$model->type 	= $this->type;

		$model->setScenario( 'create' );

		if( $model->load( Yii::$app->request->post(), 'Template' ) && $config->load( Yii::$app->request->post(), 'NotificationConfig' ) &&
			$model->validate() && $config->validate() ) {

			TemplateService::create( $model );

			$model->updateDataAttribute( CoreGlobal::DATA_CONFIG, $config );

			return $this->redirect( $this->returnUrl );
		}

    	return $this->render( 'create', [
    		'model' => $model,
    		'config' => $config,
    		'renderers' => Yii::$app->templateSource->renderers
    	]);
	}

	public function actionUpdate( $id ) {

		$this->setViewPath( '@cmsgears/module-notify/admin/views/notification/template' );

		// Find Model
		$model		= TemplateService::findById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			$config	= new NotificationConfig( $model->getDataAttribute( CoreGlobal::DATA_CONFIG ) );

			$model->setScenario( 'update' );

			if( $model->load( Yii::$app->request->post(), 'Template' ) && $config->load( Yii::$app->request->post(), 'NotificationConfig' ) &&
				$model->validate() && $config->validate() ) {

				TemplateService::update( $model );

				$model->updateDataAttribute( CoreGlobal::DATA_CONFIG, $config );

				return $this->redirect( $this->returnUrl );
			}

	    	return $this->render( 'update', [
	    		'model' => $model,
	    		'config' => $config,
	    		'renderers' => Yii::$app->templateSource->renderers
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>