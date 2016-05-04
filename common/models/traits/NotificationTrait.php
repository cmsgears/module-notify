<?php
namespace cmsgears\notify\common\models\traits;

// Yii Imports
use \Yii;
use \yii\db\Query;

// CMG Imports
use cmsgears\notify\common\models\base\NotifyTables;
use cmsgears\notify\common\models\mappers\ModelNotification;

/**
 * NotificationTrait can be used to add notification feature to relevant models.
 */
trait NotificationTrait {

	public function getModelNotifications() {

		return ModelNotification::findByParent( $this->id, $this->parentType );
	}

    public function getStatusCounts() {

        $returnArr      = [ 0 => 0, 1 => 0 ];

        $notifyTable   = NotifyTables::TABLE_MODEL_NOTIFICATION;
        $query          = new Query();

        $query->select( [ 'consumed', 'count(id) as total' ] )
                ->from( $notifyTable )
                ->where( [ 'parentType' => $this->parentType, 'parentId' => $this->id ] )
                ->groupBy( 'consumed' );

        $counts     = $query->all();
        $counter    = 0;

        foreach ( $counts as $count ) {

            $returnArr[ $count[ 'consumed' ] ] = $count[ 'total' ];
        }

        foreach( $returnArr as $val ) {

            $counter    += $val;
        }

        $returnArr[ 'all' ] = $counter;

        return $returnArr;
    }
}

?>