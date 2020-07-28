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

/**
 * NotificationController provide actions specific to admin notifications.
 *
 * @since 1.0.0
 */
class NotificationController extends \cmsgears\core\admin\controllers\apix\base\Controller {

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
		$this->modelService = Yii::$app->factory->get( 'notificationService' );
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
					'toggle-trash' => [ 'permission' => $this->crudPermission ],
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
					'toggle-trash' => [ 'post' ],
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
			'toggle-read' => [ 'class' => 'cmsgears\notify\common\actions\notification\ToggleRead', 'admin' => true ],
			'toggle-trash' => [ 'class' => 'cmsgears\notify\common\actions\reminder\ToggleTrash', 'admin' => true ],
			'read' => [ 'class' => 'cmsgears\notify\common\actions\notification\Read', 'admin' => true ],
			'trash' => [ 'class' => 'cmsgears\notify\common\actions\notification\Trash', 'admin' => true ],
			'delete' => [ 'class' => 'cmsgears\notify\common\actions\notification\Delete', 'admin' => true ],
			'bulk' => [ 'class' => 'cmsgears\notify\common\actions\notification\Bulk', 'admin' => true ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// NotificationController ----------------

}
