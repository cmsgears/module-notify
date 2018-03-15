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

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\notify\common\config\NotifyGlobal;

use cmsgears\core\common\models\interfaces\base\IOwner;
use cmsgears\core\common\models\interfaces\resources\IData;

use cmsgears\core\common\models\base\Resource;
use cmsgears\core\common\models\entities\User;
use cmsgears\notify\common\models\entities\Event;
use cmsgears\notify\common\models\base\NotifyTables;

use cmsgears\core\common\models\traits\base\UserOwnerTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;

/**
 * Reminder will be triggered based on event configuration.
 *
 * @property integer $id
 * @property integer $siteId
 * @property integer $eventId
 * @property integer $userId
 * @property string $title
 * @property string $description
 * @property string $link
 * @property boolean $admin
 * @property string $adminLink
 * @property boolean $consumed
 * @property boolean $trash
 * @property date $scheduledAt
 * @property string $content
 * @property string $data
 * @property string $gridCache
 * @property boolean $gridCacheValid
 * @property datetime $gridCachedAt
 */
class EventReminder extends Resource implements IData, IOwner {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType	= NotifyGlobal::TYPE_REMINDER;

	// Private ----------------

	// Traits ------------------------------------------------------

	use DataTrait;
	use UserOwnerTrait;

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

		// Model Rules
		$rules = [
			// Required, Safe
			[ [ 'eventId', 'scheduledAt' ], 'required' ],
			[ [ 'id', 'content', 'data', 'gridCache' ], 'safe' ],
			// Text Limit
			[ 'title', 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			[ [ 'link', 'adminLink' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxxLargeText ],
			[ 'description', 'string', 'min' => 1, 'max' => Yii::$app->core->xtraLargeText ],
			// Other
			[ [ 'admin', 'consumed', 'trash', 'gridCacheValid' ], 'boolean' ],
			[ [ 'eventId', 'userId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ 'scheduledAt', 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'eventId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_E ),
			'userId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_USER ),
			'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'link' => Yii::$app->notifyMessage->getMessage( NotifyGlobal::FIELD_FOLLOW ),
			'admin' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ADMIN ),
			'adminLink' => Yii::$app->notifyMessage->getMessage( NotifyGlobal::FIELD_FOLLOW_ADMIN ),
			'consumed' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONSUMED ),
			'trash' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TRASH ),
			'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONTENT ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA ),
			'gridCache' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_GRID_CACHE )
		];
	}

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Reminder ---------------------------------

	/**
	 * Returns the event associated with the reminder.
	 *
	 * @return \cmsgears\notify\common\models\entities\Event
	 */
	public function getEvent() {

		return $this->hasOne( Event::class, [ 'id' => 'eventId' ] );
	}

	/**
	 * Returns the host user associated with the notification.
	 *
	 * @return \cmsgears\core\common\models\entities\User
	 */
	public function getUser() {

		return $this->hasOne( User::class, [ 'id' => 'userId' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return NotifyTables::getTableName( NotifyTables::TABLE_EVENT_REMINDER );
	}

	// CMG parent classes --------------------

	// Reminder ---------------------------------

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
	 * Return query to find the reminder with event.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with event.
	 */
	public static function queryWithEvent( $config = [] ) {

		$config[ 'relations' ]	= [ 'event' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the reminder with user.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with user.
	 */
	public static function queryWithUser( $config = [] ) {

		$config[ 'relations' ]	= [ 'user' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the reminder using user id.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query using user id.
	 */
	public static function queryByUserId( $userId ) {

		return static::find()->where( 'userId=:uid', [ ':uid' => $userId ] );
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
