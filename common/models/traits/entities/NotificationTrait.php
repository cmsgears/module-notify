<?php
namespace cmsgears\notify\common\models\traits\entities;

// Yii Imports
use yii\db\Query;

// CMG Imports
use cmsgears\notify\common\models\base\NotifyTables;
use cmsgears\notify\common\models\entities\Notification;

/**
 * NotificationTrait can be used to add notification feature to relevant models.
 */
trait NotificationTrait {

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii classes ---------------------------

	// CMG interfaces ------------------------

	// CMG classes ---------------------------

	// Validators ----------------------------

	// NotificationTrait ---------------------

	public function getNotifications() {

		return $this->hasMany( Notification::className(), [ 'parentId' => 'id' ] )
					->where( "parentType='$this->modelType'" );
	}

	public function getNotificationStatusCounts() {

		$returnArr      = [ Notification::STATUS_NEW => 0, Notification::STATUS_CONSUMED => 0, Notification::STATUS_TRASH => 0 ];

		$notifyTable   	= NotifyTables::TABLE_NOTIFICATION;
		$query          = new Query();

		$query->select( [ 'status', 'count(id) as total' ] )
				->from( $notifyTable )
				->where( [ 'parentId' => $this->id, 'parentType' => $this->modelType ] )
				->groupBy( 'status' );

		$counts     = $query->all();
		$counter    = 0;

		foreach ( $counts as $count ) {

			$returnArr[ $count[ 'status' ] ] = $count[ 'total' ];
		}

		foreach( $returnArr as $val ) {

			$counter    += $val;
		}

		$returnArr[ 'all' ] = $counter;

		return $returnArr;
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// NotificationTrait ---------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
