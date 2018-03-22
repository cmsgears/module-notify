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
use cmsgears\core\common\services\interfaces\base\IModelResourceService;

/**
 * INotificationService declares methods specific to notification model.
 *
 * @since 1.0.0
 */
interface INotificationService extends IModelResourceService {

	// Data Provider ------

	public function getPageForAdmin();

	public function getPageByUserId( $userId );

	public function getPageByParent( $parentId, $parentType, $admin = false );

	// Read ---------------

	// Read - Models ---

	public function getRecent( $limit = 5, $config = [] );

	public function getRecentByParent( $parentId, $parentType, $limit = 5, $config = [] );

	public function getCount( $consumed = false );

	public function getUserCount( $userId, $consumed = false, $admin = false );

	public function getCountByParent( $parentId, $parentType, $consumed = false );

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function toggleRead( $notification );

	public function markNew( $notification );

	public function markConsumed( $notification );

	public function markTrash( $notification );

	public function applyBulkByParent( $column, $action, $target, $parentId, $parentType );

	public function applyBulkByUserId( $column, $action, $target, $userId );

	public function applyBulkByAdmin( $column, $action, $target );

	// Delete -------------

	//public function deleteByParent( $parentId, $parentType, $user = false );

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
