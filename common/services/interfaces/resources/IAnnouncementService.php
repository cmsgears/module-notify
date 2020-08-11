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
use cmsgears\core\common\services\interfaces\base\IMultiSite;

/**
 * IAnnouncementService declares methods specific to announcement model.
 *
 * @since 1.0.0
 */
interface IAnnouncementService extends IModelResourceService, IMultiSite {

	// Data Provider ------

	public function getPageForAdmin();

	public function getPageForSite( $config = [] );

	public function getPageByParent( $parentId, $parentType, $admin = false );

	// Read ---------------

	// Read - Models ---

	public function getRecentForAdmin( $limit = 5, $config = [] );

	public function getRecentForSite( $limit = 5, $config = [] );

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

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
