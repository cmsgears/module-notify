<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\notify\admin\models\forms;

// CMG Imports
use cmsgears\core\common\models\forms\DataModel;

/**
 * ReminderConfig form allows admin to configure reminder templates.
 *
 * @since 1.0.0
 */
class ReminderConfig extends DataModel {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public $admin		= false;

	public $user		= false;

	public $adminEmail	= false;

	public $userEmail	= false;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Model ---------

	public function rules() {

		$rules = [
			[ [ 'admin', 'user', 'adminEmail', 'userEmail' ], 'required' ],
			[ [ 'admin', 'user', 'adminEmail', 'userEmail' ], 'boolean' ]
		];

		return $rules;
	}

	public function attributeLabels() {

		return [
			'admin' => 'Admin',
			'user' => 'User',
			'adminEmail' => 'Admin Email',
			'userEmail' => 'User Email'
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// ReminderConfig ------------------------

}
