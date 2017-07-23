<?php
// Yii Imports
use yii\helpers\Url;

// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Activities | ' . $coreProperties->getSiteTitle();

// Templates
$moduleTemplates	= '@cmsgears/module-notify/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => false, 'addUrl' => 'create', 'data' => [ ],
	'title' => 'Activities', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'title' => 'Title', 'content' => 'Content' ],
	'sortColumns' => [
		'title' => 'Title', 'ip' => 'IP', 'agent' => 'Agent',
		'cdate' => 'Created At', 'udate' => 'Updated At'
	],
	//'filters' => [ 'type' => [ 'user' => 'User' ] ],
	'reportColumns' => [
		'title' => [ 'title' => 'Title', 'type' => 'text' ],
		'content' => [ 'title' => 'Content', 'type' => 'text' ]
	],
	'bulkPopup' => 'popup-grid-bulk',
	'bulkActions' => [
		'model' => [ 'delete' => 'Delete' ]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, 'x3', null, null, 'x8', null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'title' => 'Title',
		'ip' => 'IP',
		'agent' => 'Agent',
		'content' => 'Content',
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => '@themes/admin/views/templates/widget/grid',
	//'dataView' => "$moduleTemplates/grid/data/activity",
	//'cardView' => "$moduleTemplates/grid/cards/activity",
	//'actionView' => "$moduleTemplates/grid/actions/activity"
]) ?>

<?= Popup::widget([
	'title' => 'Update Activities', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'bulk',
	'data' => [ 'model' => 'Activity', 'app' => 'main', 'controller' => 'crud', 'action' => 'bulk', 'url' => "notify/activity/bulk" ]
]) ?>

<?= Popup::widget([
	'title' => 'Delete Activity', 'size' => 'medium',
	'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'delete',
	'data' => [ 'model' => 'Activity', 'app' => 'main', 'controller' => 'crud', 'action' => 'delete', 'url' => "notify/activity/delete?id=" ]
]) ?>
