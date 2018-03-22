<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\notify\common\services\interfaces\resources;

// CMG Imports
use cmsgears\core\common\services\interfaces\base\IResourceService;

/**
 * IEventReminderService declares methods specific to event reminder.
 *
 * @since 1.0.0
 */
interface IEventReminderService extends IResourceService {

	// Data Provider ------

	public function getPageForAdmin();

	public function getPageByUserId( $userId );

	// Read ---------------

	// Read - Models ---

	public function getRecent( $limit = 5, $config = [] );

	public function getCount( $consumed = false, $admin = false );

	public function getUserCount( $userId, $consumed = false, $admin = false );

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

	public function toggleRead( $model );

	public function markNew( $model );

	public function markConsumed( $model );

	public function markTrash( $model );

	public function applyBulkByUserId( $column, $action, $target, $userId );

	public function applyBulkByAdmin( $column, $action, $target );

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
