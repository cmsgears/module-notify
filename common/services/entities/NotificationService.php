<?php
namespace cmsgears\notify\common\services\entities;

// Yii Imports
use Yii;
use yii\data\Sort;

// CMG Imports
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

		$modelClass		= static::$modelClass;
		$modelTable		= static::$modelTable;

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'title' => [
					'asc' => [ 'title' => SORT_ASC ],
					'desc' => ['title' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Title'
				],
				'type' => [
					'asc' => [ 'type' => SORT_ASC ],
					'desc' => ['type' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Type'
				],
				'ip' => [
					'asc' => [ 'ip' => SORT_ASC ],
					'desc' => ['ip' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'IP'
				],
				'agent' => [
					'asc' => [ 'agent' => SORT_ASC ],
					'desc' => ['agent' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Agent'
				],
				'admin' => [
					'asc' => [ 'admin' => SORT_ASC ],
					'desc' => ['admin' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Admin'
				],
				'consumed' => [
					'asc' => [ 'consumed' => SORT_ASC ],
					'desc' => ['consumed' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Consumed'
				],
				'trash' => [
					'asc' => [ 'trash' => SORT_ASC ],
					'desc' => ['trash' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Trash'
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

		// Query ------------

		if( !isset( $config[ 'query' ] ) ) {

			$config[ 'hasOne' ] = true;
		}

		// Filters ----------

		// Params
		$consumed 	= Yii::$app->request->getQueryParam( 'consumed' );
		$trash 		= Yii::$app->request->getQueryParam( 'trash' );

		// Filter - Consumed
		if( isset( $consumed ) ) {

			$filter = [ 'new' => 0, 'read' => 1 ];
			$config[ 'conditions' ][ "$modelTable.consumed" ]	= $filter[ $consumed ];
		}

		// Filter - Trash
		if( isset( $trash ) ) {

			$filter = [ 'trash' => 1 ];
			$config[ 'conditions' ][ "$modelTable.trash" ]	= $filter[ $trash ];
		}

		// Searching --------

		$searchCol	= Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [ 'title' => "$modelTable.title", 'content' => "$modelTable.content" ];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'title' => "$modelTable.title", 'content' => "$modelTable.content",
			'consumed' => "$modelTable.consumed", 'trash' => "$modelTable.trash"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	public function getPageForAdmin() {

		$modelTable	= self::$modelTable;

		return $this->getPage( [ 'conditions' => [ "$modelTable.admin" => true ] ] );
	}

	public function getPageByUserId( $userId ) {

		$modelTable	= self::$modelTable;

		return $this->getPage( [ 'conditions' => [ "$modelTable.userId" => $userId ] ] );
	}

	public function getPageByParent( $parentId, $parentType, $admin = false ) {

		$modelTable	= self::$modelTable;

		return $this->getPage( [ 'conditions' => [ "$modelTable.parentId" => $parentId, "$modelTable.parentType" => $parentType, "$modelTable.admin" => $admin ] ] );
	}

	// Read ---------------

	// Read - Models ---

	public function getRecent( $limit = 5, $config = [] ) {

		return Notification::find()->where( $config[ 'conditions' ] )->limit( $limit )->orderBy( 'createdAt DESC' )->all();
	}

	public function getRecentByParent( $parentId, $parentType, $limit = 5, $config = [] ) {

		return Notification::queryByParent( $parentId, $parentType )->andWhere( $config[ 'conditions' ] )->limit( $limit )->orderBy( 'createdAt ASC' )->all();
	}

	public function getCount( $consumed = false, $admin = false ) {

		return Notification::find()->where( 'consumed=:consumed AND admin=:admin', [ ':consumed' => $consumed, ':admin' => $admin ] )->count();
	}

	public function getUserCount( $userId, $consumed = false, $admin = false ) {

		return Notification::queryByUserId( $userId )->andWhere( 'consumed=:consumed AND admin=:admin', [ ':consumed' => $consumed, ':admin' => $admin ] )->count();
	}

	public function getCountByParent( $parentId, $parentType, $consumed = false, $admin = false ) {

		return Notification::queryByParent( $parentId, $parentType )->andWhere( 'consumed=:consumed AND admin=:admin', [ ':consumed' => $consumed, ':admin' => $admin ] )->count();
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function create( $model, $config = [] ) {

		$model->agent	= Yii::$app->request->userAgent;
		$model->ip	= Yii::$app->request->userIP;
		$model->siteId	= Yii::$app->core->siteId;

		return parent::create( $model, $config );
	}

	public function createByParams( $params = [], $config = [] ) {

		$params[ 'admin' ]		= isset( $params[ 'admin' ] ) ? $params[ 'admin' ] : false;
		$params[ 'adminLink' ]	= isset( $params[ 'adminLink' ] ) ? $params[ 'adminLink' ] : null;

		return parent::createByParams( $params, $config );
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		return parent::update( $model, [
			'attributes' => [ 'title', 'content' ]
		]);
	}

	public function toggleRead( $model ) {

		if( $model->isConsumed() ) {

			return $this->markNew( $model );
		}

		return $this->markConsumed( $model );
	}

	public function markNew( $model ) {

		$model->consumed = false;

		return parent::update( $model, [
			'attributes' => [ 'consumed' ]
		]);
	}

	public function markConsumed( $model ) {

		$model->consumed = true;

		return parent::update( $model, [
			'attributes' => [ 'consumed' ]
		]);
	}

	public function markTrash( $model ) {

		$model->trash = true;

		return parent::update( $model, [
			'attributes' => [ 'trash' ]
		]);
	}

	public function applyBulkByParent( $column, $action, $target, $parentId, $parentType ) {

		foreach ( $target as $id ) {

			$notification = $this->getById( $id );

			if( isset( $notification ) && $notification->parentId == $parentId && $notification->parentType == $parentType ) {

				$this->applyBulk( $notification, $column, $action, $target );
			}
		}
	}

	public function applyBulkByUserId( $column, $action, $target, $userId ) {

		foreach ( $target as $id ) {

			$notification = $this->getById( $id );

			if( isset( $notification ) && $notification->userId == $userId ) {

				$this->applyBulk( $notification, $column, $action, $target );
			}
		}
	}

	public function applyBulkByAdmin( $column, $action, $target ) {

		foreach ( $target as $id ) {

			$notification = $this->getById( $id );

			if( isset( $notification ) && $notification->admin ) {

				$this->applyBulk( $notification, $column, $action, $target );
			}
		}
	}

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'id': {

				$this->delete( $model );

				break;
			}
			case 'consumed': {

				switch( $action ) {

					case 'new': {

						$this->markNew( $model );

						break;
					}
					case 'read': {

						$this->markConsumed( $model );

						break;
					}
				}

				break;
			}
			case 'trash': {

				$this->markTrash( $model );

				break;
			}
			case 'model': {

				switch( $action ) {

					case 'delete': {

						$this->delete( $model );

						break;
					}
				}

				break;
			}
		}
	}

	// Delete -------------

	/*
	public function deleteByParent( $parentId, $parentType, $user = false ) {

		$modelTable	= self::$modelTable;

		$userIds    = $this->getIdList( [ 'conditions' => [ "$modelTable.userId" => $userId ] ] );
		$creatorIds = $this->getIdList( [ 'conditions' => [ "$modelTable.createdBy" => $userId ] ] );

		$models     = array_merge( $userIds, $creatorIds );

		if( count( $models ) > 0 ) {

			// Delete user notifications if any
			$this->applyBulkByUserId( 'model', 'delete', $models, $userId );

			// Delete admin notifications if any
			$this->applyBulkByAdmin( 'model', 'delete', $models );
		}
	}
	*/

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
