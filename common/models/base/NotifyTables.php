<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\notify\common\models\base;

use cmsgears\core\common\models\base\DbTables;

class NotifyTables extends DbTables {

	// Entities -------------

	const TABLE_ACTIVITY			= 'cmg_notify_activity';
	const TABLE_EVENT				= 'cmg_notify_event';
	const TABLE_NOTIFICATION		= 'cmg_notify_notification';

	// Resources ------------

	const TABLE_EVENT_REMINDER		= 'cmg_notify_event_reminder';

	// Mappers --------------

	const TABLE_EVENT_PARTICIPANT	= 'cmg_notify_event_participant';
}
