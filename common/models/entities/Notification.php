<?php
namespace cmsgears\notify\common\models\entities;

// Yii Imports
use Yii;
use yii\helpers\Url;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\notify\common\config\NotifyGlobal;

use cmsgears\core\common\models\interfaces\IOwner;

use cmsgears\core\common\models\entities\User;
use cmsgears\notify\common\models\base\NotifyTables;

use cmsgears\core\common\models\traits\CreateModifyTrait;
use cmsgears\core\common\models\traits\ResourceTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * Notification Entity
 *
 * @property integer $id
 * @property integer $siteId
 * @property integer $userId
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property integer $parentId
 * @property string $parentType
 * @property string $title
 * @property string $type
 * @property string $ip
 * @property string $agent
 * @property string $link
 * @property boolean $admin
 * @property string $adminLink
 * @property boolean $consumed
 * @property boolean $trash
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property string $content
 * @property string $data
 */
class Notification extends \cmsgears\core\common\models\base\Entity implements IOwner {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use CreateModifyTrait;
	use DataTrait;
	use ResourceTrait;

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
			[ [ 'id', 'content', 'data' ], 'safe' ],
			// Text Limit
			[ [ 'parentType', 'type', 'ip' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ 'agent', 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ [ 'title', 'link', 'adminLink' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			// Other
			[ [ 'admin', 'consumed', 'trash' ], 'boolean' ],
			[ [ 'siteId', 'userId', 'createdBy', 'modifiedBy', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];

		return $rules;
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
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'ip' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_IP ),
			'agent' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_AGENT_BROWSER ),
			'link' => Yii::$app->notifyMessage->getMessage( NotifyGlobal::FIELD_FOLLOW ),
			'admin' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ADMIN ),
			'adminLink' => Yii::$app->notifyMessage->getMessage( NotifyGlobal::FIELD_FOLLOW_ADMIN ),
			'consumed' => 'Consumed',
			'trash' => 'Trash',
			'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONTENT )
		];
	}

	// CMG interfaces ------------------------

	// IOwner -----------------

	public function isOwner( $user = null, $strict = false ) {

		if( !isset( $user ) && !$strict ) {

			$user	= Yii::$app->user->getIdentity();
		}

		if( isset( $user ) ) {

			return $this->userId == $user->id;
		}

		return false;
	}

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Notification --------------------------

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

	public function toHtml() {

		$content	= "<li class='new'>";

		if( $this->isConsumed() ) {

			$content	= "<li class='consumed'>";
		}

		if( $this->isTrash() ) {

			$content	= "<li class='trash'>";
		}

		if( !empty( $this->link ) ) {

			$link		 = Url::toRoute( [ $this->link ], true );
			$content	.= "<a href='$link'>$this->content</a></li>";
		}
		else {

			$content	.= "$this->content</li>";
		}

		return $content;
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return NotifyTables::TABLE_NOTIFICATION;
	}

	// CMG parent classes --------------------

	// Notification --------------------------

	// Read - Query -----------

	public static function queryWithHasOne( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'user', 'creator', 'modifier' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	public static function queryWithUser( $config = [] ) {

		$config[ 'relations' ]	= [ 'user' ];

		return parent::queryWithAll( $config );
	}

	public static function queryByUserId( $userId ) {

		return static::find()->where( 'userId=:uid', [ ':uid' => $userId ] );
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
