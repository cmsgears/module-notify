<?php
namespace cmsgears\notify\common\services\entities;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\notify\common\config\NotifyGlobal;

use cmsgears\notify\common\models\base\NotifyTables;
use cmsgears\notify\common\models\entities\Reminder;

use cmsgears\notify\common\services\interfaces\entities\IReminderService;

/**
 * The class ReminderService is base class to perform database activities for Reminder Entity.
 */
class ReminderService extends \cmsgears\core\common\services\base\EntityService implements IReminderService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\notify\common\models\entities\Reminder';

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

	// ReminderService -----------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$modelTable	= self::$modelTable;

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'event' => [
					'asc' => [ 'eventId' => SORT_ASC ],
					'desc' => ['eventId' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Event'
				],
				'title' => [
					'asc' => [ 'title' => SORT_ASC ],
					'desc' => ['title' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Title'
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
				'sdate' => [
					'asc' => [ 'scheduledAt' => SORT_ASC ],
					'desc' => ['scheduledAt' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Scheduled At'
				]
			],
			'defaultOrder' => [ 'sdate' => 'SORT_ASC' ]
		]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
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

			$config[ 'search-col' ] = $searchCol;
		}

		// Reporting --------

		$config[ 'report-col' ]	= [ 'title', 'content', 'scheduledAt' ];

		// Result -----------

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

	// Read ---------------

	// Read - Models ---

	public function getRecent( $limit = 5, $config = [] ) {

		return Reminder::find()->where( $config[ 'conditions' ] )->andWhere( "scheduledAt <= NOW()" )->limit( $limit )->orderBy( 'scheduledAt ASC' )->all();
	}

	public function getCount( $consumed = false, $admin = false ) {

		return Reminder::find()->where( 'scheduledAt <= NOW() AND consumed=:consumed AND admin=:admin', [ ':consumed' => $consumed, ':admin' => $admin ] )->count();
	}

	public function getUserCount( $userId, $consumed = false, $admin = false ) {

		return Reminder::queryByUserId( $userId )->andWhere( 'scheduledAt <= NOW() AND consumed=:consumed AND admin=:admin', [ ':consumed' => $consumed, ':admin' => $admin ] )->count();
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

	protected function applyBulk( $model, $column, $action, $target ) {

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

	// ReminderService -------------------

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
