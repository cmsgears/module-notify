<?php
namespace cmsgears\notify\admin\models\forms;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class NotificationConfig extends \cmsgears\core\common\models\forms\JsonModel {

	// Variables ---------------------------------------------------

	// Public Variables --------------------

	public $admin		= false;

	public $user		= false;

	public $adminEmail	= false;

	public $userEmail	= false;

	// Instance Methods --------------------------------------------

	// yii\base\Model

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
}

?>