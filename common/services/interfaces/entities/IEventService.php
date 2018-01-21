<?php
namespace cmsgears\notify\common\services\interfaces\entities;

// CMG Imports
use cmsgears\core\common\services\interfaces\base\IEntityService;

interface IEventService extends IEntityService {

	// Data Provider ------

	public function getPageForAdmin();

	public function getPageByUserId( $userId );

	public function getPageByParent( $parentId, $parentType, $admin = false );

	// Read ---------------

	// Read - Models ---

	public function getNewEvents();

	public function getByParentId( $parentId );

	// Read - Lists ----

	// Read - Maps -----

	// Create -------------

	// Update -------------

	public function updateStatus( $model, $status );

	public function trash( $model );

	// Delete -------------

}
