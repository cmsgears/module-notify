<?php
namespace cmsgears\notify\common\services\interfaces\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\notify\common\models\entities\Notification;

interface INotificationService extends \cmsgears\core\common\services\interfaces\base\IResourceService {

    // Data Provider ------

    public function getPageForAdmin();

    public function getPageByUserId( $userId );

    public function getPageByParent( $parentId, $parentType );

    // Read ---------------

    // Read - Models ---

    public function getByParentStatus( $parentId, $parentType, $status = Notification::STATUS_NEW );

    public function getRecent( $limit = 5, $config = [] );

    public function getStatusCounts( $config = [] );

    public function getStatusCountsByParent( $parentId, $parentType );

    // Read - Lists ----

    // Read - Maps -----

    // Create -------------

    // Update -------------

    public function toggleRead( $notification );

    public function markNew( $notification );

    public function markConsumed( $notification );

    public function markTrash( $notification );

    // Delete -------------

}
