<?php
namespace cmsgears\notify\common\models\mappers;

// Yii Imports
use \Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\notify\common\config\NotifyGlobal;

use cmsgears\core\common\models\entities\User;
use cmsgears\notify\common\models\base\NotifyTables;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * ModelNotification Entity
 *
 * @property integer $id
 * @property integer $userId
 * @property integer $parentId
 * @property string $parentType
 * @property string $type
 * @property string $ip
 * @property string $agent
 * @property boolean $admin
 * @property string $follow
 * @property boolean $consumed
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property string $content
 */
class ModelNotification extends \cmsgears\core\common\models\base\CmgModel {

	// Variables ---------------------------------------------------

	// Constants/Statics --

	// Public -------------

	// Private/Protected --

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance Methods --------------------------------------------

    // yii\base\Component ----------------

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

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

        $rules = [
            [ [ 'id', 'content' ], 'safe' ],
            [ [ 'parentType', 'type', 'ip' ], 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->mediumText ],
            [ [ 'agent', 'follow' ], 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->extraLargeText ],
            [ [ 'admin', 'consumed' ], 'boolean' ],
            [ [ 'userId', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];

		return $rules;
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'userId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_USER ),
			'parentId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'type' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'ip' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_IP ),
			'agent' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_AGENT_BROWSER ),
			'follow' => Yii::$app->cmgNotifyMessage->getMessage( NotifyGlobal::FIELD_FOLLOW ),
			'content' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_CONTENT )
		];
	}

	// ModelNotification -----------------

	public function getUser() {

		return $this->hasOne( User::className(), [ 'id' => 'userId' ] );
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return NotifyTables::TABLE_MODEL_NOTIFICATION;
	}

	// ModelNotification -----------------

	// Create -------------

	// Read ---------------

	// Update -------------

	// Delete ----

	/**
	 * Delete all entries related to a user
	 */
	public static function deleteByUserId( $userId ) {

		self::deleteAll( 'userId=:uid', [ ':uid' => $userId ] );
	}
}

?>