<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\notify\common\models\resources;

// Yii Imports
use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\notify\common\config\NotifyGlobal;

use cmsgears\core\common\models\interfaces\base\IAuthor;
use cmsgears\core\common\models\interfaces\base\IMultiSite;
use cmsgears\core\common\models\interfaces\resources\IData;
use cmsgears\core\common\models\interfaces\resources\IVisual;

use cmsgears\core\common\models\base\ModelResource;
use cmsgears\notify\common\models\base\NotifyTables;

use cmsgears\core\common\models\traits\base\AuthorTrait;
use cmsgears\core\common\models\traits\base\MultiSiteTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\resources\VisualTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * Announcement represents announcements sent to users and admin based on application updates.
 *
 * @property integer $id
 * @property integer $siteId
 * @property integer $bannerId
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property integer $parentId
 * @property string $parentType
 * @property string $title
 * @property string $description
 * @property string $type
 * @property integer $status
 * @property integer $access
 * @property string $link
 * @property string $adminLink
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property string $content
 * @property string $data
 * @property string $gridCache
 * @property boolean $gridCacheValid
 * @property datetime $gridCachedAt
 *
 * @since 1.0.0
 */
class Announcement extends ModelResource implements IAuthor, IData, IMultiSite, IVisual {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	const STATUS_NEW		=   0;
	const STATUS_APPROVED	= 100;
	const STATUS_ACTIVE		= 200;
	const STATUS_PAUSED		= 300;
	const STATUS_EXPIRED	= 400;

	// App Only
	const ACCESS_APP		= 100; // Directly available on App without admin intervention

	// Admin, App
	const ACCESS_APP_ACT	= 200; // Available on App with admin intervention
	const ACCESS_ADMIN		= 300; // Available only on Admin
	const ACCESS_APP_ADMIN	= 400; // Available on both Admin and App

	// Public -----------------

	public static $statusMap = [
		self::STATUS_NEW => 'New',
		self::STATUS_APPROVED => 'Approved',
		self::STATUS_ACTIVE => 'Active',
		self::STATUS_PAUSED => 'Paused',
		self::STATUS_EXPIRED => 'Expired'
	];

	// Used for external docs
	public static $revStatusMap = [
		'New' => self::STATUS_NEW,
		'Approved' => self::STATUS_APPROVED,
		'Active' => self::STATUS_ACTIVE,
		'Paused' => self::STATUS_PAUSED,
		'Expired' => self::STATUS_EXPIRED
	];

	// Used for url params
	public static $urlRevStatusMap = [
		'new' => self::STATUS_NEW,
		'approved' => self::STATUS_APPROVED,
		'active' => self::STATUS_ACTIVE,
		'paused' => self::STATUS_PAUSED,
		'expired' => self::STATUS_EXPIRED
	];

	public static $accessMap = [
		self::ACCESS_APP => 'App',
		self::ACCESS_APP_ACT => 'App Act',
		self::ACCESS_ADMIN => 'Admin',
		self::ACCESS_APP_ADMIN => 'App & Admin'
	];

	// Used for url params
	public static $urlRevAccessMap = [
		'app' => self::ACCESS_APP,
		'appact' => self::ACCESS_APP_ACT,
		'admin' => self::ACCESS_ADMIN,
		'appadmin' => self::ACCESS_APP_ADMIN
	];

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = NotifyGlobal::TYPE_ANNOUNCEMENT;

	// Private ----------------

	// Traits ------------------------------------------------------

	use AuthorTrait;
	use DataTrait;
	use MultiSiteTrait;
	use VisualTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	/**
	 * @inheritdoc
	 */
	public function behaviors() {

		return [
			'authorBehavior' => [
				'class' => AuthorBehavior::class
			],
			'timestampBehavior' => [
				'class' => TimestampBehavior::class,
				'createdAtAttribute' => 'createdAt',
				'updatedAtAttribute' => 'modifiedAt',
				'value' => new Expression('NOW()')
			]
		];
	}

	// yii\base\Model ---------

	/**
	 * @inheritdoc
	 */
	public function rules() {

		// Model Rules
		$rules = [
			// Required, Safe
			[ [ 'siteId', 'title' ], 'required' ],
			[ [ 'id', 'content', 'data',  'gridCache' ], 'safe' ],
			// Text Limit
			[ [ 'parentType', 'type' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ 'title', 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			[ [ 'link', 'adminLink' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxxLargeText ],
			[ 'description', 'string', 'min' => 1, 'max' => Yii::$app->core->xtraLargeText ],
			// Other
			[ 'gridCacheValid', 'boolean' ],
			[ [ 'status', 'access' ], 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'siteId', 'bannerId', 'createdBy', 'modifiedBy', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt', 'gridCachedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'siteId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SITE ),
			'bannerId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_BANNER ),
			'parentId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'description' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'status' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_STATUS ),
			'access' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ACCESS ),
			'link' => Yii::$app->notifyMessage->getMessage( NotifyGlobal::FIELD_FOLLOW ),
			'adminLink' => Yii::$app->notifyMessage->getMessage( NotifyGlobal::FIELD_FOLLOW_ADMIN ),
			'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONTENT ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA ),
			'gridCache' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_GRID_CACHE )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Announcement --------------------------

	/**
	 * Returns string representation of [[$status]].
	 *
	 * @return string
	 */
	public function getStatusStr() {

		return self::$statusMap[ $this->status ];
	}

	/**
	 * Check whether announcement is new.
	 *
	 * @return boolean
	 */
	public function isNew() {

		return $this->status == self::STATUS_NEW;
	}

	/**
	 * Check whether announcement is approved.
	 *
	 * @return boolean
	 */
	public function isApproved() {

		return $this->status == self::STATUS_APPROVED;
	}

	/**
	 * Check whether announcement is active.
	 *
	 * @return boolean
	 */
	public function isActive() {

		return $this->status == self::STATUS_ACTIVE;
	}

	/**
	 * Check whether announcement is paused.
	 *
	 * @return boolean
	 */
	public function isPaused() {

		return $this->status == self::STATUS_PAUSED;
	}

	/**
	 * Check whether announcement is expired.
	 *
	 * @return boolean
	 */
	public function isExpired() {

		return $this->status == self::STATUS_EXPIRED;
	}

	/**
	 * Returns string representation of [[$access]].
	 *
	 * @return string
	 */
	public function getAccessStr() {

		return self::$accessMap[ $this->access ];
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return NotifyTables::getTableName( NotifyTables::TABLE_ANNOUNCEMENT );
	}

	// CMG parent classes --------------------

	// Announcement --------------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'creator', 'modifier' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
