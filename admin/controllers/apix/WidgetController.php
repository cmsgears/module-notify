<?php
namespace cmsgears\cms\admin\controllers\apix;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\cms\common\config\CmsGlobal;

use cmsgears\core\common\models\forms\Binder;

use cmsgears\cms\admin\services\entities\WidgetService;

use cmsgears\core\common\utilities\AjaxUtil;

class WidgetController extends \yii\web\Controller {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'bindSidebars'  => [ 'permission' => CmsGlobal::PERM_CMS ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'bindSidebars'  => [ 'post' ]
                ]
            ]
        ];
    }

	// WidgetController

	public function actionBindSidebars() {

		$binder = new Binder();

		if( $binder->load( Yii::$app->request->post(), 'Binder' ) ) {

			if( WidgetService::bindSidebars( $binder ) ) {

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
			}
		}

		// Trigger Ajax Failure
        return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
	}
}

?>