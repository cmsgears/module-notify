<?php
namespace cmsgears\notify\common\models\entities;

// Yii Imports
use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;


// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\User;
use cmsgears\notify\common\models\base\NotifyTables;

use cmsgears\core\common\models\traits\ResourceTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\CreateModifyTrait;

/**
 * Activity Entity - It can be used to log user activities. A model can be optionally associated with it to identify model specific activities.
 *
 * @property long $id
 * @property integer $siteId
 * @property long $userId
 * @property long $parentId
 * @property string $parentType
 * @property short $title
 * @property short $type
 * @property string $ip
 * @property string $agent
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property string $content
 * @property string $data
 */
class Activity extends \cmsgears\core\common\models\base\Entity {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $multiSite	= true;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use DataTrait;
	use ResourceTrait;
	use CreateModifyTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Model ---------

	
	/**
	 * @inheritdoc
	 */
	public function behaviors() {

		return [
			
			'timestampBehavior' => [
				'class' => TimestampBehavior::className(),
				'createdAtAttribute' => 'createdAt',
				'updatedAtAttribute' => 'modifiedAt',
				'value' => new Expression('NOW()')
			]
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules() {

		return [
			// Required, Safe
			[ [ 'userId' ], 'required' ],
			[ [ 'id', 'content', 'data',  'adminLink' ], 'safe' ],
			// Text Limit
			[ [ 'parentType', 'type', 'ip' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ 'agent', 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ 'title', 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			// Other
			[ [ 'admin', 'consumed', 'trash' ], 'boolean' ],
			[ [ 'siteId', 'userId', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'userId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_USER ),
			'parentId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'ip' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_IP ),
			'agent' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_AGENT_BROWSER ),
			'admin' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ADMIN ),
			'adminLink' => Yii::$app->notifyMessage->getMessage( NotifyGlobal::FIELD_FOLLOW_ADMIN ),
			'consumed' => 'Consumed',
			'trash' => 'Trash',
			'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONTENT ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// ModelActivity -------------------------

	/**
	 * @return User - associated user
	 */
	public function getUser() {

		return $this->hasOne( User::className(), [ 'id' => 'userId' ] );
	}

	public function isNew() {

		return !$this->consumed;
	}

	public function isConsumed() {

		return $this->consumed;
	}
	
	public function getConsumedStr() {

		return Yii::$app->formatter->asBoolean( $this->consumed );
	}

	public function isTrash() {

		return $this->trash;
	}

	public function getTrashStr() {

		return Yii::$app->formatter->asBoolean( $this->trash );
	}
	
	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return NotifyTables::TABLE_ACTIVITY;
	}

	// CMG parent classes --------------------

	// ModelActivity -------------------------

	// Read - Query -----------

	public static function queryWithHasOne( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'user' ];
		$config[ 'relations' ]	= $relations;

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
