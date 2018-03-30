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

use cmsgears\notify\common\services\interfaces\resources\IActivityService;

use cmsgears\core\common\services\base\ModelResourceService;

/**
 * ActivityService provide service methods of activity model.
 *
 * @since 1.0.0
 */
class ActivityService extends ModelResourceService implements IActivityService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\notify\common\models\resources\Activity';

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

	// ActivityService -----------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$userTable = Yii::$app->get( 'userService' )->getModelTable();

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'id' => [
					'asc' => [ "$modelTable.id" => SORT_ASC ],
					'desc' => [ "$modelTable.id" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Id'
				],
	            'user' => [
					'asc' => [ "`$userTable`.`firstName`" => SORT_ASC, "`$userTable`.`lastName`" => SORT_ASC ],
					'desc' => [ "`$userTable`.`firstName`" => SORT_DESC, "`$userTable`.`lastName`" => SORT_DESC ],
					'default' => SORT_DESC,
	                'label' => 'User'
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
				'agent' => [
					'asc' => [ "$modelTable.agent" => SORT_ASC ],
					'desc' => [ "$modelTable.agent" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Agent'
				],
				'cdate' => [
					'asc' => [ "$modelTable.createdAt" => SORT_ASC ],
					'desc' => [ "$modelTable.createdAt" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Created At'
				],
				'udate' => [
					'asc' => [ "$modelTable.updatedAt" => SORT_ASC ],
					'desc' => [ "$modelTable.updatedAt" => SORT_DESC ],
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
		$type 	= Yii::$app->request->getQueryParam( 'type' );

		// Filter - Type
		if( isset( $type ) ) {

			$config[ 'conditions' ][ "$modelTable.type" ]	= $type;
		}

		// Searching --------

		$searchCol	= Yii::$app->request->getQueryParam( 'search' );

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
			'type' => "$modelTable.type"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	public function getPageByUserId( $userId ) {

		$modelTable	= self::$modelTable;

		return $this->getPage( [ 'conditions' => [ "$modelTable.userId" => $userId ] ] );
	}

	// Read ---------------

	// Read - Models ---

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

	public function getRecent( $limit = 5, $config = [] ) {

		$modelClass	= static::$modelClass;

		$siteId = Yii::$app->core->siteId;

		return $modelClass::find()->where( $config[ 'conditions' ] )->andWhere([ 'siteId' => $siteId ])->limit( $limit )->orderBy( 'createdAt DESC' )->all();
	}

	public function getCount( $consumed = false, $admin = false ) {

		$modelClass	= static::$modelClass;

		$siteId = Yii::$app->core->siteId;

		return $modelClass::find()->where( 'consumed=:consumed AND admin=:admin', [ ':consumed' => $consumed, ':admin' => $admin ] )->andWhere([ 'siteId' => $siteId ])->count();
	}

	public function createActivity( $model, $parentType = null ) {

		$title = $model->name ?? null;

		$this->triggerActivity($model, NotifyGlobal::TEMPLATE_LOG_CREATE, $title, $parentType);

	}

	public function updateActivity( $model, $parentType = null ) {

		$title = $model->name ?? null;

		$this->triggerActivity($model, NotifyGlobal::TEMPLATE_LOG_UPDATE, $title, $parentType);

	}

	public function deleteActivity( $model, $parentType = null ) {

		$title = $model->name ?? null;

		$this->triggerActivity( $model, NotifyGlobal::TEMPLATE_LOG_DELETE, $title, $parentType );
	}

	// Activity
	private function triggerActivity( $model, $templateSlug, $title, $parentType = null ) {

		$user =	Yii::$app->user->getIdentity();

		$userId		= isset( $user ) ? $user->id : "";
		$firstName	= isset( $user ) ? $user->firstName : "";
		$lastName	= isset( $user ) ? $user->lastName : "";
		$userName	= $firstName . $lastName;
		$modelName	= $model->name ?? '';

		Yii::$app->eventManager->triggerActivity(
			$templateSlug,
			[ 'parentType' => $parentType, 'userName' => $userName, 'modelName' => "<b>$modelName</b>" ],
			[
				'parentId' => $model->id,
				'parentType' => $parentType,
				'userId' => $userId,
				'title' => $title
			]
		);
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

	// ActivityService -----------------------

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
