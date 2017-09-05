<?php
namespace cmsgears\notify\common\services\interfaces\entities;

use cmsgears\notify\common\models\entities\Notification;

interface INotificationService extends \cmsgears\core\common\services\interfaces\base\IResourceService {

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

	public function deleteByParent( $parentId, $parentType, $user = false );
}
