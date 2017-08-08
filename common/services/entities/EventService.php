<?php
namespace cmsgears\notify\common\services\entities;

// Yii Imports
use Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\notify\common\config\NotifyGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\notify\common\models\base\NotifyTables;
use cmsgears\notify\common\models\entities\Event;

use cmsgears\notify\common\services\interfaces\entities\IEventService;

use cmsgears\core\common\services\traits\NameTypeTrait;
use cmsgears\core\common\services\traits\ResourceTrait;
use cmsgears\core\common\services\traits\SlugTypeTrait;

/**
 * The class EventService is base class to perform database activities for Event Entity.
 */
class EventService extends \cmsgears\core\common\services\base\EntityService implements IEventService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\notify\common\models\entities\Event';

	public static $modelTable	= NotifyTables::TABLE_EVENT;

	public static $typed		= true;

	public static $parentType	= NotifyGlobal::TYPE_EVENT;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use NameTypeTrait;
	use ResourceTrait;
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

		$modelClass		= static::$modelClass;
		$modelTable		= static::$modelTable;
		$userTable		= CoreTables::TABLE_USER;

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
	            'user' => [
					'asc' => [ "`$userTable`.`firstName`" => SORT_ASC, "`$userTable`.`lastName`" => SORT_ASC ],
					'desc' => [ "`$userTable`.`firstName`" => SORT_DESC, "`$userTable`.`lastName`" => SORT_DESC ],
					'default' => SORT_DESC,
	                'label' => 'User'
	            ],
				'name' => [
					'asc' => [ 'name' => SORT_ASC ],
					'desc' => [ 'name' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Name'
				],
				'slug' => [
					'asc' => [ 'slug' => SORT_ASC ],
					'desc' => [ 'slug' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Slug'
				],
				'type' => [
					'asc' => [ 'type' => SORT_ASC ],
					'desc' => [ 'type' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Type'
				],
				'prcount' => [
					'asc' => [ 'preReminderCount' => SORT_ASC ],
					'desc' => [ 'preReminderCount' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Pre Reminder Count'
				],
				'printerval' => [
					'asc' => [ 'preReminderInterval' => SORT_ASC ],
					'desc' => [ 'preReminderInterval' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Pre Reminder Interval'
				],
				'pscount' => [
					'asc' => [ 'postReminderCount' => SORT_ASC ],
					'desc' => [ 'postReminderCount' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Post Reminder Count'
				],
				'psinterval' => [
					'asc' => [ 'postReminderInterval' => SORT_ASC ],
					'desc' => [ 'postReminderInterval' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Post Reminder Interval'
				],
				'pruinterval' => [
					'asc' => [ 'preIntervalUnit' => SORT_ASC ],
					'desc' => [ 'preIntervalUnit' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Pre Interval Interval'
				],
				'psuinterval' => [
					'asc' => [ 'postIntervalUnit' => SORT_ASC ],
					'desc' => [ 'postIntervalUnit' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Post Interval Interval'
				],
				'status' => [
					'asc' => [ 'status' => SORT_ASC ],
					'desc' => [ 'status' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Status'
				],
				'multi' => [
					'asc' => [ 'multiUser' => SORT_ASC ],
					'desc' => [ 'multiUser' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Multi Users'
				],
				'cdate' => [
					'asc' => [ 'createdAt' => SORT_ASC ],
					'desc' => [ 'createdAt' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Created At'
				],
				'udate' => [
					'asc' => [ 'modifiedAt' => SORT_ASC ],
					'desc' => [ 'modifiedAt' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Updated At'
				],
				'sdate' => [
					'asc' => [ 'scheduledAt' => SORT_ASC ],
					'desc' => [ 'scheduledAt' => SORT_DESC ],
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

		// Filter - Status
		$status	= Yii::$app->request->getQueryParam( 'status' );

		if( isset( $status ) && isset( $modelClass::$urlRevStatusMap[ $status ] ) ) {

			$config[ 'conditions' ][ "$modelTable.status" ]	= $modelClass::$urlRevStatusMap[ $status ];
		}

		// Filter - Multi
		$multi	= Yii::$app->request->getQueryParam( 'multi' );

		if( isset( $multi ) ) {

			$config[ 'conditions' ][ "$modelTable.multiUser" ]	= true;
		}

		// Searching --------

		$searchCol	= Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [ 'name' => "$modelTable.name", 'slug' => "$modelTable.slug", 'desc' => "$modelTable.description", 'content' => "$modelTable.content" ];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'name' => "$modelTable.name", 'slug' => "$modelTable.slug", 'desc' => "$modelTable.description", 'content' => "$modelTable.content",
			'status' => "$modelTable.status", 'multi' => "$modelTable.multiUser"
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
	public function getNewEvents() {

		$modelClass	= static::$modelClass;

		return $modelClass::findNewEvents();
	}

	public function getByParentId( $parentId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByParentId( $parentId );
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function updateStatus( $model, $status ) {

		$model->status = $status;

		return parent::update( $model, [
			'attributes' => [ 'status' ]
		]);
	}

	public function trash( $model ) {

		return $this->updateStatus( $model, Event::STATUS_TRASH );
	}

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'status': {

				switch( $action ) {

					case 'trash': {

						$model->status = Event::STATUS_TRASH;

						$model->update();

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

	// Delete -------------

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
