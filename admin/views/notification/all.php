<?php
// Yii Imports
use yii\helpers\Url;

// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Notifications | ' . $coreProperties->getSiteTitle();
$apixBase		= $this->context->apixBase;

// View Templates
$moduleTemplates	= '@cmsgears/module-notify/admin/views/templates';
$themeTemplates		= '@themes/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => false, 'addUrl' => 'create', 'data' => [ ],
	'title' => 'Notifications', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'title' => 'Title', 'desc' => 'Description', 'content' => 'Content' ],
	'sortColumns' => [
		'title' => 'Title',
		'consumed' => 'Consumed', 'trash' => 'Trash',
		'cdate' => 'Created At', 'udate' => 'Updated At'
	],
	'filters' => [
		'consumed' => [ 'new' => 'New', 'read' => 'Read' ],
		'trash' => [ 'trash' => 'Trash' ]
	],
	'reportColumns' => [
		'title' => [ 'title' => 'Title', 'type' => 'text' ],
		'desc' => [ 'title' => 'Description', 'type' => 'text' ],
		'content' => [ 'title' => 'Content', 'type' => 'text' ],
		'consumed' => [ 'title' => 'Read', 'type' => 'flag' ],
		'trash' => [ 'title' => 'Trash', 'type' => 'flag' ]
	],
	'bulkPopup' => 'popup-grid-bulk',
	'bulkActions' => [
		'consumed' => [ 'new' => 'New', 'read' => 'Old' ],
		'trash' => [ 'trash' => 'Trash' ],
		'model' => [ 'delete' => 'Delete' ]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, 'x2', null, null, null, 'x8', null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'title' => 'Title',
		'alink' => [ 'title' => 'Follow', 'generate' => function( $model ) {
			return isset( $model->adminLink ) ? "<a href=\"" . Url::to( [ $model->adminLink ] . "\">View</a>", true ) : null;
		} ],
		'consumed' => [ 'title' => 'Read', 'generate' => function( $model ) { return $model->getConsumedStr(); } ],
		'trash' => [ 'title' => 'Trash', 'generate' => function( $model ) { return $model->getTrashStr(); } ],
		'content' => 'Content',
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => "$themeTemplates/widget/grid",
	//'dataView' => "$moduleTemplates/grid/data/notification",
	//'cardView' => "$moduleTemplates/grid/cards/notification",
	'actionView' => "$moduleTemplates/grid/actions/notification"
]) ?>

<?= Popup::widget([
	'title' => 'Apply Bulk Action', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid"  ), 'template' => 'bulk',
	'data' => [ 'model' => 'Notification', 'app' => 'grid', 'controller' => 'crud', 'action' => 'bulk', 'url' => "$apixBase/bulk" ]
]) ?>

<?= Popup::widget([
	'title' => 'Delete Notification', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'delete',
	'data' => [ 'model' => 'Notification', 'app' => 'grid', 'controller' => 'crud', 'action' => 'delete', 'url' => "$apixBase/delete?id=" ]
]) ?>
