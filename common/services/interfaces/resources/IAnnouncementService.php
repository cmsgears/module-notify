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
 * IAnnouncementService declares methods specific to announcement model.
 *
 * @since 1.0.0
 */
interface IAnnouncementService extends IModelResourceService {

	// Data Provider ------

	public function getPageForAdmin();

	public function getPageByParent( $parentId, $parentType, $admin = false );

	// Read ---------------

	// Read - Models ---

	public function getRecentByAdmin( $limit = 5, $config = [] );

	public function getRecentByParent( $parentId, $parentType, $limit = 5, $config = [] );

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function updateStatus( $model, $status );

	public function approve( $model );

	public function activate( $model );

	public function pause( $model );

	public function expire( $model );

	// Delete -------------

	// Bulk ---------------

	public function applyBulkByParent( $column, $action, $target, $parentId, $parentType );

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
