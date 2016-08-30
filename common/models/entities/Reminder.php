<?php
namespace cmsgears\notify\common\models\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\notify\common\models\base\NotifyTables;

/**
 * Reminder Entity
 *
 * @property integer $id
 * @property integer $eventId
 * @property integer $userId
 * @property date $scheduledAt
 * @property short $status
 */
class Reminder extends \cmsgears\core\common\models\base\Entity {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------
	const STATUS_READ	= 0;
	const STATUS_UNREAD	= 1;

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

        $rules = [
            [ [ 'eventId', 'userId', 'scheduledAt' ], 'required' ],
            [ [ 'id', 'status' ], 'safe' ],
            [ [ 'scheduledAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];

		return $rules;
    }

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Reminder ---------------------------------
	public function getEvent() {

		return $this->hasOne( Event::className(), [ 'id' => 'eventId' ] );
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

	// Read - Find ------------

	public static function getReminders( $all = false ) {

		$user	= Yii::$app->user->getIdentity();

		if( isset ( $user ) ) {

			$reminders	= self::find()->where( 'scheduledAt <= NOW() AND userId='.$user->id );

			if( !$all ) {

				$reminders->andWhere( 'status='.self::STATUS_UNREAD );
			}

			$reminders	= $reminders->all();

			return $reminders;
		}
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

	public static function deleteAllByEventId( $eventId ) {

		return self::deleteAll( [ 'eventId' => $eventId ] );
	}
}
