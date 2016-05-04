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

use cmsgears\cms\admin\services\entities\PostService;

use cmsgears\core\common\utilities\AjaxUtil;

class PostController extends \yii\web\Controller {

	public $modelName;
	public $modelType;
	public $modelService;

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->modelName	= 'Post';
		$this->modelType	= CmsGlobal::TYPE_POST;
		$this->modelService	= new PostService();
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'bindCategories'  => [ 'permission' => CmsGlobal::PERM_CMS ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'bindCategories'  => [ 'post' ]
                ]
            ]
        ];
    }

	// yii\base\Controller ---------------

    public function actions() {

        return [
            'create-tags' => [
                'class' => 'cmsgears\core\frontend\actions\common\UpdateTags'
            ],
            'delete-tag' => [
                'class' => 'cmsgears\core\frontend\actions\common\DeleteTag'
            ]
        ];
    }

	// PostController

	public function actionBindCategories() {

		$binder = new Binder();

		if( $binder->load( Yii::$app->request->post(), 'Binder' ) ) {

			if( PostService::bindCategories( $binder ) ) {

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
			}
		}

		// Trigger Ajax Failure
        return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
	}
}

?>