<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\notify\common\models\mappers;

// Yii Imports
use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\Mapper;
use cmsgears\core\common\models\entities\User;
use cmsgears\notify\common\models\base\NotifyTables;
use cmsgears\notify\common\models\entities\Event;


/**
 * EventParticipant maps the event and it's participants i.e. users.
 *
 * @property integer $id
 * @property integer $eventId
 * @property integer $userId
 * @property boolean $active
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 *
 * @since 1.0.0
 */
class EventParticipant extends Mapper {

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

	/**
	 * @inheritdoc
	 */
    public function behaviors() {

        return [
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
			[ [ 'eventId', 'userId' ], 'required' ],
			// Unique
			[ [ 'eventId', 'userId' ], 'unique', 'targetAttribute' => [ 'eventId', 'userId' ], 'comboNotUnique' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_EXIST ) ],
			// Other
			[ 'active', 'boolean' ],
			[ [ 'eventId', 'userId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];

		return $rules;
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

	/**
	 * Return the corresponding event associated with the participant.
	 *
	 * @return \cmsgears\notify\common\models\entities\Event
	 */
	public function getEvent() {

		return $this->hasOne( Event::class, [ 'id' => 'eventId' ] );
	}

	/**
	 * Return the corresponding user associated with the participant.
	 *
	 * @return \cmsgears\core\common\models\entities\User
	 */
	public function getUser() {

		return $this->hasOne( User::class, [ 'id' => 'userId' ] );
	}

	/**
	 * Returns string representation of active flag.
	 *
	 * @return string
	 */
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

		return NotifyTables::getTableName( NotifyTables::TABLE_EVENT_PARTICIPANT );
	}

	// CMG parent classes --------------------

	// EventParticipant ----------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'event', 'user' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the participant with event.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with event.
	 */
	public static function queryWithEvent( $config = [] ) {

		$config[ 'relations' ]	= [ 'event' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the participant with user.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with user.
	 */
	public static function queryWithUser( $config = [] ) {

		$config[ 'relations' ]	= [ 'user' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

	/**
	 * Delete all the participant models specific to given event id.
	 *
	 * @param integer $eventId
	 * @return integer Number of rows.
	 */
	public static function deleteByEventId( $eventId ) {

		return self::deleteAll( 'eventId=:id', [ ':id' => $eventId ] );
	}

	/**
	 * Delete all the participant models specific to given user id.
	 *
	 * @param integer $userId
	 * @return integer Number of rows.
	 */
	public static function deleteByUserId( $userId ) {

		return self::deleteAll( 'userId=:id', [ ':id' => $userId ] );
	}
}
