<?php
namespace cmsgears\notify\common\services\interfaces\entities;

interface IActivityService extends \cmsgears\core\common\services\interfaces\base\IResourceService {

	// Data Provider ------

	public function getPageByUserId( $userId );

	// Read ---------------

	// Read - Models ---

	public function getRecent( $limit = 5, $config = [] );

	public function getCount( $consumed = false );

	// Read - Lists ----

	// Read - Maps -----

	// Create -------------

	// Update -------------

	public function toggleRead( $notification );

	public function markNew( $notification );

	public function markConsumed( $notification );

	public function markTrash( $notification );
	
	public function applyBulkByParent( $column, $action, $target, $parentId, $parentType );

	public function applyBulkByUserId( $column, $action, $target, $userId );
	
	public function applyBulkByAdmin( $column, $action, $target );
	
	public function createActivity( $model );
	public function updateActivity( $model );
	public function deleteActivity( $model );

	// Delete -------------

}
