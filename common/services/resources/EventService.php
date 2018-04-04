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

use cmsgears\notify\common\models\resources\Event;

use cmsgears\notify\common\services\interfaces\resources\IEventService;

use cmsgears\core\common\services\base\ModelResourceService;

use cmsgears\core\common\services\traits\base\NameTypeTrait;
use cmsgears\core\common\services\traits\base\SlugTypeTrait;

/**
 * EventService provide service methods of event model.
 *
 * @since 1.0.0
 */
class EventService extends ModelResourceService implements IEventService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\notify\common\models\resources\Event';

	public static $typed		= true;

	public static $parentType	= NotifyGlobal::TYPE_EVENT;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use NameTypeTrait;
	use SlugTypeTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// EventService -------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$templateTable	= Yii::$app->factory->get( 'templateService' )->getModelTable();
		$userTable		= Yii::$app->factory->get( 'userService' )->getModelTable();

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'id' => [
					'asc' => [ "$modelTable.id" => SORT_ASC ],
					'desc' => [ "$modelTable.id" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Id'
				],
				'template' => [
					'asc' => [ "$templateTable.name" => SORT_ASC ],
					'desc' => [ "$templateTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Id'
				],
	            'user' => [
					'asc' => [ "`$userTable`.`firstName`" => SORT_ASC, "`$userTable`.`lastName`" => SORT_ASC ],
					'desc' => [ "`$userTable`.`firstName`" => SORT_DESC, "`$userTable`.`lastName`" => SORT_DESC ],
					'default' => SORT_DESC,
	                'label' => 'User'
	            ],
				'name' => [
					'asc' => [ "$modelTable.name" => SORT_ASC ],
					'desc' => [ "$modelTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Name'
				],
				'slug' => [
					'asc' => [ "$modelTable.slug" => SORT_ASC ],
					'desc' => [ "$modelTable.slug" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Slug'
				],
				'type' => [
					'asc' => [ "$modelTable.type" => SORT_ASC ],
					'desc' => [ "$modelTable.type" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Type'
				],
				'prcount' => [
					'asc' => [ "$modelTable.preReminderCount" => SORT_ASC ],
					'desc' => [ "$modelTable.preReminderCount" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Pre Reminder Count'
				],
				'printerval' => [
					'asc' => [ "$modelTable.preReminderInterval" => SORT_ASC ],
					'desc' => [ "$modelTable.preReminderInterval" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Pre Reminder Interval'
				],
				'pscount' => [
					'asc' => [ "$modelTable.postReminderCount" => SORT_ASC ],
					'desc' => [ "$modelTable.postReminderCount" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Post Reminder Count'
				],
				'psinterval' => [
					'asc' => [ "$modelTable.postReminderInterval" => SORT_ASC ],
					'desc' => [ "$modelTable.postReminderInterval" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Post Reminder Interval'
				],
				'pruinterval' => [
					'asc' => [ "$modelTable.preIntervalUnit" => SORT_ASC ],
					'desc' => [ "$modelTable.preIntervalUnit" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Pre Interval Interval'
				],
				'psuinterval' => [
					'asc' => [ "$modelTable.postIntervalUnit" => SORT_ASC ],
					'desc' => [ "$modelTable.postIntervalUnit" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Post Interval Interval'
				],
				'status' => [
					'asc' => [ "$modelTable.status" => SORT_ASC ],
					'desc' => [ "$modelTable.status" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Status'
				],
				'admin' => [
					'asc' => [ "$modelTable.admin" => SORT_ASC ],
					'desc' => [ "$modelTable.admin" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Admin'
				],
				'multi' => [
					'asc' => [ "$modelTable.multiUser" => SORT_ASC ],
					'desc' => [ "$modelTable.multiUser" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Multi Users'
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
				],
				'sdate' => [
					'asc' => [ "$modelTable.scheduledAt" => SORT_ASC ],
					'desc' => [ "$modelTable.scheduledAt" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Scheduled At'
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
		$type	= Yii::$app->request->getQueryParam( 'type' );
		$status	= Yii::$app->request->getQueryParam( 'status' );
		$multi	= Yii::$app->request->getQueryParam( 'multi' );

		// Filter - Type
		if( isset( $type ) ) {

			$config[ 'conditions' ][ "$modelTable.type" ] = $type;
		}

		// Filter - Status
		if( isset( $status ) && isset( $modelClass::$urlRevStatusMap[ $status ] ) ) {

			$config[ 'conditions' ][ "$modelTable.status" ]	= $modelClass::$urlRevStatusMap[ $status ];
		}

		// Filter - Multi
		if( isset( $multi ) ) {

			$config[ 'conditions' ][ "$modelTable.multiUser" ] = true;
		}

		// Searching --------

		$searchCol = Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [
				'name' => "$modelTable.name",
				'title' => "$modelTable.title",
				'desc' => "$modelTable.description",
				'content' => "$modelTable.content" ];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'name' => "$modelTable.name",
			'title' => "$modelTable.title",
			'desc' => "$modelTable.description",
			'content' => "$modelTable.content",
			'type' => "$modelTable.type",
			'status' => "$modelTable.status",
			'multi' => "$modelTable.multiUser"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	public function getPageForAdmin() {

		$modelTable	= $this->getModelTable();

		return $this->getPage( [ 'conditions' => [ "$modelTable.admin" => true ] ] );
	}

	public function getPageByUserId( $userId ) {

		$modelTable	= $this->getModelTable();

		return $this->getPage( [ 'conditions' => [ "$modelTable.userId" => $userId ] ] );
	}

	public function getPageByParent( $parentId, $parentType, $admin = false ) {

		$modelTable	= $this->getModelTable();

		return $this->getPage( [ 'conditions' => [ "$modelTable.parentId" => $parentId, "$modelTable.parentType" => $parentType, "$modelTable.admin" => $admin ] ] );
	}

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function update( $model, $config = [] ) {

		return parent::update( $model, [
			'attributes' => [ 'title', 'description', 'content' ]
		]);
	}

	public function updateStatus( $model, $status ) {

		$model->status = $status;

		return parent::update( $model, [
			'attributes' => [ 'status' ]
		]);
	}

	public function trash( $model ) {

		return $this->updateStatus( $model, Event::STATUS_TRASH );
	}

	// Delete -------------

	// Bulk ---------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'status': {

				switch( $action ) {

					case 'trash': {

						$this->trash( $model );

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

	// EventService -------------------

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
