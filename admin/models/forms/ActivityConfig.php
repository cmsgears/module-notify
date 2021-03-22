<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\notify\admin\models\forms;

/**
 * ActivityConfig form allows admin to configure activity templates.
 *
 * @since 1.0.0
 */
class ActivityConfig extends \cmsgears\core\common\models\forms\DataModel {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public $storeContent = false;

	public $storeData = false;

	public $storeCache = false;

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
			[ [ 'storeContent', 'storeData', 'storeCache' ], 'required' ],
			[ [ 'storeContent', 'storeData', 'storeCache' ], 'boolean' ]
		];

		return $rules;
	}

	public function attributeLabels() {

		return [
			'storeContent' => 'Content',
			'storeData' => 'JSON Data',
			'storeCache' => 'JSON Cache'
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// ActivityConfig ------------------------

}
