<?php
namespace cmsgears\notify\common\models\resources;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\User;
use cmsgears\notify\common\models\base\NotifyTables;
use cmsgears\notify\common\models\entities\Event;


/**
 * EventReminder Entity
 *
 * @property long $id
 * @property long $eventId
 * @property long $userId
 * @property datetime $scheduledAt
 * @property short $status
 */
class EventReminder extends \cmsgears\core\common\models\base\Mapper {

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
            [ 'status', 'number', 'integerOnly' => true, 'min' => 0 ],
            [ [ 'scheduledAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {

        return [
            'eventId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_EVENT ),
            'userId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_USER ),
            'status' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_STATUS )
        ];
    }

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// EventParticipant ----------------------

    public function getEvent() {

        return $this->hasOne( Event::className(), [ 'id' => 'eventId' ] );
    }

    public function getUser() {

        return $this->hasOne( User::className(), [ 'id' => 'userId' ] );
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

	// SiteMember ----------------------------

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

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

    public static function deleteByEventId( $eventId ) {

        self::deleteAll( 'eventId=:id', [ ':id' => $eventId ] );
    }

    public static function deleteByUserId( $userId ) {

        self::deleteAll( 'userId=:id', [ ':id' => $userId ] );
    }
}
