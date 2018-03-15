<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\notify\common\config;

/**
 * NotifyGlobal defines the global constants and variables available for notify and dependent modules.
 *
 * @since 1.0.0
 */
class NotifyGlobal {

	// System Sites ---------------------------------------------------

	// System Pages ---------------------------------------------------

	// Grouping by type ------------------------------------------------

	const TYPE_ACTIVITY		= 'activity';

	const TYPE_EVENT		= 'event';

	const TYPE_NOTIFICATION	= 'notification';

	const TYPE_REMINDER		= 'reminder';

	// Templates -------------------------------------------------------

	const TEMPLATE_LOG_CREATE	= 'create';
	const TEMPLATE_LOG_UPDATE	= 'update';
	const TEMPLATE_LOG_DELETE	= 'delete';

	// Config ----------------------------------------------------------

	// Roles -----------------------------------------------------------

	// Permissions -----------------------------------------------------

	// Model Attributes ------------------------------------------------

	// Default Maps ----------------------------------------------------

	// Messages --------------------------------------------------------

	// Errors ----------------------------------------------------------

	// Model Fields ----------------------------------------------------

	// Generic Fields
	const FIELD_EVENT			= 'eventField';
	const FIELD_FOLLOW			= 'followField';
	const FIELD_FOLLOW_ADMIN	= 'adminFollowField';

}
