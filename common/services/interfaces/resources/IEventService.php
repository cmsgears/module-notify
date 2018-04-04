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
 * IEventService declares methods specific to event model.
 *
 * @since 1.0.0
 */
interface IEventService extends IModelResourceService {

	// Data Provider ------

	public function getPageForAdmin();

	public function getPageByUserId( $userId );

	public function getPageByParent( $parentId, $parentType, $admin = false );

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function updateStatus( $model, $status );

	public function trash( $model );

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
