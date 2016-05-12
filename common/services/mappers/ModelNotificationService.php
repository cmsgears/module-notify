<?php
namespace cmsgears\notify\common\services\mappers;

// Yii Imports
use \Yii;
use yii\data\Sort;
use \yii\db\Query;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\notify\common\models\base\NotifyTables;
use cmsgears\notify\common\models\mappers\ModelNotification;

/**
 * The class ModelNotificationService is base class to perform database activities for ModelNotification Entity.
 */
class ModelNotificationService extends \cmsgears\core\common\services\base\Service {

	public function findById( $id ) {

        return ModelNotification::findById( $id );
	}

	// Static Methods ----------------------------------------------

	// Read ----------------

	public static function getById( $id ) {

        return ModelNotification::findById( $id );
	}

	public static function getByParent( $parentId, $parentType, $status = ModelNotification::STATUS_NEW ) {

		return ModelNotification::queryByParent( $parentId, $parentType )->andWhere( [ 'status' => $status ] )->all();
	}

	public static function getRecent( $limit = 5, $admin = false ) {

        return ModelNotification::find()->where( [ 'admin' => $admin ] )->limit( $limit )->orderBy( 'createdAt ASC' )->all();
	}

    public static function getStatusCounts( $admin = 0, $conditions = [] ) {

        $returnArr      = [ ModelNotification::STATUS_NEW => 0, ModelNotification::STATUS_CONSUMED => 0, ModelNotification::STATUS_TRASH => 0 ];

        $notifyTable   = NotifyTables::TABLE_MODEL_NOTIFICATION;
        $query          = new Query();

        $query->select( [ 'status', 'count(id) as total' ] )
                ->from( $notifyTable )
				->where( [ 'admin' => $admin ] )
				->andWhere( $conditions )
                ->groupBy( 'status' );

        $counts     = $query->all();
        $counter    = 0;

        foreach ( $counts as $count ) {

            $returnArr[ $count[ 'status' ] ] = intval( $count[ 'total' ] );
        }

        foreach( $returnArr as $val ) {

            $counter    += $val;
        }

        $returnArr[ 'all' ] = $counter;

        return $returnArr;
    }

	public static function getStatusCountsByParent( $parentId, $parentType ) {

		return self::getStatusCounts( false, [ 'parentId' => $parentId, 'parentType' => $parentType ] );
	}

	// Data Provider ----

	/**
	 * @return ActiveDataProvider
	 */
	public static function getPagination( $config = [] ) {

	    $sort = new Sort([
	        'attributes' => [
	            'title' => [
	                'asc' => [ 'title' => SORT_ASC ],
	                'desc' => ['title' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Title'
	            ],
	            'status' => [
	                'asc' => [ 'status' => SORT_ASC ],
	                'desc' => ['status' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Status'
	            ],
	            'agent' => [
	                'asc' => [ 'agent' => SORT_ASC ],
	                'desc' => ['agent' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Agent'
	            ],
	            'cdate' => [
	                'asc' => [ 'createdAt' => SORT_ASC ],
	                'desc' => ['createdAt' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Created At'
	            ],
	            'udate' => [
	                'asc' => [ 'modifiedAt' => SORT_ASC ],
	                'desc' => ['modifiedAt' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Updated At'
	            ]
	        ]
	    ]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		if( !isset( $config[ 'search-col' ] ) ) {

			$config[ 'search-col' ] = 'content';
		}

		// Params
		$status 		= Yii::$app->request->getQueryParam( 'status' );

		// Filter by Status
		if( isset( $status ) && isset( ModelNotification::$revStatusMap[ $status ] ) ) {

			$config[ 'conditions' ][ 'status' ]	= ModelNotification::$revStatusMap[ $status ];
		}

		return self::getDataProvider( new ModelNotification(), $config );
	}

	public static function getPaginationForAdmin() {

		return self::getPagination( [ 'conditions' => [ "admin" => true ] ] );
	}

	public static function getPaginationByUserId( $userId ) {

		return self::getPagination( [ 'conditions' => [ "userId" => $userId ] ] );
	}

	public static function getPaginationByParent( $parentId, $parentType ) {

		return self::getPagination( [ 'conditions' => [ "admin" => 0, "parentId" => $parentId, "parentType" => $parentType ] ] );
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

	public static function toggleRead( $notification ) {

		if( $notification->isConsumed() ) {

			return self::markNew( $notification );
		}

		return self::markConsumed( $notification );
	}

	public static function markNew( $notification ) {

		$notification->status	= ModelNotification::STATUS_NEW;

		$notification->update();

		return $notification;
	}

	public static function markConsumed( $notification ) {

		$notification->status	= ModelNotification::STATUS_CONSUMED;

		$notification->update();

		return $notification;
	}

	public static function markTrash( $notification ) {

		$notification->status	= ModelNotification::STATUS_TRASH;

		$notification->update();

		return $notification;
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