<?php
// Yii Imports
use yii\helpers\Url;

// CMG Imports
use cmsgears\notify\common\models\entities\Event;

use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Events | ' . $coreProperties->getSiteTitle();

// Templates
$moduleTemplates	= '@cmsgears/module-notify/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => false, 'addUrl' => 'create', 'data' => [ ],
	'title' => 'Events', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'name' => 'Name', 'content' => 'Content' ],
	'sortColumns' => [
		'name' => 'Name', 'slug' => 'Slug', 'status' => 'Status', 'multi' => 'Multi Users',
		'cdate' => 'Created At', 'udate' => 'Updated At', 'sdate' => 'Scheduled At'
	],
	'filters' => [ 'status' => [ 'new' => 'New', 'trash' => 'Trash' ], 'multi' => [ 'multi' => 'Multi Users' ] ],
	'reportColumns' => [
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		'slug' => [ 'title' => 'Slug', 'type' => 'text' ],
		'content' => [ 'title' => 'Content', 'type' => 'text' ],
		'status' => [ 'title' => 'Status', 'type' => 'select', 'options' => Event::$statusMap ],
		'multi' => [ 'title' => 'Multi Users', 'type' => 'flag' ]
	],
	'bulkPopup' => 'popup-grid-bulk',
	'bulkActions' => [
		'status' => [ 'trash' => 'Trash' ],
		'model' => [ 'delete' => 'Delete' ]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, 'x3', 'x3', null, null, null, null, null, null, null, null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'name' => 'Name',
		'user' => [ 'title' => 'User', 'generate' => function( $model ) { return isset( $model->user ) ? $model->user->getName() : null; } ],
		'type' => 'Type',
		'multi' => [ 'title' => 'Old', 'generate' => function( $model ) { return $model->getMultiStr(); } ],
		'status' => [ 'title' => 'Trash', 'generate' => function( $model ) { return $model->getStatusStr(); } ],
		'preReminderCount' => 'Pre Count',
		'preReminderInterval' => [ 'title' => 'Pre Interval', 'generate' => function( $model ) { return $model->getPreIntervalStr(); } ],
		'postReminderCount' => 'Post Count',
		'postReminderInterval' => [ 'title' => 'Post Interval', 'generate' => function( $model ) { return $model->getPostIntervalStr(); } ],
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => '@themes/admin/views/templates/widget/grid',
	//'dataView' => "$moduleTemplates/grid/data/event",
	//'cardView' => "$moduleTemplates/grid/cards/event",
	//'actionView' => "$moduleTemplates/grid/actions/event"
]) ?>

<?= Popup::widget([
	'title' => 'Update Events', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'bulk',
	'data' => [ 'model' => 'Event', 'app' => 'main', 'controller' => 'crud', 'action' => 'bulk', 'url' => "notify/event/bulk" ]
]) ?>

<?= Popup::widget([
	'title' => 'Delete Event', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'delete',
	'data' => [ 'model' => 'Event', 'app' => 'main', 'controller' => 'crud', 'action' => 'delete', 'url' => "notify/event/delete?id=" ]
]) ?>
