<?php
namespace cmsgears\notify\common\services\interfaces\entities;

interface IActivityService extends \cmsgears\core\common\services\interfaces\base\IResourceService {

	// Data Provider ------

	public function getPageByUserId( $userId );

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Create -------------

	// Update -------------

	public function applyBulkByParent( $column, $action, $target, $parentId, $parentType );

	public function applyBulkByUserId( $column, $action, $target, $userId );
	
	public function createActivity( $model );
	public function updateActivity( $model );
	public function deleteActivity( $model );

	// Delete -------------

}
