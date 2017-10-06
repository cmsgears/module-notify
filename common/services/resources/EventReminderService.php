<?php
namespace cmsgears\notify\common\services\resources;

// Yii Imports
use Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\notify\common\config\NotifyGlobal;

use cmsgears\notify\common\models\base\NotifyTables;
use cmsgears\notify\common\models\resources\EventReminder;

use cmsgears\notify\common\services\interfaces\resources\IEventReminderService;

/**
 * The class EventReminderService is base class to perform database activities for EventReminder Entity.
 */
class EventReminderService extends \cmsgears\core\common\services\base\EntityService implements IEventReminderService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\notify\common\models\resources\EventReminder';

	public static $modelTable	= NotifyTables::TABLE_EVENT_REMINDER;

	public static $parentType	= NotifyGlobal::TYPE_REMINDER;

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

	// EventReminderService ------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$modelTable	= self::$modelTable;

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'event' => [
					'asc' => [ "$modelTable.eventId" => SORT_ASC ],
					'desc' => [ "$modelTable.eventId" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Event'
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
			'scheduledAt' => "$modelTable.scheduledAt"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	public function getPageForAdmin() {

		$modelTable	= self::$modelTable;

		return $this->getPage( [ 'conditions' => [ "NOW() > $modelTable.scheduledAt", "$modelTable.admin" => true ] ] );
	}

	public function getPageByUserId( $userId ) {

		$modelTable	= self::$modelTable;

		return $this->getPage( [ 'conditions' => [ "NOW() > $modelTable.scheduledAt", "$modelTable.userId" => $userId ] ] );
	}

	// Read ---------------

	// Read - Models ---

	public function getRecent( $limit = 5, $config = [] ) {

		return EventReminder::find()->where( $config[ 'conditions' ] )->andWhere( "scheduledAt <= NOW()" )->limit( $limit )->orderBy( 'scheduledAt ASC' )->all();
	}

	public function getCount( $consumed = false, $admin = false ) {

		return EventReminder::find()->where( 'scheduledAt <= NOW() AND consumed=:consumed AND admin=:admin', [ ':consumed' => $consumed, ':admin' => $admin ] )->count();
	}

	public function getUserCount( $userId, $consumed = false, $admin = false ) {

		return EventReminder::queryByUserId( $userId )->andWhere( 'scheduledAt <= NOW() AND consumed=:consumed AND admin=:admin', [ ':consumed' => $consumed, ':admin' => $admin ] )->count();
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

	public function applyBulkByUserId( $column, $action, $target, $userId ) {

		foreach ( $target as $id ) {

			$reminder = $this->getById( $id );

			if( isset( $reminder ) && $reminder->userId == $userId ) {

				$this->applyBulk( $reminder, $column, $action, $target );
			}
		}
	}

	public function applyBulkByAdmin( $column, $action, $target ) {

		foreach ( $target as $id ) {

			$reminder = $this->getById( $id );

			if( isset( $reminder ) && $reminder->admin ) {

				$this->applyBulk( $reminder, $column, $action, $target );
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
		}
	}

	// Delete -------------

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
