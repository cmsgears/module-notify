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
	'searchColumns' => [ 'name' => 'Name', 'title' => 'Title', 'desc' => 'Description', 'content' => 'Content' ],
	'sortColumns' => [
		'name' => 'Name', 'icon' => 'Icon', 'title' => 'Title',
		'template' => 'Template', 'multi' => 'Group', 'status' => 'Status',
		'cdate' => 'Created At', 'udate' => 'Updated At', 'sdate' => 'Scheduled At'
	],
	'filters' => [
		'status' => [ 'new' => 'New', 'trash' => 'Trash' ],
		'model' => [ 'multi' => 'Group' ]
	],
	'reportColumns' => [
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		'title' => [ 'title' => 'Title', 'type' => 'text' ],
		'desc' => [ 'title' => 'Description', 'type' => 'text' ],
		'content' => [ 'title' => 'Content', 'type' => 'text' ],
		'status' => [ 'title' => 'Status', 'type' => 'select', 'options' => $statusMap ],
		'multi' => [ 'title' => 'Group', 'type' => 'flag' ]
	],
	'bulkPopup' => 'popup-grid-bulk',
	'bulkActions' => [
		'status' => [ 'trash' => 'Trash' ],
		'model' => [ 'delete' => 'Delete' ]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, 'x3', 'x4', null, null, null, null, null, null, null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'name' => 'Name',
		'title' => 'Title',
		'multi' => [ 'title' => 'Group', 'generate' => function( $model ) { return $model->getMultiStr(); } ],
		'status' => [ 'title' => 'Status', 'generate' => function( $model ) { return $model->getStatusStr(); } ],
		'preReminderCount' => 'Pre Count',
		'preReminderInterval' => [ 'title' => 'Pre Interval', 'generate' => function( $model ) { return $model->getPreIntervalStr(); } ],
		'postReminderCount' => 'Post Count',
		'postReminderInterval' => [ 'title' => 'Post Interval', 'generate' => function( $model ) { return $model->getPostIntervalStr(); } ],
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => "$themeTemplates/widget/grid",
	//'dataView' => "$moduleTemplates/grid/data/event",
	//'cardView' => "$moduleTemplates/grid/cards/event",
	//'actionView' => "$moduleTemplates/grid/actions/event"
]) ?>

<?= Popup::widget([
	'title' => 'Apply Bulk Action', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'bulk',
	'data' => [ 'model' => 'Event', 'app' => 'grid', 'controller' => 'crud', 'action' => 'bulk', 'url' => "$apixBase/bulk" ]
]) ?>

<?= Popup::widget([
	'title' => 'Delete Event', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'delete',
	'data' => [ 'model' => 'Event', 'app' => 'grid', 'controller' => 'crud', 'action' => 'delete', 'url' => "$apixBase/delete?id=" ]
]) ?>
