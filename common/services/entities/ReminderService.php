<?php
namespace cmsgears\notify\common\services\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\notify\common\models\base\NotifyTables;

use cmsgears\notify\common\services\interfaces\entities\IReminderService;

/**
 * The class ReminderService is base class to perform database activities for Reminder Entity.
 */
class ReminderService extends \cmsgears\core\common\services\base\EntityService implements IReminderService {

    // Variables ---------------------------------------------------

    // Globals -------------------------------

    // Constants --------------

    // Public -----------------

    public static $modelClass	= '\cmsgears\notify\common\models\entities\Reminder';

    public static $modelTable	= NotifyTables::TABLE_EVENT_REMINDER;

    // Protected --------------

    // Variables -----------------------------

    // Public -----------------

    // Protected --------------

    // Private ----------------

    // Traits ------------------------------------------------------

    // Constructor and Initialisation ------------------------------

    // Instance methods --------------------------------------------

    // Yii parent classes --------------------

    // yii\base\Component -----

    // CMG interfaces ------------------------

    // CMG parent classes --------------------

    // ReminderService -----------------------

    // Data Provider ------

    // Read ---------------

    // Read - Models ---

    public function getReminders( $all = false  ) {

        $modelClass	= static::$modelClass;

        return $modelClass::getReminders( $all );
    }

    // Read - Lists ----

    // Read - Maps -----

    // Read - Others ---

    // Create -------------

    // Update -------------

    public function setRead( $id ) {

        $model	= self::findById( $id );
        $user	= Yii::$app->user->getIdentity();

        if( isset( $model ) && isset( $user ) && $model->userId == $user->id ) {

            $modelClass	= self::$modelClass;

            $model->status	= $modelClass::STATUS_READ;
            $model->update();

            return true;
        }

        return false;
    }

    // Delete -------------

    // Static Methods ----------------------------------------------

    // CMG parent classes --------------------

    // ReminderService -------------------

    // Data Provider ------

    // Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

    // Read - Others ---

    // Create -------------

    // Update -------------

    // Delete -------------

    public function deleteAllByEventId( $eventId ) {

        $modelClass	= static::$modelClass;

        return $modelClass::deleteAllByEventId( $eventId );
    }
}
