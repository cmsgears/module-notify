<?php
namespace cmsgears\notify\common\models\resources;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\notify\common\models\base\NotifyTables;

use cmsgears\core\common\models\interfaces\IOwner;

use cmsgears\core\common\models\entities\User;
use cmsgears\notify\common\models\entities\Event;

use cmsgears\core\common\models\traits\resources\DataTrait;

/**
 * Reminder Entity
 *
 * @property integer $id
 * @property integer $siteId
 * @property integer $eventId
 * @property integer $userId
 * @property string $title
 * @property string $link
 * @property boolean $admin
 * @property string $adminLink
 * @property boolean $consumed
 * @property boolean $trash
 * @property date $scheduledAt
 * @property string $content
 * @property string $data
 */
class EventReminder extends \cmsgears\core\common\models\base\Entity implements IOwner {

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

	use DataTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Model ---------

	/**
	 * @inheritdoc
	 */
	public function rules() {

		$rules = [
			// Required, Safe
			[ [ 'eventId', 'scheduledAt' ], 'required' ],
			[ [ 'id', 'siteId' ], 'safe' ],
			// Text Limit
			[ [ 'title', 'link', 'adminLink' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			// Other
			[ [ 'admin', 'consumed', 'trash' ], 'boolean' ],
			[ [ 'scheduledAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ],
			[ [ 'eventId', 'userId' ], 'number', 'integerOnly' => true, 'min' => 1 ]
		];

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'eventId' => 'Event',
			'userId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_USER ),
			'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'link' => Yii::$app->notifyMessage->getMessage( NotifyGlobal::FIELD_FOLLOW ),
			'admin' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ADMIN ),
			'adminLink' => Yii::$app->notifyMessage->getMessage( NotifyGlobal::FIELD_FOLLOW_ADMIN ),
			'consumed' => 'Consumed',
			'trash' => 'Trash',
			'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONTENT )
		];
	}

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

	// Reminder ---------------------------------

	public function getEvent() {

		return $this->hasOne( Event::className(), [ 'id' => 'eventId' ] );
	}

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

		return NotifyTables::TABLE_EVENT_REMINDER;
	}

	// CMG parent classes --------------------

	// Reminder ---------------------------------

	// Read - Query -----------

	public static function queryWithHasOne( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'event', 'user' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	public static function queryWithEvent( $config = [] ) {

		$config[ 'relations' ]	= [ 'event' ];

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

	public static function deleteByEventId( $eventId ) {

		return self::deleteAll( [ 'eventId' => $eventId ] );
	}

	/**
	 * Delete all entries related to a user
	 */
	public static function deleteByUserId( $userId ) {

		self::deleteAll( 'userId=:uid', [ ':uid' => $userId ] );
	}
}
