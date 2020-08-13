<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Events | ' . $coreProperties->getSiteTitle();
$apixBase		= $this->context->apixBase;

// View Templates
$moduleTemplates	= '@cmsgears/module-notify/admin/views/templates';
$themeTemplates		= '@themes/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => 'create', 'data' => [ ],
	'title' => 'Events', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [
		'name' => 'Name', 'title' => 'Title',
		'desc' => 'Description', 'content' => 'Content'
	],
	'sortColumns' => [
		'name' => 'Name', 'title' => 'Title',
		'multi' => 'Group', 'status' => 'Status',
		'template' => 'Template', 'sdate' => 'Scheduled At',
		'cdate' => 'Created At', 'udate' => 'Updated At'
	],
	'filters' => [
		'status' => [
			'new' => 'New', 'cancelled' => 'Cancelled',
			'active' => 'Active', 'expired' => 'Expired'
		],
		'model' => [ 'group' => 'Group' ]
	],
	'reportColumns' => [
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		'title' => [ 'title' => 'Title', 'type' => 'text' ],
		'desc' => [ 'title' => 'Description', 'type' => 'text' ],
		'content' => [ 'title' => 'Content', 'type' => 'text' ],
		'status' => [ 'title' => 'Status', 'type' => 'select', 'options' => $statusMap ],
		'group' => [ 'title' => 'Group', 'type' => 'flag' ]
	],
	'bulkPopup' => 'popup-grid-bulk',
	'bulkActions' => [
		'status' => [
			'cancel' => 'Cancel', 'activate' => 'Activate', 'expire' => 'Expire'
		],
		'model' => [
			'group' => 'Group', 'delete' => 'Delete'
		]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, 'x2', 'x3', null, null, null, null, null, null, 'x2', null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'name' => 'Name',
		'title' => 'Title',
		'group' => [ 'title' => 'Group', 'generate' => function( $model ) { return $model->getGroupStr(); } ],
		'status' => [ 'title' => 'Status', 'generate' => function( $model ) { return $model->getStatusStr(); } ],
		'preReminderCount' => 'Pre Count',
		'preReminderInterval' => [ 'title' => 'Pre Interval', 'generate' => function( $model ) { return $model->getPreIntervalStr(); } ],
		'postReminderCount' => 'Post Count',
		'postReminderInterval' => [ 'title' => 'Post Interval', 'generate' => function( $model ) { return $model->getPostIntervalStr(); } ],
		'scheduledAt' => 'Scheduled At',
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => "$themeTemplates/widget/grid",
	//'dataView' => "$moduleTemplates/grid/data/event",
	//'cardView' => "$moduleTemplates/grid/cards/event",
	'actionView' => "$moduleTemplates/grid/actions/event"
]) ?>

<?= Popup::widget([
	'title' => 'Apply Bulk Action', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'bulk',
	'data' => [ 'model' => 'Event', 'app' => 'grid', 'controller' => 'crud', 'action' => 'bulk', 'url' => "$apixBase/bulk" ]
])?>

<?= Popup::widget([
	'title' => 'Delete Event', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'delete',
	'data' => [ 'model' => 'Event', 'app' => 'grid', 'controller' => 'crud', 'action' => 'delete', 'url' => "$apixBase/delete?id=" ]
])?>
