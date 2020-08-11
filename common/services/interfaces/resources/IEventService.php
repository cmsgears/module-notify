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
use cmsgears\core\common\services\interfaces\base\INameType;
use cmsgears\core\common\services\interfaces\base\ISlugType;
use cmsgears\core\common\services\interfaces\mappers\IFile;

/**
 * IEventService declares methods specific to event model.
 *
 * @since 1.0.0
 */
interface IEventService extends IModelResourceService, IFile, IMultiSite, INameType, ISlugType {

	// Data Provider ------

	public function getPageForAdmin( $config = [] );

	public function getPageByUserId( $userId, $config = [] );

	public function getPageByParent( $parentId, $parentType, $config = [] );

	// Read ---------------

	// Read - Models ---

	public function getByRangeUserId( $startDate, $endDate, $userId, $config = [] );

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function markGrouped( $model );

	public function updateStatus( $model, $status );

	public function cancel( $model );

	public function activate( $model );

	public function expire( $model );

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
