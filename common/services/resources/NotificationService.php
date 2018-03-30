<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\notify\common\services\resources;

// Yii Imports
use Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\notify\common\config\NotifyGlobal;

use cmsgears\notify\common\services\interfaces\resources\INotificationService;

use cmsgears\core\common\services\base\ModelResourceService;

/**
 * NotificationService provide service methods of notification model.
 *
 * @since 1.0.0
 */
class NotificationService extends ModelResourceService implements INotificationService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\notify\common\models\resources\Notification';

	public static $parentType	= NotifyGlobal::TYPE_NOTIFICATION;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// NotificationService -------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'id' => [
					'asc' => [ "$modelTable.id" => SORT_ASC ],
					'desc' => [ "$modelTable.id" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Id'
				],
				'title' => [
					'asc' => [ "$modelTable.title" => SORT_ASC ],
					'desc' => [ "$modelTable.title" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Title'
				],
				'type' => [
					'asc' => [ "$modelTable.type" => SORT_ASC ],
					'desc' => [ "$modelTable.type" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Type'
				],
				'ip' => [
					'asc' => [ "$modelTable.ip" => SORT_ASC ],
					'desc' => [ "$modelTable.ip" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'IP'
				],
				'agent' => [
					'asc' => [ "$modelTable.agent" => SORT_ASC ],
					'desc' => [ "$modelTable.agent" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Agent'
				],
				'admin' => [
					'asc' => [ "$modelTable.admin" => SORT_ASC ],
					'desc' => [ "$modelTable.admin" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Admin'
				],
				'consumed' => [
					'asc' => [ "$modelTable.consumed" => SORT_ASC ],
					'desc' => [ "$modelTable.consumed" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Consumed'
				],
				'trash' => [
					'asc' => [ "$modelTable.trash" => SORT_ASC ],
					'desc' => [ "$modelTable.trash" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Trash'
				],
				'cdate' => [
					'asc' => [ "$modelTable.createdAt" => SORT_ASC ],
					'desc' => [ "$modelTable.createdAt" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Created At'
				],
				'udate' => [
					'asc' => [ "$modelTable.modifiedAt" => SORT_ASC ],
					'desc' => [ "$modelTable.modifiedAt" => SORT_DESC ],
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

			$config[ 'conditions' ][ "$modelTable.consumed" ] = $filter[ $consumed ];
		}

		// Filter - Trash
		if( isset( $trash ) ) {

			$filter = [ 'trash' => 1 ];

			$config[ 'conditions' ][ "$modelTable.trash" ] = $filter[ $trash ];
		}

		// Searching --------

		$searchCol = Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [
				'title' => "$modelTable.title",
				'content' => "$modelTable.content"
			];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'title' => "$modelTable.title",
			'content' => "$modelTable.content",
			'consumed' => "$modelTable.consumed",
			'trash' => "$modelTable.trash"
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

		$modelClass	= static::$modelClass;

		$siteId = Yii::$app->core->siteId;

		return $modelClass::find()->where( $config[ 'conditions' ] )->andWhere([ 'siteId' => $siteId ])->limit( $limit )->orderBy( 'createdAt DESC' )->all();
	}

	public function getRecentByParent( $parentId, $parentType, $limit = 5, $config = [] ) {

		$modelClass	= static::$modelClass;

		$siteId = Yii::$app->core->siteId;

		return $modelClass::queryByParent( $parentId, $parentType )->andWhere( $config[ 'conditions' ] )->limit( $limit )->orderBy( 'createdAt ASC' )->andWhere([ 'siteId' => $siteId ])->all();
	}

	public function getCount( $consumed = false, $admin = false ) {

		$modelClass	= static::$modelClass;

		$siteId = Yii::$app->core->siteId;

		return $modelClass::find()->where( 'consumed=:consumed AND admin=:admin', [ ':consumed' => $consumed, ':admin' => $admin ] )->andWhere([ 'siteId' => $siteId ])->count();
	}

	public function getUserCount( $userId, $consumed = false, $admin = false ) {

		$modelClass	= static::$modelClass;

		$siteId = Yii::$app->core->siteId;

		return $modelClass::queryByUserId( $userId )->andWhere( 'consumed=:consumed AND admin=:admin', [ ':consumed' => $consumed, ':admin' => $admin ] )->andWhere([ 'siteId' => $siteId ])->count();
	}

	public function getCountByParent( $parentId, $parentType, $consumed = false, $admin = false ) {

		$modelClass	= static::$modelClass;

		$siteId = Yii::$app->core->siteId;

		return $modelClass::queryByParent( $parentId, $parentType )->andWhere( 'consumed=:consumed AND admin=:admin', [ ':consumed' => $consumed, ':admin' => $admin ] )->andWhere([ 'siteId' => $siteId ])->count();
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function create( $model, $config = [] ) {

		$siteId = isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;

		$model->agent	= Yii::$app->request->userAgent;
		$model->ip		= Yii::$app->request->userIP;
		$model->siteId	= $siteId;

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

		foreach( $target as $id ) {

			$notification = $this->getById( $id );

			if( isset( $notification ) && $notification->parentId == $parentId && $notification->parentType == $parentType ) {

				$this->applyBulk( $notification, $column, $action, $target );
			}
		}
	}

	public function applyBulkByUserId( $column, $action, $target, $userId ) {

		foreach( $target as $id ) {

			$notification = $this->getById( $id );

			if( isset( $notification ) && $notification->userId == $userId ) {

				$this->applyBulk( $notification, $column, $action, $target );
			}
		}
	}

	public function applyBulkByAdmin( $column, $action, $target ) {

		foreach( $target as $id ) {

			$notification = $this->getById( $id );

			if( isset( $notification ) && $notification->admin ) {

				$this->applyBulk( $notification, $column, $action, $target );
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

	// Bulk ---------------

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

	// Notifications ------

	// Cache --------------

	// Additional ---------

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
