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
use cmsgears\notify\common\services\interfaces\base\IBulk;
use cmsgears\notify\common\services\interfaces\base\INotify;
use cmsgears\notify\common\services\interfaces\base\IToggle;

/**
 * IActivityService declares methods specific to activity model.
 *
 * @since 1.0.0
 */
interface IActivityService extends IModelResourceService, IBulk, INotify, IToggle {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

	public function deleteByUserId( $userId, $config = [] );

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
