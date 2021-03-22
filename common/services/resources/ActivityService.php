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
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\notify\common\services\interfaces\resources\IActivityService;

use cmsgears\core\common\services\traits\base\MultisiteTrait;

use cmsgears\notify\common\services\traits\base\BulkTrait;
use cmsgears\notify\common\services\traits\base\NotifyTrait;
use cmsgears\notify\common\services\traits\base\ToggleTrait;

/**
 * ActivityService provide service methods of activity model.
 *
 * @since 1.0.0
 */
class ActivityService extends \cmsgears\core\common\services\base\ModelResourceService implements IActivityService {

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

	use BulkTrait;
	use MultisiteTrait;
	use NotifyTrait;
	use ToggleTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ActivityService -----------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$searchParam	= $config[ 'search-param' ] ?? 'keywords';
		$searchColParam	= $config[ 'search-col-param' ] ?? 'search';

		$defaultSort = isset( $config[ 'defaultSort' ] ) ? $config[ 'defaultSort' ] : [ 'id' => SORT_DESC ];

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$userTable = Yii::$app->factory->get( 'userService' )->getModelTable();

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
					'asc' => [ "$userTable.name" => SORT_ASC ],
					'desc' => [ "$userTable.name" => SORT_DESC ],
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
				'ip' => [
					'asc' => [ "$modelTable.ipNum" => SORT_ASC ],
					'desc' => [ "$modelTable.ipNum" => SORT_DESC ],
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
			'defaultOrder' => $defaultSort
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
		$type	= Yii::$app->request->getQueryParam( 'type' );
		$cons	= Yii::$app->request->getQueryParam( 'consumed' );
		$trash	= Yii::$app->request->getQueryParam( 'trash' );

		// Filter - Type
		if( isset( $type ) ) {

			$config[ 'conditions' ][ "$modelTable.type" ] = $type;
		}

		// Filter - Consumed
		if( isset( $cons ) ) {

			switch( $cons ) {

				case 'new': {

					$config[ 'conditions' ][ "$modelTable.consumed" ] = false;

					break;
				}
				case 'read': {

					$config[ 'conditions' ][ "$modelTable.consumed" ] = true;

					break;
				}
			}
		}

		// Filter - Trash
		if( isset( $trash ) ) {

			switch( $trash ) {

				case 'trash': {

					$config[ 'conditions' ][ "$modelTable.trash" ] = true;

					break;
				}
				case 'active': {

					$config[ 'conditions' ][ "$modelTable.trash" ] = false;

					break;
				}
			}
		}

		// Searching --------

		$searchCol		= Yii::$app->request->getQueryParam( $searchColParam );
		$keywordsCol	= Yii::$app->request->getQueryParam( $searchParam );

		$search = [
			'title' => "$modelTable.title",
			'desc' => "$modelTable.description",
			'content' => "$modelTable.content"
		];

		if( isset( $searchCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search[ $searchCol ];
		}
		else if( isset( $keywordsCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search;
		}

		// Reporting --------

		$config[ 'report-col' ]	= $config[ 'report-col' ] ?? [
			'title' => "$modelTable.title",
			'desc' => "$modelTable.description",
			'content' => "$modelTable.content",
			'type' => "$modelTable.type",
			'consumed' => "$modelTable.consumed",
			'trash' => "$modelTable.trash"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	public function getPageByUserId( $userId, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$modelTable	= $this->getModelTable();

		$config[ 'conditions' ][ "$modelTable.userId" ] = $userId;

		// Show only user activities to frontend
		if( !$admin ) {

			$config[ 'conditions' ][ "$modelTable.type" ] = CoreGlobal::TYPE_USER;
		}

		return $this->getPage( $config );
	}

	public function getPageByParent( $parentId, $parentType, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$modelTable	= $this->getModelTable();

		$config[ 'conditions' ][ "$modelTable.parentId" ]	= $parentId;
		$config[ 'conditions' ][ "$modelTable.parentType" ] = $parentType;
		$config[ 'conditions' ][ "$modelTable.admin" ]		= $admin;

		// Show only user activities to frontend
		if( !$admin ) {

			$config[ 'conditions' ][ "$modelTable.type" ] = CoreGlobal::TYPE_USER;
		}

		return $this->getPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	public function getNotifyRecentByUserId( $userId, $limit = 5, $config = [] ) {

		$admin		= isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : true;
		$siteId		= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;
		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$query = $modelClass::queryByUserId( $userId )->andWhere( 'admin=:admin', [ ':admin' => $admin ] );

		if( !$ignoreSite ) {

			$query->andWhere( 'siteId=:siteId', [ ':siteId' => $siteId ] );
		}

		// Show only user activities to frontend
		if( !$admin ) {

			$query->andWhere( [ "$modelTable.type" => CoreGlobal::TYPE_USER ] );
		}

		$query->limit( $limit )->orderBy( 'createdAt ASC' );

		return $query->all();
	}

	public function getNotifyRecentByParent( $parentId, $parentType, $limit = 5, $config = [] ) {

		$admin		= isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : true;
		$siteId		= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;
		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$query = $modelClass::queryByParent( $parentId, $parentType )->andWhere( 'admin=:admin', [ ':admin' => $admin ] );

		if( !$ignoreSite ) {

			$query->andWhere( 'siteId=:siteId', [ ':siteId' => $siteId ] );
		}

		// Show only user activities to frontend
		if( !$admin ) {

			$query->andWhere( [ "$modelTable.type" => CoreGlobal::TYPE_USER ] );
		}

		$query->limit( $limit )->orderBy( 'createdAt ASC' );

		return $query->all();
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	public function getNotifyCountByUserId( $userId, $config = [] ) {

		$consumed	= isset( $config[ 'consumed' ] ) ? $config[ 'consumed' ] : false;
		$admin		= isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : true;
		$siteId		= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;
		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$query = $modelClass::queryByUserId( $userId )->andWhere( 'consumed=:consumed AND admin=:admin', [ ':consumed' => $consumed, ':admin' => $admin ] );

		if( !$ignoreSite ) {

			$query->andWhere( 'siteId=:siteId', [ ':siteId' => $siteId ] );
		}

		// Show only user activities to frontend
		if( !$admin ) {

			$query->andWhere( [ "$modelTable.type" => CoreGlobal::TYPE_USER ] );
		}

		return $query->count();
	}

	public function getNotifyCountByParent( $parentId, $parentType, $config = [] ) {

		$consumed	= isset( $config[ 'consumed' ] ) ? $config[ 'consumed' ] : false;
		$admin		= isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : true;
		$siteId		= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;
		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$query = $modelClass::queryByParent( $parentId, $parentType )->andWhere( 'consumed=:consumed AND admin=:admin', [ ':consumed' => $consumed, ':admin' => $admin ] );

		if( !$ignoreSite ) {

			$query->andWhere( 'siteId=:siteId', [ ':siteId' => $siteId ] );
		}

		// Show only user activities to frontend
		if( !$admin ) {

			$query->andWhere( [ "$modelTable.type" => CoreGlobal::TYPE_USER ] );
		}

		return $query->count();
	}

	// Create -------------

	public function create( $model, $config = [] ) {

		$model->agent	= Yii::$app->request->userAgent;
		$model->ip		= Yii::$app->request->userIP;

		return parent::create( $model, $config );
	}

	public function createByParams( $params = [], $config = [] ) {

		$params[ 'link' ]		= isset( $params[ 'link' ] ) ? $params[ 'link' ] : null;
		$params[ 'admin' ]		= isset( $params[ 'admin' ] ) ? $params[ 'admin' ] : false;
		$params[ 'adminLink' ]	= isset( $params[ 'adminLink' ] ) ? $params[ 'adminLink' ] : null;

		return parent::createByParams( $params, $config );
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		return parent::update( $model, [
			'attributes' => [ 'title', 'description', 'content' ]
		]);
	}

	// Delete -------------

	public function deleteByUserId( $userId, $config = [] ) {

		$modelClass = self::$modelClass;

		$modelClass::deleteByUserId( $userId );
	}

	// Bulk ---------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

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

				switch( $action ) {

					case 'trash': {

						$this->markTrash( $model );

						break;
					}
					case 'untrash': {

						$this->unTrash( $model );

						break;
					}
				}

				break;
			}
			/*case 'model': {

				switch( $action ) {

					case 'delete': {

						$this->delete( $model );

						break;
					}
				}

				break;
			}*/
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
