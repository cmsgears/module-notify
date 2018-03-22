<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\notify\common\models\traits\entities;

// Yii Imports
use yii\db\Query;

// CMG Imports
use cmsgears\notify\common\models\base\NotifyTables;
use cmsgears\notify\common\models\entities\Notification;

/**
 * NotificationTrait provide methods to add notification feature to relevant models.
 *
 * @since 1.0.0
 */
trait NotificationTrait {

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii classes ---------------------------

	// CMG interfaces ------------------------

	// CMG classes ---------------------------

	// Validators ----------------------------

	// NotificationTrait ---------------------

	/**
	 * @inheritdoc
	 */
	public function getNotifications() {

		return $this->hasMany( Notification::className(), [ 'parentId' => 'id' ] )
			->where( "parentType='$this->modelType'" );
	}

	/**
	 * @inheritdoc
	 */
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
