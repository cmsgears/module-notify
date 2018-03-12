<?php
namespace cmsgears\notify\common\config;

class NotifyGlobal {

	// Traits - Metas, Tags, Attachments, Addresses --------------------

	const TYPE_ACTIVITY		= 'activity';

	const TYPE_EVENT		= 'event';

	const TYPE_NOTIFICATION	= 'notification';

	const TYPE_REMINDER		= 'reminder';

	// Template

	const TEMPLATE_LOG_CREATE	= 'create';
	const TEMPLATE_LOG_UPDATE	= 'update';
	const TEMPLATE_LOG_DELETE	= 'delete';

	// Permissions -----------------------------------------------------

	// Config ----------------------------------------------------------

	// Errors ----------------------------------------------------------

	// Model Fields ----------------------------------------------------

	// Generic Fields
	const FIELD_EVENT			= 'eventField';
	const FIELD_FOLLOW			= 'followField';
	const FIELD_FOLLOW_ADMIN	= 'adminFollowField';
}
