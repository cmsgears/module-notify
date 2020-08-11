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
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\notify\common\config\NotifyGlobal;

use cmsgears\core\common\models\interfaces\base\IAuthor;
use cmsgears\core\common\models\interfaces\base\IMultiSite;
use cmsgears\core\common\models\interfaces\base\INameType;
use cmsgears\core\common\models\interfaces\base\IOwner;
use cmsgears\core\common\models\interfaces\base\ISlugType;
use cmsgears\core\common\models\interfaces\resources\IData;
use cmsgears\core\common\models\interfaces\resources\IModelMeta;
use cmsgears\core\common\models\interfaces\resources\ITemplate;
use cmsgears\core\common\models\interfaces\resources\IVisual;
use cmsgears\core\common\models\interfaces\mappers\IFile;

use cmsgears\core\common\models\base\ModelResource;
use cmsgears\core\common\models\entities\User;

use cmsgears\notify\common\models\base\NotifyTables;

use cmsgears\core\common\models\traits\base\AuthorTrait;
use cmsgears\core\common\models\traits\base\MultiSiteTrait;
use cmsgears\core\common\models\traits\base\NameTypeTrait;
use cmsgears\core\common\models\traits\base\SlugTypeTrait;
use cmsgears\core\common\models\traits\base\UserOwnerTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\resources\ModelMetaTrait;
use cmsgears\core\common\models\traits\resources\TemplateTrait;
use cmsgears\core\common\models\traits\resources\VisualTrait;
use cmsgears\core\common\models\traits\mappers\FileTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

use cmsgears\core\common\utilities\DateUtil;

/**
 * Event model represents an event on calendar. Scheduled reminders will be triggered for
 * the event according to it's configuration.
 *
 * @property integer $id
 * @property integer $siteId
 * @property integer $templateId
 * @property integer $userId
 * @property integer $avatarId
 * @property integer $bannerId
 * @property integer $videoId
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property integer $parentId
 * @property string $parentType
 * @property string $name
 * @property string $slug
 * @property string $type
 * @property string $icon
 * @property string $title
 * @property string $description
 * @property short $preReminderCount
 * @property short $preReminderInterval
 * @property short $preIntervalUnit
 * @property short $postReminderCount
 * @property short $postReminderInterval
 * @property short $postIntervalUnit
 * @property boolean $admin
 * @property boolean $group
 * @property short $status
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property datetime $scheduledAt
 * @property datetime $triggeredAt
 * @property string $content
 * @property string $data
 * @property string $gridCache
 * @property boolean $gridCacheValid
 * @property datetime $gridCachedAt
 *
 * @since 1.0.0
 */
class Event extends ModelResource implements IAuthor, IData, IFile, IModelMeta, IMultiSite,
	INameType, IOwner, ISlugType, ITemplate, IVisual {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	const STATUS_NEW		= 	  0;
	const STATUS_CANCELLED	= 	100;
	const STATUS_ACTIVE		=  1000;
	const STATUS_EXPIRED	=  2000;

	// Constants --------------

	public static $statusMinMap = [
		self::STATUS_NEW => 'New',
		self::STATUS_ACTIVE => 'Active'
	];

	public static $statusMap = [
		self::STATUS_NEW => 'New',
		self::STATUS_CANCELLED => 'Cancelled',
		self::STATUS_ACTIVE => 'Active',
		self::STATUS_EXPIRED => 'Expired'
	];

	public static $revStatusMap = [
		'New' => self::STATUS_NEW,
		'Cancelled' => self::STATUS_CANCELLED,
		'Active' => self::STATUS_ACTIVE,
		'Expired' => self::STATUS_EXPIRED
	];

	public static $urlRevStatusMap = [
		'new' => self::STATUS_NEW,
		'cancelled' => self::STATUS_CANCELLED,
		'active' => self::STATUS_ACTIVE,
		'expired' => self::STATUS_EXPIRED
	];

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = NotifyGlobal::TYPE_EVENT;

	// Private ----------------

	// Traits ------------------------------------------------------

	use AuthorTrait;
	use DataTrait;
	use FileTrait;
	use ModelMetaTrait;
	use MultiSiteTrait;
	use NameTypeTrait;
	use SlugTypeTrait;
	use TemplateTrait;
	use UserOwnerTrait;
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
            ],
			'sluggableBehavior' => [
				'class' => SluggableBehavior::class,
				'attribute' => 'name',
				'slugAttribute' => 'slug', // Unique for combination of Site Id
				'immutable' => true,
				'ensureUnique' => true,
				'uniqueValidator' => [ 'targetAttribute' => [ 'siteId', 'slug' ] ]
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
			[ [ 'name', 'scheduledAt' ], 'required' ],
			[ [ 'id', 'content', 'gridCache' ], 'safe' ],
			// Unique
			[ 'slug', 'unique', 'targetAttribute' => [ 'siteId', 'slug' ] ],
			// Text Limit
			[ [ 'parentType', 'type' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ 'icon', 'string', 'min' => 1, 'max' => Yii::$app->core->largeText ],
			[ 'name', 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ 'slug', 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			[ 'title', 'string', 'min' => 1, 'max' => Yii::$app->core->xxxLargeText ],
			[ 'description', 'string', 'min' => 1, 'max' => Yii::$app->core->xtraLargeText ],
			// Other
			[ [ 'preReminderCount', 'preReminderInterval', 'preIntervalUnit', 'postReminderCount', 'postReminderInterval', 'postIntervalUnit', 'status' ], 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'admin', 'group', 'gridCacheValid' ], 'boolean' ],
			[ 'status', 'number', 'integerOnly' => true, 'min' => 0 ],
			[ 'templateId', 'number', 'integerOnly' => true, 'min' => 0, 'tooSmall' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
			[ [ 'siteId', 'userId', 'createdBy', 'modifiedBy', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt', 'scheduledAt', 'triggeredAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];

		// Trim Text
        if( Yii::$app->core->trimFieldValue ) {

            $trim[] = [ [ 'name', 'title', 'description' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

            return ArrayHelper::merge( $trim, $rules );
        }

        return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'siteId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SITE ),
			'templateId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TEMPLATE ),
			'userId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_USER ),
			'parentId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'icon' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ICON ),
			'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'description' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
			'status' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_STATUS ),
			'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONTENT ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA ),
			'gridCache' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_GRID_CACHE ),
			'scheduleDate' => 'Schedule Date',
			'scheduleTime' => 'Schedule Time'
		];
	}

	// yii\db\BaseActiveRecord

	/**
	 * @inheritdoc
	 */
	public function beforeSave( $insert ) {

		if( parent::beforeSave( $insert ) ) {

			if( $this->templateId <= 0 ) {

				$this->templateId = null;
			}

			// Default Type - Default
			$this->type = $this->type ?? CoreGlobal::TYPE_DEFAULT;

			return true;
		}

		return false;
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Event ---------------------------------

	/**
	 * Returns the host user associated with the event.
	 *
	 * @return \cmsgears\core\common\models\entities\User
	 */
	public function getUser() {

		return $this->hasOne( User::class, [ 'id' => 'userId' ] );
	}

	/**
	 * Returns string representation of admin flag.
	 *
	 * @return string
	 */
	public function getAdminStr() {

		return Yii::$app->formatter->asBoolean( $this->admin );
	}

	/**
	 * Returns string representation of multi user flag.
	 *
	 * @return string
	 */
	public function getGroupStr() {

		return Yii::$app->formatter->asBoolean( $this->group );
	}

	/**
	 * Returns string representation of status.
	 *
	 * @return string
	 */
	public function getStatusStr() {

		return self::$statusMap[ $this->status ];
	}

	/**
	 * Returns string representation of pre reminder interval.
	 *
	 * @return string
	 */
	public function getPreIntervalStr() {

		$unit = DateUtil::$durationMap[ $this->preIntervalUnit ];

		return "$this->preReminderInterval $unit" . 's';
	}

	/**
	 * Returns string representation of post reminder interval.
	 *
	 * @return string
	 */
	public function getPostIntervalStr() {

		$unit = DateUtil::$durationMap[ $this->postIntervalUnit ];

		return "$this->postReminderInterval $unit" . 's';
	}

	public function isNew() {

		return $this->status == self::STATUS_NEW;
	}

	public function isCancelled() {

		return $this->status == self::STATUS_CANCELLED;
	}

	public function isActive() {

		return $this->status == self::STATUS_ACTIVE;
	}

	public function isCancellable() {

		return $this->status == self::STATUS_NEW || $this->status == self::STATUS_ACTIVE;
	}

	public function isActivable() {

		return $this->status == self::STATUS_NEW || $this->status == self::STATUS_CANCELLED;
	}

	public function isExpirable() {

		$statusCheck = in_array( $this->status, [ self::STATUS_NEW, self::STATUS_CANCELLED, self::STATUS_ACTIVE ] );

		$expired = DateUtil::isPast( $this->scheduledAt );

		return $statusCheck && $expired;
	}

	public function isEditable() {

		return $this->status < self::STATUS_EXPIRED;
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return NotifyTables::getTableName( NotifyTables::TABLE_EVENT );
	}

	// CMG parent classes --------------------

	// Event ---------------------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'site', 'template', 'user', 'creator', 'modifier' ];

		$config[ 'relations' ] = $relations;

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the event with user.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with user.
	 */
	public static function queryWithUser( $config = [] ) {

		$config[ 'relations' ] = [ 'user' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

	/**
	 * Delete all entries related to a user
	 */
	public static function deleteByUserId( $userId ) {

		self::deleteAll( 'userId=:uid', [ ':uid' => $userId ] );
	}

}
