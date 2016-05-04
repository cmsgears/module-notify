<?php
namespace cmsgears\notify\common\services\mappers;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\notify\common\models\mappers\ModelNotification;

/**
 * The class ModelNotificationService is base class to perform database activities for ModelNotification Entity.
 */
class ModelNotificationService extends \cmsgears\core\common\services\base\Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	public static function getById( $id ) {

        return ModelNotification::findById( $id );
	}

	public static function getByParent( $parentId, $parentType, $consumed = false ) {

		return ModelNotification::queryByParent( $parentId, $parentType )->andWhere( [ 'consumed' => $consumed ] )->all();
	}

	// Data Provider ----

	/**
	 * @return ActiveDataProvider
	 */
	public static function getPagination( $config = [] ) {

	    $sort = new Sort([
	        'attributes' => [
	            'agent' => [
	                'asc' => [ 'agent' => SORT_ASC ],
	                'desc' => ['agent' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'agent'
	            ],
	            'content' => [
	                'asc' => [ 'content' => SORT_ASC ],
	                'desc' => ['content' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'content'
	            ]
	        ]
	    ]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		if( !isset( $config[ 'search-col' ] ) ) {

			$config[ 'search-col' ] = 'content';
		}

		return self::getDataProvider( new ModelNotification(), $config );
	}

	public static function getPaginationForAdmin() {

		return self::getPagination( [ 'conditions' => [ "admin" => true ] ] );
	}

	// Create -----------

 	public static function create( $notification ) {

		$notification->agent	= Yii::$app->request->userAgent;
		$notification->ip		= Yii::$app->request->userIP;

		$notification->save();

		return $notification;
 	}

	// Update -----------

	public static function update( $notification ) {

		// Find existing Notification
		$notificationToUpdate	= self::getById( $notification->id );

		// Copy and set Attributes
		$notificationToUpdate->copyForUpdateFrom( $notification, [ 'name', 'email', 'avatarUrl', 'websiteUrl', 'rating', 'content' ] );

		// Update Notification
		$notificationToUpdate->update();

		// Return updated Notification
		return $notificationToUpdate;
	}

	public static function markRead( $notification ) {

		$notification->consumed	= true;

		$notification->update();
	}

	public static function markUnread( $notification ) {

		$notification->consumed	= false;

		$notification->update();
	}

	// Delete -----------

	public static function delete( $notification ) {

		// Find existing Notification
		$notificationToDelete	= self::getById( $notification->id );

		// Delete Notification
		$notificationToDelete->delete();
    }
}

?>