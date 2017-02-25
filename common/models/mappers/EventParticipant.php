<?php
namespace cmsgears\notify\common\models\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\User;
use cmsgears\notify\common\models\base\NotifyTables;
use cmsgears\notify\common\models\entities\Event;


/**
 * EventParticipant Entity
 *
 * @property long $id
 * @property long $eventId
 * @property long $userId
 * @property boolean $active
 */
class EventParticipant extends \cmsgears\core\common\models\base\Mapper {

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

		return [
			[ [ 'eventId', 'userId' ], 'required' ],
			[ [ 'eventId', 'userId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'eventId', 'userId' ], 'unique', 'targetAttribute' => [ 'eventId', 'userId' ] ],
			[ 'active', 'boolean' ]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'eventId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_EVENT ),
			'userId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_USER ),
			'active' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ACTIVE )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// EventParticipant ----------------------

	public function getEvent() {

		return $this->hasOne( Event::className(), [ 'id' => 'eventId' ] );
	}

	public function getParticipant() {

		return $this->hasOne( User::className(), [ 'id' => 'userId' ] );
	}

	public function getActiveStr() {

		return Yii::$app->formatter->asBoolean( $this->active );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return NotifyTables::TABLE_EVENT_PARTICIPANT;
	}

	// CMG parent classes --------------------

	// SiteMember ----------------------------

	// Read - Query -----------

	public static function queryWithHasOne( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'event', 'participant' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	public static function queryWithEvent( $config = [] ) {

		$config[ 'relations' ]	= [ 'event' ];

		return parent::queryWithAll( $config );
	}

	public static function queryWithParticipant( $config = [] ) {

		$config[ 'relations' ]	= [ 'participant' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

	public static function deleteByEventId( $eventId ) {

		self::deleteAll( 'eventId=:id', [ ':id' => $eventId ] );
	}

	public static function deleteByUserId( $participantId ) {

		self::deleteAll( 'userId=:id', [ ':id' => $participantId ] );
	}
}
