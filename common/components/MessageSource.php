<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\notify\common\components;

// CMG Imports
use cmsgears\notify\common\config\NotifyGlobal;

use cmsgears\core\common\base\MessageSource as BaseMessageSource;

/**
 * MessageSource stores and provide the messages and message templates available in
 * Notify Module.
 *
 * @since 1.0.0
 */
class MessageSource extends BaseMessageSource {

	// Variables ---------------------------------------------------

	// Global -----------------

	// Public -----------------

	// Protected --------------

	protected $messageDb = [
		// Generic Fields
		NotifyGlobal::FIELD_EVENT => 'Event',
		NotifyGlobal::FIELD_FOLLOW => 'Follow',
		NotifyGlobal::FIELD_FOLLOW_ADMIN => 'Admin Follow'
	];

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// MessageSource -------------------------

}
