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
 * StatsController provide actions specific to Notify Module stats specific to User.
 *
 * @since 1.0.0
 */
class StatsController extends \cmsgears\core\frontend\controllers\apix\base\Controller {

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
					'stats' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'stats' => [ 'get', 'post' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	public function actions() {

		return [
			'stats' => [ 'class' => 'cmsgears\notify\common\actions\notify\Stats', 'user' => true ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// StatsController -----------------------

}
