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

use cmsgears\core\common\services\base\ModelResourceService;

use cmsgears\notify\common\services\traits\base\NotifyTrait;
use cmsgears\notify\common\services\traits\base\ToggleTrait;

/**
 * EventReminderService provide service methods of event reminder.
 *
 * @since 1.0.0
 */
class EventReminderService extends ModelResourceService implements IEventReminderService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\notify\common\models\resources\EventReminder';

	public static $parentType	= NotifyGlobal::TYPE_REMINDER;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

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
			'defaultOrder' => [ 'sdate' => 'SORT_ASC' ]
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
		$cons	= Yii::$app->request->getQueryParam( 'consumed' );
		$trash	= Yii::$app->request->getQueryParam( 'trash' );

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

			$config[ 'conditions' ][ "$modelTable.trash" ] = true;
		}

		// Searching --------

		$searchCol	= Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [
				'title' => "$modelTable.title",
				'desc' => "$modelTable.description",
				'content' => "$modelTable.content"
			];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'title' => "$modelTable.title",
			'desc' => "$modelTable.description",
			'content' => "$modelTable.content",
			'consumed' => "$modelTable.consumed",
			'trash' => "$modelTable.trash",
			'scheduledAt' => "$modelTable.scheduledAt"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	public function getPageForAdmin() {

		$modelTable	= $this->getModelTable();

		return $this->getPage( [ 'conditions' => [ "NOW() > $modelTable.scheduledAt", "$modelTable.admin" => true ] ] );
	}

	public function getPageByUserId( $userId ) {

		$modelTable	= $this->getModelTable();

		return $this->getPage( [ 'conditions' => [ "NOW() > $modelTable.scheduledAt", "$modelTable.userId" => $userId ] ] );
	}

	public function getPageByParent( $parentId, $parentType, $admin = false ) {

		$modelTable	= $this->getModelTable();

		return $this->getPage( [ 'conditions' => [ "NOW() > $modelTable.scheduledAt", "$modelTable.parentId" => $parentId, "$modelTable.parentType" => $parentType, "$modelTable.admin" => $admin ] ] );
	}

	// Read ---------------

	// Read - Models ---

	// TODO: Check for options to show collaborative irrespective of siteId

	public function getRecent( $limit = 5, $config = [] ) {

		$modelClass	= static::$modelClass;

		$siteId = Yii::$app->core->siteId;

		return $modelClass::find()
			->where( $config[ 'conditions' ] )
			->andWhere( "scheduledAt <= NOW()" )
			->andWhere( [ 'siteId' => $siteId ] )
			->limit( $limit )->orderBy( 'scheduledAt ASC' )
			->all();
	}

	public function getRecentByParent( $parentId, $parentType, $limit = 5, $config = [] ) {

		$modelClass	= static::$modelClass;

		$siteId = Yii::$app->core->siteId;

		return $modelClass::queryByParent( $parentId, $parentType )
			->andWhere( $config[ 'conditions' ] )
			->andWhere( "scheduledAt <= NOW()" )
			->andWhere( [ 'siteId' => $siteId ] )
			->limit( $limit )->orderBy( 'scheduledAt ASC' )
			->all();
	}

	public function getCount( $consumed = false, $admin = false ) {

		$modelClass	= static::$modelClass;

		return $modelClass::find()
			->where( 'scheduledAt <= NOW() AND consumed=:consumed AND admin=:admin', [ ':consumed' => $consumed, ':admin' => $admin ] )
			->count();
	}

	public function getUserCount( $userId, $consumed = false, $admin = false ) {

		$modelClass	= static::$modelClass;

		return $modelClass::queryByUserId( $userId )
			->andWhere( 'scheduledAt <= NOW() AND consumed=:consumed AND admin=:admin', [ ':consumed' => $consumed, ':admin' => $admin ] )
			->count();
	}

	public function getCountByParent( $parentId, $parentType, $consumed = false, $admin = false ) {

		$modelClass	= static::$modelClass;

		$siteId = Yii::$app->core->siteId;

		return $modelClass::queryByParent( $parentId, $parentType )
			->andWhere( 'scheduledAt <= NOW() AND consumed=:consumed AND admin=:admin', [ ':consumed' => $consumed, ':admin' => $admin ] )
			->andWhere( [ 'siteId' => $siteId ] )
			->count();
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function update( $model, $config = [] ) {

		return parent::update( $model, [
			'attributes' => [ 'title', 'content' ]
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

	public function applyBulkByUserId( $column, $action, $target, $userId ) {

		foreach( $target as $id ) {

			$model = $this->getById( $id );

			if( isset( $model ) && $model->userId == $userId ) {

				$this->applyBulk( $model, $column, $action, $target );
			}
		}
	}

	public function applyBulkByAdmin( $column, $action, $target ) {

		foreach( $target as $id ) {

			$model = $this->getById( $id );

			if( isset( $model ) && $model->admin ) {

				$this->applyBulk( $model, $column, $action, $target );
			}
		}
	}

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
