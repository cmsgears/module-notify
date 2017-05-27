<?php
namespace cmsgears\notify\common\services\entities;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\notify\common\config\NotifyGlobal;

use cmsgears\notify\common\models\base\NotifyTables;
use cmsgears\notify\common\models\entities\Activity;

use cmsgears\notify\common\services\interfaces\entities\IActivityService;

use cmsgears\core\common\services\traits\ResourceTrait;

/**
 * The class ActivityService is base class to perform database activities for Activity Entity.
 */
class ActivityService extends \cmsgears\core\common\services\base\EntityService implements IActivityService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\notify\common\models\entities\Activity';

	public static $modelTable	= NotifyTables::TABLE_ACTIVITY;

	public static $parentType	= null;

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

		$modelTable	= self::$modelTable;

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'user' => [
					'asc' => [ 'user' => SORT_ASC ],
					'desc' => [ 'user' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'User'
				],
				'title' => [
					'asc' => [ 'title' => SORT_ASC ],
					'desc' => [ 'title' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Title'
				],
				'type' => [
					'asc' => [ 'type' => SORT_ASC ],
					'desc' => [ 'type' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Type'
				],
				'agent' => [
					'asc' => [ 'agent' => SORT_ASC ],
					'desc' => [ 'agent' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Agent'
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
				]
			],
			'defaultOrder' => [ 'cdate' => 'SORT_ASC' ]
		]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		// Filters ----------

		// Searching --------

		$searchCol	= Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$config[ 'search-col' ] = $searchCol;
		}

		// Reporting --------

		$config[ 'report-col' ]	= [ 'title', 'content', 'createdAt' ];

		// Result -----------

		return parent::findPage( $config );
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

		$model->agent	= Yii::$app->request->userAgent;
		$model->ip		= Yii::$app->request->userIP;

		return parent::create( $model, $config );
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		return parent::update( $model, [
			'attributes' => [ 'title', 'content' ]
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

	protected function applyBulk( $model, $column, $action, $target ) {

		switch( $column ) {

			case 'id': {

				$this->delete( $model );

				break;
			}
		}
	}

	// Delete -------------

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
