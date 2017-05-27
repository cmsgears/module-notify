<?php
namespace cmsgears\notify\common\services\interfaces\entities;

// CMG Imports
use cmsgears\core\common\services\interfaces\base\IEntityService;

interface IReminderService extends \cmsgears\core\common\services\interfaces\base\IEntityService {

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

	// Create -------------

	// Update -------------

	// Delete -------------

	public function toggleRead( $model );

	public function markNew( $model );

	public function markConsumed( $model );

	public function markTrash( $model );

	public function applyBulkByUserId( $column, $action, $target, $userId );

	public function applyBulkByAdmin( $column, $action, $target );

}
