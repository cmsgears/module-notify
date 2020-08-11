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

use cmsgears\notify\common\services\interfaces\resources\IEventReminderService;

use cmsgears\core\common\services\traits\base\MultisiteTrait;

use cmsgears\notify\common\services\traits\base\NotifyTrait;
use cmsgears\notify\common\services\traits\base\ToggleTrait;

/**
 * EventReminderService provide service methods of event reminder.
 *
 * @since 1.0.0
 */
class EventReminderService extends \cmsgears\core\common\services\base\ModelResourceService implements IEventReminderService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\notify\common\models\resources\EventReminder';

	public static $parentType = NotifyGlobal::TYPE_REMINDER;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use MultisiteTrait;
	use NotifyTrait;
	use ToggleTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// EventReminderService ------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$searchParam	= $config[ 'search-param' ] ?? 'keywords';
		$searchColParam	= $config[ 'search-col-param' ] ?? 'search';

		$defaultSort = isset( $config[ 'defaultSort' ] ) ? $config[ 'defaultSort' ] : [ 'sdate' => SORT_DESC ];

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$eventTable	= Yii::$app->factory->get( 'eventService' )->getModelTable();
		$userTable	= Yii::$app->factory->get( 'userService' )->getModelTable();

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'id' => [
					'asc' => [ "$modelTable.id" => SORT_ASC ],
					'desc' => [ "$modelTable.id" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Id'
				],
				'event' => [
					'asc' => [ "$eventTable.name" => SORT_ASC ],
					'desc' => [ "$eventTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Event'
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
				'sdate' => [
					'asc' => [ "$modelTable.scheduledAt" => SORT_ASC ],
					'desc' => [ "$modelTable.scheduledAt" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Scheduled At'
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

			$config[ 'search-col' ] = $search[ $searchCol ];
		}
		else if( isset( $keywordsCol ) ) {

			$config[ 'search-col' ] = $search;
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'title' => "$modelTable.title",
			'desc' => "$modelTable.description",
			'content' => "$modelTable.content",
			'type' => "$modelTable.type",
			'consumed' => "$modelTable.consumed",
			'trash' => "$modelTable.trash",
			'sdate' => "$modelTable.scheduledAt"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	public function getPageForAdmin( $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : true;

		$modelTable	= $this->getModelTable();

		$config[ 'conditions' ][] = "$modelTable.scheduledAt <= NOW()";

		$config[ 'conditions' ][ "$modelTable.admin" ] = $admin;

		return $this->getPage( $config );
	}

	public function getPageByUserId( $userId, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$modelTable	= $this->getModelTable();

		$config[ 'conditions' ][] = "$modelTable.scheduledAt <= NOW()";

		$config[ 'conditions' ][ "$modelTable.admin" ]	= $admin;
		$config[ 'conditions' ][ "$modelTable.userId" ] = $userId;

		return $this->getPage( $config );
	}

	public function getPageByParent( $parentId, $parentType, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$modelTable	= $this->getModelTable();

		$config[ 'conditions' ][] = "$modelTable.scheduledAt <= NOW()";

		$config[ 'conditions' ][ "$modelTable.admin" ]		= $admin;
		$config[ 'conditions' ][ "$modelTable.parentId" ]	= $parentId;
		$config[ 'conditions' ][ "$modelTable.parentType" ]	= $parentType;

		return $this->getPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	public function getNotifyRecent( $limit = 5, $config = [] ) {

		$admin		= isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : true;
		$siteId		= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;
		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		$modelClass	= static::$modelClass;

		$query = $modelClass::find()->where( 'scheduledAt <= NOW() AND admin=:admin', [ ':admin' => $admin ] );

		if( !$ignoreSite ) {

			$query->andWhere( 'siteId=:siteId', [ ':siteId' => $siteId ] );
		}

		$query->limit( $limit )->orderBy( 'scheduledAt DESC' );

		return $query->all();
	}

	public function getNotifyRecentByUserId( $userId, $limit = 5, $config = [] ) {

		$admin		= isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : true;
		$siteId		= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;
		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		$modelClass	= static::$modelClass;

		$query = $modelClass::queryByUserId( $userId )->where( 'scheduledAt <= NOW() AND admin=:admin', [ ':admin' => $admin ] );

		if( !$ignoreSite ) {

			$query->andWhere( 'siteId=:siteId', [ ':siteId' => $siteId ] );
		}

		$query->limit( $limit )->orderBy( 'scheduledAt DESC' );

		return $query->all();
	}

	public function getNotifyRecentByParent( $parentId, $parentType, $limit = 5, $config = [] ) {

		$admin		= isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : true;
		$siteId		= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;
		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		$modelClass	= static::$modelClass;

		$query = $modelClass::queryByParent( $parentId, $parentType )->where( 'scheduledAt <= NOW() AND admin=:admin', [ ':admin' => $admin ] );

		if( !$ignoreSite ) {

			$query->andWhere( 'siteId=:siteId', [ ':siteId' => $siteId ] );
		}

		$query->limit( $limit )->orderBy( 'scheduledAt DESC' );

		return $query->all();
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	public function getNotifyCount( $config = [] ) {

		$consumed	= isset( $config[ 'consumed' ] ) ? $config[ 'consumed' ] : false;
		$admin		= isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : true;
		$siteId		= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;
		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		$modelClass	= static::$modelClass;

		$query = $modelClass::find()->where( 'scheduledAt <= NOW() AND consumed=:consumed AND admin=:admin', [ ':consumed' => $consumed, ':admin' => $admin ] );

		if( !$ignoreSite ) {

			$query->andWhere( 'siteId=:siteId', [ ':siteId' => $siteId ] );
		}

		return $query->count();
	}

	public function getNotifyCountByUserId( $userId, $config = [] ) {

		$consumed	= isset( $config[ 'consumed' ] ) ? $config[ 'consumed' ] : false;
		$admin		= isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : true;
		$siteId		= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;
		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		$modelClass	= static::$modelClass;

		$query = $modelClass::queryByUserId( $userId )->where( 'scheduledAt <= NOW() AND consumed=:consumed AND admin=:admin', [ ':consumed' => $consumed, ':admin' => $admin ] );

		if( !$ignoreSite ) {

			$query->andWhere( 'siteId=:siteId', [ ':siteId' => $siteId ] );
		}

		return $query->count();
	}

	public function getNotifyCountByParent( $parentId, $parentType, $config = [] ) {

		$consumed	= isset( $config[ 'consumed' ] ) ? $config[ 'consumed' ] : false;
		$admin		= isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : true;
		$siteId		= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;
		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		$modelClass	= static::$modelClass;

		$query = $modelClass::queryByParent( $parentId, $parentType )->where( 'scheduledAt <= NOW() AND consumed=:consumed AND admin=:admin', [ ':consumed' => $consumed, ':admin' => $admin ] );

		if( !$ignoreSite ) {

			$query->andWhere( 'siteId=:siteId', [ ':siteId' => $siteId ] );
		}

		return $query->count();
	}

	// Create -------------

	// Update -------------

	public function update( $model, $config = [] ) {

		return parent::update( $model, [
			'attributes' => [ 'title', 'description', 'content' ]
		]);
	}

	// Delete -------------

	public function deleteByEventId( $eventId, $config = [] ) {

		$modelClass = self::$modelClass;

		$modelClass::deleteByEventId( $eventId );
	}

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
					case 'active': {

						$this->unTrash( $model );

						break;
					}
				}

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

	// EventReminderService ------------------

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
