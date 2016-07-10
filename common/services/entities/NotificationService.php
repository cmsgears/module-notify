<?php
namespace cmsgears\notify\common\services\entities;

// Yii Imports
use \Yii;
use yii\data\Sort;
use yii\db\Query;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\notify\common\config\NotifyGlobal;

use cmsgears\notify\common\models\base\NotifyTables;
use cmsgears\notify\common\models\entities\Notification;

use cmsgears\notify\common\services\interfaces\entities\INotificationService;

use cmsgears\core\common\services\traits\ResourceTrait;

/**
 * The class NotificationService is base class to perform database activities for Notification Entity.
 */
class NotificationService extends \cmsgears\core\common\services\base\EntityService implements INotificationService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\notify\common\models\entities\Notification';

	public static $modelTable	= NotifyTables::TABLE_NOTIFICATION;

	public static $parentType	= NotifyGlobal::TYPE_NOTIFICATION;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use ResourceTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// NotificationService -------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$modelTable	= self::$modelTable;

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
	        ],
	        'defaultOrder' => [ 'cdate' => 'SORT_ASC' ]
	    ]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		// Params
		$status 		= Yii::$app->request->getQueryParam( 'status' );

		// Filter by Status
		if( isset( $status ) && isset( Notification::$revStatusMap[ $status ] ) ) {

			$config[ 'conditions' ][ "$modelTable.status" ]	= Notification::$revStatusMap[ $status ];
		}

		return parent::findPage( $config );
	}

	public function getPageForAdmin() {

		$modelTable	= self::$modelTable;

		return $this->getPage( [ 'conditions' => [ "$modelTable.admin" => true ] ] );
	}

	public function getPageByUserId( $userId ) {

		$modelTable	= self::$modelTable;

		return $this->getPage( [ 'conditions' => [ "$modelTable.userId" => $userId ] ] );
	}

	public function getPageByParent( $parentId, $parentType ) {

		$modelTable	= self::$modelTable;

		return $this->getPage( [ 'conditions' => [ "$modelTable.admin" => false, "$modelTable.parentId" => $parentId, "$modelTable.parentType" => $parentType ] ] );
	}

	// Read ---------------

    // Read - Models ---

	public function getByParentStatus( $parentId, $parentType, $status = Notification::STATUS_NEW ) {

		$modelTable	= self::$modelTable;

		return Notification::queryByParent( $parentId, $parentType )->andWhere( [ "$modelTable.status" => $status ] )->all();
	}

	public function getRecent( $limit = 5, $config = [] ) {

        return Notification::find()->where( $config[ 'conditions' ] )->limit( $limit )->orderBy( 'createdAt ASC' )->all();
	}

    public function getStatusCounts( $config = [] ) {

        $returnArr      = [ Notification::STATUS_NEW => 0, Notification::STATUS_CONSUMED => 0, Notification::STATUS_TRASH => 0 ];

        $notifyTable   = NotifyTables::TABLE_NOTIFICATION;
        $query          = new Query();

        $query->select( [ 'status', 'count(id) as total' ] )
                ->from( $notifyTable )
				->where( $config[ 'conditions' ] )
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

	public function getStatusCountsByParent( $parentId, $parentType ) {

		return $this->getStatusCounts( [ 'conditions' => [ 'admin' => false, 'parentId' => $parentId, 'parentType' => $parentType ] ] );
	}

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

 	public function create( $model, $config = [] ) {

		$model->agent	= Yii::$app->request->userAgent;
		$model->ip		= Yii::$app->request->userIP;

		return parent::create( $model, $config );
 	}

	// Update -------------

	public function update( $model, $config = [] ) {

		return parent::update( $model, [
			'attributes' => [ 'name', 'email', 'avatarUrl', 'websiteUrl', 'rating', 'content' ]
		]);
 	}

	public function toggleRead( $notification ) {

		if( $notification->isConsumed() ) {

			return $this->markNew( $notification );
		}

		return $this->markConsumed( $notification );
	}

	public function markNew( $notification ) {

		$notification->status	= Notification::STATUS_NEW;

		return parent::update( $model, [
			'attributes' => [ 'status' ]
		]);
	}

	public function markConsumed( $notification ) {

		$notification->status	= Notification::STATUS_CONSUMED;

		return parent::update( $model, [
			'attributes' => [ 'status' ]
		]);
	}

	public function markTrash( $notification ) {

		$notification->status	= Notification::STATUS_TRASH;

		return parent::update( $model, [
			'attributes' => [ 'status' ]
		]);
	}


	// Delete -------------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// NotificationService -------------------

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------
}
