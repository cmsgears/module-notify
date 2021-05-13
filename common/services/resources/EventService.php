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

use cmsgears\core\common\services\interfaces\resources\IFileService;

use cmsgears\notify\common\services\interfaces\resources\IEventService;

use cmsgears\core\common\services\traits\base\MultisiteTrait;
use cmsgears\core\common\services\traits\base\NameTypeTrait;
use cmsgears\core\common\services\traits\base\SlugTypeTrait;
use cmsgears\core\common\services\traits\mappers\FileTrait;

/**
 * EventService provide service methods of event model.
 *
 * @since 1.0.0
 */
class EventService extends \cmsgears\core\common\services\base\ModelResourceService implements IEventService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\notify\common\models\resources\Event';

	public static $typed = true;

	public static $parentType = NotifyGlobal::TYPE_EVENT;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $fileService;

	// Private ----------------

	// Traits ------------------------------------------------------

	use FileTrait;
	use MultisiteTrait;
	use NameTypeTrait;
	use SlugTypeTrait;

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

	// EventService --------------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$searchParam	= $config[ 'search-param' ] ?? 'keywords';
		$searchColParam	= $config[ 'search-col-param' ] ?? 'search';

		$defaultSort = isset( $config[ 'defaultSort' ] ) ? $config[ 'defaultSort' ] : [ 'id' => SORT_DESC ];

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
					'asc' => [ "$userTable.name" => SORT_ASC ],
					'desc' => [ "$userTable.name" => SORT_DESC ],
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
				'icon' => [
					'asc' => [ "$modelTable.icon" => SORT_ASC ],
					'desc' => [ "$modelTable.icon" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Icon'
				],
				'title' => [
					'asc' => [ "$modelTable.title" => SORT_ASC ],
					'desc' => [ "$modelTable.title" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Title'
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
				'pruinterval' => [
					'asc' => [ "$modelTable.preIntervalUnit" => SORT_ASC ],
					'desc' => [ "$modelTable.preIntervalUnit" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Pre Interval Interval'
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
				'psuinterval' => [
					'asc' => [ "$modelTable.postIntervalUnit" => SORT_ASC ],
					'desc' => [ "$modelTable.postIntervalUnit" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Post Interval Interval'
				],
				'grouped' => [
					'asc' => [ "$modelTable.grouped" => SORT_ASC ],
					'desc' => [ "$modelTable.grouped" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Grouped'
				],
				'status' => [
					'asc' => [ "$modelTable.status" => SORT_ASC ],
					'desc' => [ "$modelTable.status" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Status'
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
		$filter	= Yii::$app->request->getQueryParam( 'model' );

		// Filter - Type
		if( isset( $type ) ) {

			$config[ 'conditions' ][ "$modelTable.type" ] = $type;
		}

		// Filter - Status
		if( isset( $status ) && isset( $modelClass::$urlRevStatusMap[ $status ] ) ) {

			$config[ 'conditions' ][ "$modelTable.status" ]	= $modelClass::$urlRevStatusMap[ $status ];
		}

		// Filter - Model
		if( isset( $filter ) ) {

			switch( $filter ) {

				case 'grouped': {

					$config[ 'conditions' ][ "$modelTable.grouped" ] = true;

					break;
				}
			}
		}

		// Searching --------

		$searchCol		= Yii::$app->request->getQueryParam( $searchColParam );
		$keywordsCol	= Yii::$app->request->getQueryParam( $searchParam );

		$search = [
			'name' => "$modelTable.name",
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

		$config[ 'report-col' ]	= $config[ 'report-col' ] ?? [
			'name' => "$modelTable.name",
			'title' => "$modelTable.title",
			'desc' => "$modelTable.description",
			'content' => "$modelTable.content",
			'type' => "$modelTable.type",
			'status' => "$modelTable.status",
			'grouped' => "$modelTable.grouped",
			'sdate' => "$modelTable.Scheduled At"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	public function getPageForAdmin( $config = [] ) {

		$modelTable	= $this->getModelTable();

		$config[ 'conditions' ][ "$modelTable.admin" ] = true;

		return $this->getPage( $config );
	}

	public function getPageByUserId( $userId, $config = [] ) {

		$modelTable	= $this->getModelTable();

		$config[ 'conditions' ][ "$modelTable.userId" ] = $userId;

		return $this->getPage( $config );
	}

	public function getPageByParent( $parentId, $parentType, $config = [] ) {

		$modelTable	= $this->getModelTable();

		$config[ 'conditions' ][ "$modelTable.parentId" ]	= $parentId;
		$config[ 'conditions' ][ "$modelTable.parentType" ] = $parentType;

		return $this->getPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	public function getByRangeUserId( $startDate, $endDate, $userId, $config = [] ) {

		$siteId		= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;
		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$conditions = [ 'userId' => $userId ];

		if( !$ignoreSite ) {

			$conditions[ 'siteId' ]	= $siteId;
		}

		$query = $modelClass::queryWithUser( [ 'conditions' => $conditions ] );

		$query->andWhere( "$modelTable.scheduledAt BETWEEN ':sdate' AND ':edate'", [ ':sdate' => $startDate, ':edate' => $endDate ] );

		return $query->all();
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function create( $model, $config = [] ) {

		$avatar = isset( $config[ 'avatar' ] ) ? $config[ 'avatar' ] : null;
		$banner = isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;
		$video	= isset( $config[ 'video' ] ) ? $config[ 'video' ] : null;

		// Save resources
		$this->fileService->saveFiles( $model, [ 'avatarId' => $avatar, 'bannerId' => $banner, 'videoId' => $video ] );

		return parent::create( $model, $config );
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$attributes	= isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'templateId', 'avatarId', 'bannerId', 'videoId',
			'name', 'slug', 'icon', 'title', 'description',
			'preReminderCount', 'preReminderInterval', 'preIntervalUnit',
			'postReminderCount', 'postReminderInterval', 'postIntervalUnit',
			'grouped', 'status', 'scheduledAt', 'content'
		];

		$avatar = isset( $config[ 'avatar' ] ) ? $config[ 'avatar' ] : null;
		$banner = isset( $config[ 'banner' ] ) ? $config[ 'banner' ] : null;
		$video	= isset( $config[ 'video' ] ) ? $config[ 'video' ] : null;

		// Save resources
		$this->fileService->saveFiles( $model, [ 'avatarId' => $avatar, 'bannerId' => $banner, 'videoId' => $video ] );

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	public function markGrouped( $model ) {

		$model->grouped = true;

		return parent::update( $model, [
			'attributes' => [ 'grouped' ]
		]);
	}

	public function updateStatus( $model, $status ) {

		$model->status = $status;

		return parent::update( $model, [
			'attributes' => [ 'status' ]
		]);
	}

	public function cancel( $model ) {

		if( $model->isCancellable() ) {

			return $this->updateStatus( $model, Event::STATUS_CANCELLED );
		}

		return false;
	}

	public function activate( $model ) {

		if( $model->isActivable() ) {

			return $this->updateStatus( $model, Event::STATUS_ACTIVE );
		}

		return false;
	}

	public function expire( $model ) {

		if( $model->isExpirable() ) {

			return $this->updateStatus( $model, Event::STATUS_EXPIRED );
		}

		return false;
	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		// Delete Model Files
		$this->fileService->deleteMultiple( $model->files );

		// Delete model
		return parent::delete( $model, $config );
	}

	// Bulk ---------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'status': {

				switch( $action ) {

					case 'cancel': {

						$this->cancel( $model );

						break;
					}
					case 'activate': {

						$this->activate( $model );

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

					case 'grouped': {

						$this->markGrouped( $model );

						break;
					}
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

	// EventService --------------------------

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
