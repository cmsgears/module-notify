<?php
// Yii Imports
use yii\helpers\Url;

// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Notifications | ' . $coreProperties->getSiteTitle();

// Templates
$moduleTemplates	= '@cmsgears/module-notify/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => false, 'addUrl' => 'create', 'data' => [ ],
	'title' => 'Notifications', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'title' => 'Title', 'content' => 'Content' ],
	'sortColumns' => [
		'title' => 'Title', 'ip' => 'IP', 'agent' => 'Agent', 'consumed' => 'Consumed', 'trash' => 'Trash',
		'cdate' => 'Created At', 'udate' => 'Updated At'
	],
	'filters' => [ 'consumed' => [ 'new' => 'New', 'read' => 'Old' ], 'trash' => [ 'trash' => 'Trash' ] ],
	'reportColumns' => [
		'title' => [ 'title' => 'Title', 'type' => 'text' ],
		'content' => [ 'title' => 'Content', 'type' => 'text' ],
		'consumed' => [ 'title' => 'Old', 'type' => 'flag' ],
		'trash' => [ 'title' => 'Trash', 'type' => 'flag' ]
	],
	'bulkPopup' => 'popup-grid-bulk',
	'bulkActions' => [
		'consumed' => [ 'new' => 'New', 'read' => 'Old' ],
		'trash' => [ 'trash' => 'Trash' ],
		'model' => [ 'delete' => 'Delete' ]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, 'x2', 'x8', null, null, null, null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'title' => 'Title',
		//'ip' => 'IP',
		//'agent' => 'Agent',
		'content' => 'Content',
		'link' => [ 'title' => 'Link', 'generate' => function( $model ) { return isset( $model->adminLink ) ? "<a href=\"" . Url::toRoute( [ $model->adminLink ], true ) . "\">View</a>" : null; } ],
		'consumed' => [ 'title' => 'Old', 'generate' => function( $model ) { return $model->getConsumedStr(); } ],
		'trash' => [ 'title' => 'Trash', 'generate' => function( $model ) { return $model->getTrashStr(); } ],
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => '@themes/admin/views/templates/widget/grid',
	//'dataView' => "$moduleTemplates/grid/data/notification",
	//'cardView' => "$moduleTemplates/grid/cards/notification",
	'actionView' => "$moduleTemplates/grid/actions/notification"
]) ?>

<?= Popup::widget([
	'title' => 'Update Notifications', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'bulk',
	'data' => [ 'model' => 'Notification', 'app' => 'main', 'controller' => 'crud', 'action' => 'bulk', 'url' => "notify/notification/bulk" ]
]) ?>

<?= Popup::widget([
	'title' => 'Delete Notification', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'delete',
	'data' => [ 'model' => 'Notification', 'app' => 'main', 'controller' => 'crud', 'action' => 'delete', 'url' => "notify/notification/delete?id=" ]
]) ?>
