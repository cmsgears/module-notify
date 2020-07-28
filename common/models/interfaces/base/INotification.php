<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\notify\common\models\interfaces\base;

/**
 * The IStatusSwitch interface provide methods specific to models supporting consumed and trash features.
 *
 * @since 1.0.0
 */
interface INotification {

	/**
	 * Return notifications triggered for the model.
	 *
	 * @return \cmsgears\notify\common\models\entities\Notification[]
	 */
	public function getNotifications();

	/**
	 * Return the status count of notifications.
	 *
	 * @return array
	 */
	public function getNotificationStatusCounts();

}
