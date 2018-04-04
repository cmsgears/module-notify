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
use cmsgears\notify\common\config\NotifyGlobal;

use cmsgears\core\admin\controllers\base\TemplateController as BaseTemplateController;

/**
 * TemplateController provide actions specific to Activity templates.
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
		$this->type = NotifyGlobal::TYPE_ACTIVITY;

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-activity', 'child' => 'template' ];

		// Return Url
		$this->returnUrl = Url::previous( 'templates' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/notify/activity/template/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs = [
			'base' => [ [ 'label' => 'Activities', 'url' =>  [ '/notify/activity/all' ] ] ],
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

}
