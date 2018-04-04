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

use cmsgears\notify\common\models\resources\Announcement;

use cmsgears\notify\common\services\interfaces\resources\IAnnouncementService;

use cmsgears\core\common\services\base\ModelResourceService;

/**
 * AnnouncementService provide service methods of announcement model.
 *
 * @since 1.0.0
 */
class AnnouncementService extends ModelResourceService implements IAnnouncementService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\notify\common\models\resources\Announcement';

	public static $parentType	= NotifyGlobal::TYPE_ANNOUNCEMENT;

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

	// AnnouncementService -------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'id' => [
					'asc' => [ "$modelTable.id" => SORT_ASC ],
					'desc' => [ "$modelTable.id" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Id'
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
				'status' => [
					'asc' => [ "$modelTable.status" => SORT_ASC ],
					'desc' => [ "$modelTable.status" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Status'
				],
				'access' => [
					'asc' => [ "$modelTable.access" => SORT_ASC ],
					'desc' => [ "$modelTable.access" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Access'
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
		$access	= Yii::$app->request->getQueryParam( 'access' );

		// Filter - Type
		if( isset( $type ) ) {

			$config[ 'conditions' ][ "$modelTable.type" ] = $type;
		}

		// Filter - Status
		if( isset( $status ) && isset( $modelClass::$urlRevStatusMap[ $status ] ) ) {

			$config[ 'conditions' ][ "$modelTable.status" ]	= $modelClass::$urlRevStatusMap[ $status ];
		}

		// Filter - Access
		if( isset( $access ) && isset( $modelClass::$urlRevAccessMap[ $access ] ) ) {

			$config[ 'conditions' ][ "$modelTable.access" ]	= $modelClass::$urlRevAccessMap[ $access ];
		}

		// Searching --------

		$searchCol = Yii::$app->request->getQueryParam( 'search' );

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
			'type' => "$modelTable.type"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	/**
	 * Returns the data provider to show admin announcements. The results also include
	 * application announcements that need admin intervention.
	 *
	 * @return \cmsgears\core\common\data\ActiveDataProvider
	 */
	public function getPageForAdmin() {

		$modelTable	= $this->getModelTable();

		return $this->getPage( [ 'conditions' => [ "$modelTable.access >=" . Announcement::ACCESS_APP ] ] );
	}

	public function getPageByParent( $parentId, $parentType, $admin = false ) {

		$modelTable	= $this->getModelTable();

		return $this->getPage( [ 'conditions' => [ "$modelTable.parentId" => $parentId, "$modelTable.parentType" => $parentType, "$modelTable.admin" => $admin ] ] );
	}

	// Read ---------------

	// Read - Models ---

	/**
	 * It returns the most recent announcements that can be displayed on Admin.
	 *
	 * @param integer $limit
	 * @param array $config
	 * @return \cmsgears\notify\common\models\resources\Announcement
	 */
	public function getRecentByAdmin( $limit = 5, $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$siteId = Yii::$app->core->siteId;

		$config[ 'conditions' ][] = "$modelTable.access >=" . Announcement::ACCESS_ADMIN;

		return $modelClass::find()->where( $config[ 'conditions' ] )->andWhere( [ 'siteId' => $siteId ] )->limit( $limit )->orderBy( 'createdAt DESC' )->all();
	}

	public function getRecentByParent( $parentId, $parentType, $limit = 5, $config = [] ) {

		$modelClass	= static::$modelClass;

		$siteId = Yii::$app->core->siteId;

		return $modelClass::queryByParent( $parentId, $parentType )->andWhere( $config[ 'conditions' ] )->limit( $limit )->orderBy( 'createdAt ASC' )->andWhere([ 'siteId' => $siteId ])->all();
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function create( $model, $config = [] ) {

		$siteId = isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;

		$model->siteId	= $siteId;

		return parent::create( $model, $config );
	}

	public function createByParams( $params = [], $config = [] ) {

		$params[ 'link' ]		= isset( $params[ 'link' ] ) ? $params[ 'link' ] : null;
		$params[ 'adminLink' ]	= isset( $params[ 'adminLink' ] ) ? $params[ 'adminLink' ] : null;

		return parent::createByParams( $params, $config );
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$admin		= isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;
		$attributes	= isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'title', 'description', 'content' ];

		if( $admin ) {

			$attributes[] = 'status';
		}

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	public function updateStatus( $model, $status ) {

		$model->status = $status;

		return parent::update( $model, [
			'attributes' => [ 'status' ]
		]);
	}

	public function approve( $model ) {

		return $this->updateStatus( $model, Announcement::STATUS_APPROVED );
	}

	public function activate( $model ) {

		return $this->updateStatus( $model, Announcement::STATUS_ACTIVE );
	}

	public function pause( $model ) {

		return $this->updateStatus( $model, Announcement::STATUS_PAUSED );
	}

	public function expire( $model ) {

		return $this->updateStatus( $model, Announcement::STATUS_EXPIRED );
	}

	// Delete -------------

	// Bulk ---------------

	public function applyBulkByParent( $column, $action, $target, $parentId, $parentType ) {

		foreach( $target as $id ) {

			$model = $this->getById( $id );

			if( isset( $model ) && $model->parentId == $parentId && $model->parentType == $parentType ) {

				$this->applyBulk( $model, $column, $action, $target );
			}
		}
	}

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'status': {

				switch( $action ) {

					case 'approved': {

						$this->approve( $model );

						break;
					}
					case 'active': {

						$this->activate( $model );

						break;
					}
					case 'paused': {

						$this->pause( $model );

						break;
					}
					case 'expired': {

						$this->expire( $model );

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

	// AnnouncementService -------------------

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
