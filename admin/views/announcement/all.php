<?php
// Yii Imports
use yii\helpers\Url;

// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Announcements | ' . $coreProperties->getSiteTitle();
$apixBase		= $this->context->apixBase;

// View Templates
$moduleTemplates	= '@cmsgears/module-notify/admin/views/templates';
$themeTemplates		= '@themes/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => 'create', 'data' => [ ],
	'title' => 'Announcements', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'title' => 'Title', 'desc' => 'Description', 'content' => 'Content' ],
	'sortColumns' => [
		'title' => 'Title', 'status' => 'Status', 'access' => 'Access',
		'cdate' => 'Created At', 'udate' => 'Updated At'
	],
	'filters' => [
		'status' => [ 'approved' => 'Approved', 'active' => 'Active', 'paused' => 'Paused', 'expired' => 'Expired' ],
		'access' => [ 'appact' => 'App Act', 'admin' => 'Admin', 'appadmin' => 'App & Admin' ]
	],
	'reportColumns' => [
		'title' => [ 'title' => 'Title', 'type' => 'text' ],
		'desc' => [ 'title' => 'Description', 'type' => 'text' ],
		'content' => [ 'title' => 'Content', 'type' => 'text' ],
		'status' => [ 'title' => 'Status', 'type' => 'select', 'options' => $statusMap ],
		'access' => [ 'title' => 'Access', 'type' => 'select', 'options' => $accessMap ],
	],
	'bulkPopup' => 'popup-grid-bulk',
	'bulkActions' => [
		'status' => [ 'approved' => 'Approve', 'active' => 'Activate', 'paused' => 'Pause', 'expired' => 'Expire' ],
		'model' => [ 'delete' => 'Delete' ]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, 'x4', null, null, null, null, 'x5', null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'title' => 'Title',
		'status' => [ 'title' => 'Status', 'generate' => function( $model ) { return $model->getStatusStr(); } ],
		'access' => [ 'title' => 'Access', 'generate' => function( $model ) { return $model->getAccessStr(); } ],
		'app' => [ 'title' => 'App', 'generate' => function( $model ) use( $coreProperties ) {
			return !empty( $model->adminLink ) ? "<a href=\"" . $coreProperties->getSiteUrl() . '/' . $model->link . "\">View</a>" : null;
		}],
		'admin' => [ 'title' => 'Admin', 'generate' => function( $model ) {
			return !empty( $model->adminLink ) ? "<a href=\"" . Url::to( [ $model->adminLink ] . "\">View</a>", true ) : null;
		}],
		'description' => 'Description',
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
	'data' => [ 'model' => 'Event', 'app' => 'main', 'controller' => 'crud', 'action' => 'bulk', 'url' => "$apixBase/bulk" ]
]) ?>

<?= Popup::widget([
	'title' => 'Delete Event', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'delete',
	'data' => [ 'model' => 'Event', 'app' => 'main', 'controller' => 'crud', 'action' => 'delete', 'url' => "$apixBase/delete?id=" ]
]) ?>
