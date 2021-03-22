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

/**
 * ReminderController provides actions specific to user reminders.
 *
 * @since 1.0.0
 */
class ReminderController extends \cmsgears\core\frontend\controllers\apix\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permission
		$this->crudPermission = CoreGlobal::PERM_USER;

		// Services
		$this->modelService = Yii::$app->factory->get( 'reminderService' );
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
			'toggle-read' => [ 'class' => 'cmsgears\notify\common\actions\reminder\ToggleRead', 'user' => true ],
			'toggle-trash' => [ 'class' => 'cmsgears\notify\common\actions\reminder\ToggleTrash', 'user' => true ],
			'read' => [ 'class' => 'cmsgears\notify\common\actions\reminder\Read', 'user' => true ],
			'trash' => [ 'class' => 'cmsgears\notify\common\actions\reminder\Trash', 'user' => true ],
			'delete' => [ 'class' => 'cmsgears\notify\common\actions\reminder\Delete', 'user' => true ],
			'bulk' => [ 'class' => 'cmsgears\notify\common\actions\reminder\Bulk', 'user' => true ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ReminderController --------------------

}
