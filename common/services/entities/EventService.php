<?php
namespace cmsgears\notify\common\services\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\notify\common\models\base\NotifyTables;

use cmsgears\notify\common\services\interfaces\entities\IEventService;

/**
 * The class EventService is base class to perform database activities for Event Entity.
 */
class EventService extends \cmsgears\core\common\services\base\EntityService implements IEventService {

    // Variables ---------------------------------------------------

    // Globals -------------------------------

    // Constants --------------

    // Public -----------------

    public static $modelClass	= '\cmsgears\notify\common\models\entities\Event';

    public static $modelTable	= NotifyTables::TABLE_EVENT;

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

    // EventService -------------------

    // Data Provider ------

    // Read ---------------

    // Read - Models ---
    public function getNewEvents() {

        $modelClass	= static::$modelClass;

        return $modelClass::findNewEvents();
    }

    public function getByParentId( $parentId ) {

        $modelClass	= static::$modelClass;

        return $modelClass::findByParentId( $parentId );
    }

    // Read - Lists ----

    // Read - Maps -----

    // Read - Others ---

    // Create -------------

    // Update -------------

    // Delete -------------

    // Static Methods ----------------------------------------------

    // CMG parent classes --------------------

    // EventService -------------------

    // Data Provider ------

    // Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

    // Read - Others ---

    // Create -------------

    // Update -------------

    // Delete -------------
}
