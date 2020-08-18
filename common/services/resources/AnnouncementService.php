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
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\notify\common\config\NotifyGlobal;

use cmsgears\notify\common\models\resources\Announcement;

use cmsgears\core\common\services\interfaces\resources\IFileService;

use cmsgears\notify\common\services\interfaces\resources\IAnnouncementService;

use cmsgears\core\common\services\traits\base\MultisiteTrait;

/**
 * AnnouncementService provide service methods of announcement model.
 *
 * @since 1.0.0
 */
class AnnouncementService extends \cmsgears\core\common\services\base\ModelResourceService implements IAnnouncementService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\notify\common\models\resources\Announcement';

	public static $parentType = NotifyGlobal::TYPE_ANNOUNCEMENT;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $fileService;

	// Private ----------------

	// Traits ------------------------------------------------------

	use MultisiteTrait;

	// Constructor and Initialisation ------------------------------

	public function __construct( IFileService $fileService, $config = [] ) {

		$this->fileService = $fileService;

		parent::__construct( $config );
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// AnnouncementService -------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$searchParam	= $config[ 'search-param' ] ?? 'keywords';
		$searchColParam	= $config[ 'search-col-param' ] ?? 'search';

		$defaultSort = isset( $config[ 'defaultSort' ] ) ? $config[ 'defaultSort' ] : [ 'id' => SORT_DESC ];

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

		// Result -----------

		return parent::getPage( $config );
	}

	/**
	 * Returns the data provider to show admin announcements. The results also include
	 * application announcements that need admin intervention.
	 *
	 * @return \cmsgears\core\common\data\ActiveDataProvider
	 */
	public function getPageForAdmin( $config = [] ) {

		$modelTable	= $this->getModelTable();

		$config[ 'conditions' ][] = "$modelTable.access >=" . Announcement::ACCESS_APP;

		$config[ 'conditions' ][ "$modelTable.admin" ] = true;

		return $this->getPage( $config );
	}

	public function getPageForSite( $config = [] ) {

		$siteId = isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->site->id;

		$modelTable	= $this->getModelTable();

		$config[ 'ignoreSite' ] = true;

		$config[ 'conditions' ][ "$modelTable.parentId" ]	= $siteId;
		$config[ 'conditions' ][ "$modelTable.parentType" ]	= CoreGlobal::TYPE_SITE;
		$config[ 'conditions' ][ "$modelTable.admin" ]		= false;

		$config[ 'conditions' ][] = "$modelTable.access <" . Announcement::ACCESS_ADMIN;

		return $this->getPage( $config );
	}

	public function getPageByParent( $parentId, $parentType, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$modelTable	= $this->getModelTable();

		$config[ 'conditions' ][ "$modelTable.parentId" ]	= $parentId;
		$config[ 'conditions' ][ "$modelTable.parentType" ]	= $parentType;
		$config[ 'conditions' ][ "$modelTable.admin" ]		= $admin;

		return $this->getPage( $config );
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
	public function getRecentForAdmin( $limit = 5, $config = [] ) {

		$siteId		= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;
		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$query = $modelClass::find()->where( 'admin=:admin AND status=:status', [ ':admin' => false, ':status' => $modelClass::STATUS_ACTIVE ] );

		$query->andWhere( "$modelTable.access >=" . Announcement::ACCESS_APP_ADMIN );

		if( !$ignoreSite ) {

			$query->andWhere( 'siteId=:siteId', [ ':siteId' => $siteId ] );
		}

		$query->limit( $limit )->orderBy( 'createdAt DESC' );

		return $query->all();
	}

	public function getRecentForSite( $limit = 5, $config = [] ) {

		$siteId = isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$query = $modelClass::find()->where( 'admin=:admin AND status=:status AND siteId=:siteId', [ ':admin' => false, ':status' => $modelClass::STATUS_ACTIVE, ':siteId' => $siteId ] );

		$query->andWhere( "$modelTable.access < " . Announcement::ACCESS_ADMIN );

		$query->limit( $limit )->orderBy( 'createdAt DESC' );

		return $query->all();
	}

	public function getRecentByParent( $parentId, $parentType, $limit = 5, $config = [] ) {

		$admin		= isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : true;
		$siteId		= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;
		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$query = $modelClass::queryByParent( $parentId, $parentType )->where( 'admin=:admin AND status=:status', [ ':admin' => false, ':status' => $modelClass::STATUS_ACTIVE ] );

		$query->andWhere( "$modelTable.access >=" . Announcement::ACCESS_APP_ADMIN );

		if( !$ignoreSite ) {

			$query->andWhere( 'siteId=:siteId', [ ':siteId' => $siteId ] );
		}

		$query->limit( $limit )->orderBy( 'createdAt DESC' );

		return $query->all();
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function create( $model, $config = [] ) {

		$banner = isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;

		// Save resources
		$this->fileService->saveFiles( $model, [ 'bannerId' => $banner ] );

		return parent::create( $model, $config );
	}

	public function createByParams( $params = [], $config = [] ) {

		$params[ 'link' ]		= isset( $params[ 'link' ] ) ? $params[ 'link' ] : null;
		$params[ 'admin' ]		= isset( $params[ 'admin' ] ) ? $params[ 'admin' ] : null;
		$params[ 'adminLink' ]	= isset( $params[ 'adminLink' ] ) ? $params[ 'adminLink' ] : null;

		return parent::createByParams( $params, $config );
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$attributes	= isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'bannerId', 'title', 'description', 'link', 'adminLink', 'expiresAt', 'content'
		];

		$banner = isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;

		if( $admin ) {

			$attributes	= ArrayHelper::merge( $attributes, [ 'status', 'access', 'admin' ] );
		}

		// Save resources
		$this->fileService->saveFiles( $model, [ 'bannerId' => $banner ] );

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

	public function delete( $model, $config = [] ) {

		// Delete resources
		$this->fileService->deleteFiles( [ $model->banner ] );

		return parent::delete( $model, $config );
	}

	// Bulk ---------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'status': {

				switch( $action ) {

					case 'approve': {

						$this->approve( $model );

						break;
					}
					case 'activate': {

						$this->activate( $model );

						break;
					}
					case 'pause': {

						$this->pause( $model );

						break;
					}
					case 'expire': {

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
