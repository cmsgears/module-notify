<?php
namespace cmsgears\notify\common\models\entities;

// Yii Imports
use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\notify\common\config\NotifyGlobal;

use cmsgears\notify\common\models\base\NotifyTables;

use cmsgears\core\common\models\entities\Site;
use cmsgears\core\common\models\entities\User;

use cmsgears\core\common\models\traits\CreateModifyTrait;
use cmsgears\core\common\models\traits\NameTypeTrait;
use cmsgears\core\common\models\traits\ResourceTrait;
use cmsgears\core\common\models\traits\SlugTypeTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * Event Entity
 *
 * @property integer $id
 * @property integer $siteId
 * @property integer $userId
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property integer $parentId
 * @property string $parentType
 * @property string $name
 * @property string $slug
 * @property string $type
 * @property string $icon
 * @property string $description
 * @property short $preReminderCount
 * @property short $preReminderInterval
 * @property short $postReminderCount
 * @property short $postReminderInterval
 * @property short $preIntervalUnit
 * @property short $postIntervalUnit
 * @property boolean $admin
 * @property boolean $multiUser
 * @property short $status
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property datetime $scheduledAt
 * @property string $content
 * @property string $data
 */
class Event extends \cmsgears\core\common\models\base\Entity {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	const STATUS_NEW	= 	  0;
	const STATUS_TRASH	= 20000;

	// Interval Units ---------

	const UNIT_YEAR		= 0;
	const UNIT_MONTH	= 1;
	const UNIT_DAY		= 2;
	const UNIT_HOUR		= 3;
	const UNIT_MINUTE	= 4;
	const UNIT_SECOND	= 5;

	// Constants --------------

	public static $statusMap = [
		self::STATUS_NEW => 'New',
		self::STATUS_TRASH => 'Trash'
	];

	public static $revStatusMap = [
		'New' => self::STATUS_NEW,
		'Trash' => self::STATUS_TRASH
	];

	public static $urlRevStatusMap = [
		'new' => self::STATUS_NEW,
		'trash' => self::STATUS_TRASH
	];

	public static $unitMap = [
		self::UNIT_YEAR => 'year',
		self::UNIT_MONTH => 'month',
		self::UNIT_DAY => 'days',
		self::UNIT_HOUR => 'hours',
		self::UNIT_MINUTE => 'minute',
		self::UNIT_SECOND => 'seconds'
	];

	// Public -----------------

	// Protected --------------

	public static $multiSite = true;

	// Variables -----------------------------

	// Public -----------------

	public $modelType	= NotifyGlobal::TYPE_EVENT;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use CreateModifyTrait;
	use DataTrait;
	use NameTypeTrait;
	use ResourceTrait;
	use SlugTypeTrait;

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
				'class' => AuthorBehavior::className()
			],
			'timestampBehavior' => [
				'class' => TimestampBehavior::className(),
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

		$rules = [
			// Required, Safe
			[ [ 'siteId', 'name' ], 'safe' ],
			[ [ 'id', 'content', 'data' ], 'safe' ],
			// Unique
			[ [ 'name', 'type' ], 'unique', 'targetAttribute' => [ 'name', 'type' ] ],
			[ [ 'slug', 'type' ], 'unique', 'targetAttribute' => [ 'slug', 'type' ] ],
			// Text Limit
			[ [ 'parentType', 'type' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ 'icon', 'string', 'min' => 1, 'max' => Yii::$app->core->largeText ],
			[ 'name', 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ [ 'slug', 'description' ], 'string', 'min' => 0, 'max' => Yii::$app->core->xxLargeText ],
			// Other
			[ [ 'preReminderCount', 'preReminderInterval', 'postReminderCount', 'postReminderInterval', 'preIntervalUnit', 'postIntervalUnit', 'status' ], 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'admin', 'multiUser' ], 'boolean' ],
			[ [ 'siteId', 'userId', 'createdBy', 'modifiedBy', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt', 'scheduledAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'siteId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SITE ),
			'userId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_USER ),
			'parentId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'icon' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ICON ),
			'description' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
			'status' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_STATUS ),
			'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONTENT ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Event ---------------------------------

	public function getSite() {

		return $this->hasOne( Site::className(), [ 'id' => 'siteId' ] );
	}

	/**
	 * @return User - associated user
	 */
	public function getUser() {

		return $this->hasOne( Activity::className(), [ 'id' => 'userId' ] );
	}

	public function getAdminStr() {

		return Yii::$app->formatter->asBoolean( $this->admin );
	}

	public function getMultiStr() {

		return Yii::$app->formatter->asBoolean( $this->multiUser );
	}

	public function getStatusStr() {

		return self::$statusMap[ $this->status ];
	}

	public function getPreIntervalStr() {

		return $this->preReminderInterval . self::$unitMap[ $this->preIntervalUnit ];
	}

	public function getPostIntervalStr() {

		return $this->postReminderInterval . self::$unitMap[ $this->postIntervalUnit ];
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return NotifyTables::TABLE_EVENT;
	}

	// CMG parent classes --------------------

	// Event ---------------------------------

	// Read - Query -----------

	public static function queryWithHasOne( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'site', 'user', 'creator', 'modifier' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	public static function queryWithSite( $config = [] ) {

		$config[ 'relations' ]	= [ 'site' ];

		return parent::queryWithAll( $config );
	}

	public static function queryWithUser( $config = [] ) {

		$config[ 'relations' ]	= [ 'user' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
