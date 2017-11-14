<?php
namespace cmsgears\notify\common\services\entities;

// Yii Imports
use Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\models\base\CoreTables;
use cmsgears\notify\common\models\base\NotifyTables;

use cmsgears\notify\common\services\interfaces\entities\IActivityService;

use cmsgears\core\common\services\traits\ResourceTrait;
use cmsgears\notify\common\config\NotifyGlobal;
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

	// ActivityService -----------------------

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

			$search = [ 'title' => "$modelTable.title", 'content' => "$modelTable.content" ];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'title' => "$modelTable.title", 'content' => "$modelTable.content",
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
		$this->triggerActivity($model, NotifyGlobal::TEMPLATE_LOG_DELETE, $title, $parentType);
	}
	
	// Activity
	private function triggerActivity( $model, $templateSlug, $title, $parentType = null ) {

		$user =	Yii::$app->user->getIdentity();
			
		$userId		= isset( $user ) ? $user->id : "";
		$firstName	= isset( $user ) ? $user->firstName : "";
		$lastName	= isset( $user ) ? $user->lastName : "";
		$userName	= $firstName . $lastName;
		
		Yii::$app->eventManager->triggerActivity(
			$templateSlug,
			[  'userName' => $userName, 'modelName' => "<b>$model->name</b>" ],
			[
				'parentId' => $model->id,
				'parentType' => $parentType,
				'userId' =>  $userId,
				'title' => $title
			]
		);
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

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

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
