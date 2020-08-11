<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\notify\admin\controllers\event;

// Yii Imports
use Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\notify\common\config\NotifyGlobal;

/**
 * ModelFileController provides actions specific to newsletter files.
 *
 * @since 1.0.0
 */
class ModelFileController extends \cmsgears\core\admin\controllers\base\ModelFileController {

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
		$this->title	= 'Event File';
		$this->apixBase	= 'notify/event/model-file';

		// Services
		$this->parentService = Yii::$app->factory->get( 'eventService' );

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-reminder', 'child' => 'event' ];

		// Return Url
		$this->returnUrl = Url::previous( 'event-files' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/notify/event/file/all' ], true );

		// All Url
		$allUrl = Url::previous( 'events' );
		$allUrl = isset( $allUrl ) ? $allUrl : Url::toRoute( [ '/notify/event/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs = [
			'base' => [
				[ 'label' => 'Home', 'url' => Url::toRoute( '/dashboard' ) ],
				[ 'label' => 'Events', 'url' =>  $allUrl ]
			],
			'all' => [ [ 'label' => 'Event Files' ] ],
			'create' => [ [ 'label' => 'Event Files', 'url' => $this->returnUrl ], [ 'label' => 'Create' ] ],
			'update' => [ [ 'label' => 'Event Files', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Event Files', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ModelFileController -------------------

	public function actionAll( $pid ) {

		Url::remember( Yii::$app->request->getUrl(), 'event-files' );

		return parent::actionAll( $pid );
	}

}
