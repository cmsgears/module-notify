<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\notify\frontend\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\controllers\base\Controller;

/**
 * NotificationController provides actions specific to user notifications.
 *
 * @since 1.0.0
 */
class NotificationController extends Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->crudPermission	= CoreGlobal::PERM_USER;
		$this->modelService 	= Yii::$app->factory->get( 'notificationService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	public function behaviors() {

		return [
			'rbac' => [
				'class' => Yii::$app->core->getRbacFilterClass(),
				'actions' => [
					'toggle-read' => [ 'permission' => $this->crudPermission, 'filters' => [ 'owner' ] ],
					'trash' => [ 'permission' => $this->crudPermission, 'filters' => [ 'owner' ] ],
					'delete' => [ 'permission' => $this->crudPermission, 'filters' => [ 'owner' ] ],
					'bulk' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'toggle-read' => [ 'post' ],
					'trash' => [ 'post' ],
					'delete' => [ 'post' ],
					'bulk' => [ 'post' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	public function actions() {

		return [
			'toggle-read' => [ 'class' => 'cmsgears\notify\common\actions\notification\ToggleRead' ],
			'trash' => [ 'class' => 'cmsgears\notify\common\actions\notification\Trash' ],
			'delete' => [ 'class' => 'cmsgears\notify\common\actions\notification\Delete' ],
			'bulk' => [ 'class' => 'cmsgears\notify\common\actions\notification\Bulk' ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// NotificationController ----------------

}
