<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\notify\admin\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\notify\common\config\NotifyGlobal;

use cmsgears\core\admin\controllers\base\Controller;

/**
 * ActivityController provide actions specific to Activity model.
 *
 * @since 1.0.0
 */
class ActivityController extends Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permissions
		$this->crudPermission = NotifyGlobal::PERM_NOTIFY_ADMIN;

		// Services
		$this->modelService = Yii::$app->factory->get( 'activityService' );
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
					'toggle-read' => [ 'permission' => $this->crudPermission ],
					'read' => [ 'permission' => $this->crudPermission ],
					'trash' => [ 'permission' => $this->crudPermission ],
					'delete' => [ 'permission' => $this->crudPermission ],
					'bulk' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'toggle-read' => [ 'post' ],
					'read' => [ 'post' ],
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
			'toggle-read' => [ 'class' => 'cmsgears\notify\common\actions\activity\ToggleRead', 'admin' => true ],
			'read' => [ 'class' => 'cmsgears\notify\common\actions\activity\Read', 'admin' => true ],
			'trash' => [ 'class' => 'cmsgears\notify\common\actions\activity\Trash', 'admin' => true ],
			'delete' => [ 'class' => 'cmsgears\notify\common\actions\activity\Delete', 'admin' => true ],
			'bulk' => [ 'class' => 'cmsgears\notify\common\actions\activity\Bulk', 'admin' => true ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ActivityController --------------------

}
