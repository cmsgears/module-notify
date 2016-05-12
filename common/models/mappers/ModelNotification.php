<?php
namespace cmsgears\notify\common\models\mappers;

// Yii Imports
use \Yii;
use yii\helpers\Url;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\notify\common\config\NotifyGlobal;

use cmsgears\core\common\models\interfaces\IOwner;

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
 * @property string $title
 * @property string $type
 * @property string $ip
 * @property string $agent
 * @property string $follow
 * @property boolean $admin
 * @property string $adminFollow
 * @property boolean $status
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property string $content
 */
class ModelNotification extends \cmsgears\core\common\models\base\CmgModel implements IOwner {

	// Variables ---------------------------------------------------

	// Constants/Statics --

	const STATUS_NEW		=   0;
	const STATUS_CONSUMED	= 100;
	const STATUS_TRASH		= 200;

    public static $statusMap = [
        self::STATUS_NEW => 'New',
        self::STATUS_CONSUMED => 'Consumed',
        self::STATUS_TRASH => 'Trash'
    ];

    public static $revStatusMap = [
        'new' => self::STATUS_NEW,
        'consumed' => self::STATUS_CONSUMED,
        'trash' => self::STATUS_TRASH
    ];

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
            [ [ 'title', 'agent', 'follow', 'adminFollow' ], 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->extraLargeText ],
            [ [ 'admin' ], 'boolean' ],
            [ [ 'status' ], 'number', 'integerOnly' => true, 'min' => 0 ],
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
			'title' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'type' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'ip' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_IP ),
			'agent' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_AGENT_BROWSER ),
			'follow' => Yii::$app->cmgNotifyMessage->getMessage( NotifyGlobal::FIELD_FOLLOW ),
			'admin' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ADMIN ),
			'adminFollow' => Yii::$app->cmgNotifyMessage->getMessage( NotifyGlobal::FIELD_FOLLOW ),
			'status' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_STATUS ),
			'content' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_CONTENT )
		];
	}

	// IOwner ----------------------------

	public function isOwner( $user = null ) {

		if( !isset( $user ) ) {

			$user	= Yii::$app->user->getIdentity();
		}

		if( isset( $user ) ) {

			return $this->userId == $user->id;
		}

		return false;
	}

	// ModelNotification -----------------

	public function getUser() {

		return $this->hasOne( User::className(), [ 'id' => 'userId' ] );
	}

    public function getStatusStr() {

        return self::$statusMap[ $this->status ];
    }

    public function isNew() {

        return $this->status == self::STATUS_NEW;
    }

    public function isConsumed( $strict = false ) {

        if( $strict ) {

			return $this->status == self::STATUS_CONSUMED;
		}

		return $this->status >= self::STATUS_CONSUMED;
    }

    public function isTrash() {

        return $this->status == self::STATUS_TRASH;
    }

	public function toHtml() {

		$content	= "<li class='new'>";

		if( $this->isConsumed() ) {

			$content	= "<li class='consumed'>";
		}

		if( $this->isTrash() ) {

			$content	= "<li class='trash'>";
		}

		if( !empty( $this->follow ) ) {

			$link		 = Url::toRoute( [ $this->follow ], true );
			$content	.= "<a href='$link'>$this->content</a></li>";
		}
		else {

			$content	.= "$this->content</li>";
		}

		return $content;
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