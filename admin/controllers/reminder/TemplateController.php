<?php
namespace cmsgears\notify\admin\controllers\reminder;

// Yii Imports
use \Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\notify\common\config\NotifyGlobal;

class TemplateController extends \cmsgears\core\admin\controllers\base\TemplateController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->sidebar 		= [ 'parent' => 'sidebar-reminder', 'child' => 'template' ];

		$this->type			= NotifyGlobal::TYPE_REMINDER;

		$this->returnUrl	= Url::previous( 'templates' );
		$this->returnUrl	= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/notify/reminder/template/all' ], true );
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
}
