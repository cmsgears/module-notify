<?php
// Yii Imports
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\common\utilities\CodeGenUtil;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'All Events | ' . $coreProperties->getSiteTitle();

// Data
$pagination		= $dataProvider->getPagination();
$models			= $dataProvider->getModels();

// Searching
$keywords		= Yii::$app->request->getQueryParam( 'keywords' );

// Sorting
$sortOrder		= Yii::$app->request->getQueryParam( 'sort' );

if( !isset( $sortOrder ) ) {

	$sortOrder	= '';
}
?>
<div class="row header-content">
	<div class="col-small col15x10 header-actions">
		<span class="frm-icon-element element-small">
			<i class="cmti cmti-plus"></i>
			<?= Html::a( 'Add', [ 'create' ], [ 'class' => 'btn' ] ) ?>
		</span>
	</div>
	<div class="col-small col15x5 header-search">
		<input id="search-terms" class="element-large" type="text" name="search" value="<?= $keywords ?>">
		<span class="frm-icon-element element-medium">
			<i class="cmti cmti-search"></i>
			<button id="btn-search">Search</button>
		</span>
	</div>
</div>
<div class="row header-content">
	<div class="col col12x8 header-actions">
		<span class="bold">Sort By:</span>
		<span class="wrap-sort">
			<?= $dataProvider->sort->link( 'name', [ 'class' => 'sort btn btn-medium' ] ) ?>
			<?= $dataProvider->sort->link( 'cdate', [ 'class' => 'sort btn btn-medium' ] ) ?>
			<?= $dataProvider->sort->link( 'udate', [ 'class' => 'sort btn btn-medium' ] ) ?>
		</span>
	</div>
	<div class="col col12x4 header-actions align align-right">
		<span class="wrap-filters"></span>
	</div>
</div>
<div class="data-grid">
	<div class="row grid-header">
		<div class="col col12x6 info">
			<?=CodeGenUtil::getPaginationDetail( $dataProvider ) ?>
		</div>
		<div class="col col12x6 pagination">
			<?= LinkPager::widget( [ 'pagination' => $pagination, 'options' => [ 'class' => 'pagination-basic' ] ] ); ?>
		</div>
	</div>
	<div class="grid-content">
		<table>
			<thead>
				<tr>
					<th>Name</th>
					<th>Status</th>
					<th>Type</th>
					<th>Multi</th>
					<th>Pre Count</th>
					<th>Pre Interval</th>
					<th>Post Count</th>
					<th>Post Interval</th>
					<th>Pre Unit</th>
					<th>Post Unit</th>
					<th>Created At</th>
					<th>Updated At</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php

					foreach( $models as $event ) {

						$id 	= $event->id;
				?>
					<tr>
						<td><?= $event->name ?></td>
						<td><?= $event->getStatusStr() ?></td>
						<td><?= $event->type ?></td>
						<td><?= $event->getMultiUserStr() ?></td>
						<td><?= $event->preReminderCount ?></td>
						<td><?= $event->preReminderInterval ?></td>
						<td><?= $event->postReminderCount ?></td>
						<td><?= $event->postReminderInterval ?></td>
						<td><?= $event->preIntervalUnit ?></td>
						<td><?= $event->postIntervalUnit ?></td>
						<td><?= $event->createdAt ?></td>
						<td><?= $event->modifiedAt ?></td>
						<td class="actions"></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<div class="row grid-header">
		<div class="col col12x6 info">
			<?=CodeGenUtil::getPaginationDetail( $dataProvider ) ?>
		</div>
		<div class="col col12x6 pagination">
			<?= LinkPager::widget( [ 'pagination' => $pagination, 'options' => [ 'class' => 'pagination-basic' ] ] ); ?>
		</div>
	</div>
</div>