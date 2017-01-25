<?php
// Yii Imports
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\common\utilities\CodeGenUtil;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'All Notifications | ' . $coreProperties->getSiteTitle();

// Data
$pagination		= $dataProvider->getPagination();
$models			= $dataProvider->getModels();

// Searching
$searchTerms	= Yii::$app->request->getQueryParam( 'search' );

// Sorting
$sortOrder		= Yii::$app->request->getQueryParam( 'sort' );

if( !isset( $sortOrder ) ) {

	$sortOrder	= '';
}

// Filters
$statusFilter	= Yii::$app->request->getQueryParam( 'status' );
?>
<div class="header-content clearfix">
	<div class="header-actions col15x10">
		<h5>Notifications</h5>
	</div>
	<div class="header-search col15x5">
		<input id="search-terms" class="element-large" type="text" name="search" value="<?= $searchTerms ?>">
		<span class="frm-icon-element element-medium">
			<i class="cmti cmti-search"></i>
			<button id="btn-search">Search</button>
		</span>
	</div>
</div>
<div class="header-content clearfix">
	<div class="header-actions col12x6">
		<span class="bold">Sort By:</span>
		<span class="wrap-sort">
			<?= $dataProvider->sort->link( 'title', [ 'class' => 'sort btn btn-medium' ] ) ?>
			<?= $dataProvider->sort->link( 'cdate', [ 'class' => 'sort btn btn-medium' ] ) ?>
			<?= $dataProvider->sort->link( 'udate', [ 'class' => 'sort btn btn-medium' ] ) ?>
		</span>
	</div>
	<div class="header-actions col12x6 align align-right">
		<span class="wrap-filters">
			<span class="filter filter-text cmti cmti-listing <?= strcmp( $statusFilter, 'all' ) == 0 ? 'active' : '' ?>" column="status" filter="all" title="All"></span>
			<span class="filter filter-text cmti cmti-envelope <?= strcmp( $statusFilter, 'new' ) == 0 ? 'active' : '' ?>" column="status" filter="new" title="New"></span>
			<span class="filter filter-text cmti cmti-envelope-o <?= strcmp( $statusFilter, 'consumed' ) == 0 ? 'active' : '' ?>" column="status" filter="consumed" title="Consumed"></span>
			<span class="filter filter-text cmti cmti-bin <?= strcmp( $statusFilter, 'trash' ) == 0 ? 'active' : '' ?>" column="status" filter="trash" title="Trash"></span>
		</span>
	</div>
</div>

<div class="data-grid">
	<div class="grid-header clearfix">
		<div class="col12x6 info">
			<?=CodeGenUtil::getPaginationDetail( $dataProvider ) ?>
		</div>
		<div class="col12x6 pagination">
			<?= LinkPager::widget( [ 'pagination' => $pagination, 'options' => [ 'class' => 'pagination-basic' ] ] ); ?>
		</div>
	</div>

	<div class="grid-content">
		<table>
			<thead>
				<tr>
					<th>Title</th>
					<th>Message</th>
					<th>Follow</th>
					<th>Status</th>
					<th>Created At</th>
					<th>Updated At</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php

					foreach( $models as $notification ) {

						$id 	= $notification->id;

						if( strlen( $notification->content ) > CoreGlobal::DISPLAY_TEXT_LARGE ) {

							$notification->content	= "$notification->message ...";
						}
				?>
					<tr>
						<td><?= $notification->title ?></td>
						<td><?= $notification->content ?></td>
						<td>
							<?php if( isset( $notification->adminLink ) ) { ?>
								<a href="<?= Url::toRoute( [ $notification->adminLink ], true ) ?>">Follow</a>
							<?php } ?>
						</td>
						<td><?= $notification->getStatusStr() ?></td>
						<td><?= $notification->createdAt ?></td>
						<td><?= $notification->modifiedAt ?></td>
						<td class="actions">
							<span class="cmt-request" cmt-controller="notification" cmt-action="toggleRead" action="notify/notification/toggle-read?id=<?= $id ?>">
								<div class="spinner max-area-cover">
									<div class="valign-center cmti cmti-2x cmti-spinner-1 spin"></div>
								</div>
								<span class="cmt-click cmti <?= $notification->isConsumed() ? 'cmti-envelope-o' : 'cmti-envelope' ?>" title="<?= $notification->isConsumed() ? 'Mark Unread' : 'Mark Read' ?>"></span>
							</span>
							<?php if( $notification->isTrash() ) { ?>
								<span class="cmt-request" cmt-controller="notification" cmt-action="delete" action="notify/notification/delete?id=<?= $id ?>">
									<div class="spinner max-area-cover">
										<div class="valign-center cmti cmti-spinner-1 spin"></div>
									</div>
									<span class="cmt-click cmti cmti-close-c" title="Delete"></span>
								</span>
							<?php } else { ?>
								<span class="cmt-request" cmt-controller="notification" cmt-action="trash" action="notify/notification/trash?id=<?= $id ?>">
									<div class="spinner max-area-cover">
										<div class="valign-center cmti cmti-spinner-1 spin"></div>
									</div>
									<span class="cmt-click cmti cmti-bin" title="Trash"></span>
								</span>
							<?php } ?>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<div class="grid-footer clearfix">
		<div class="col12x6 info">
			<?=CodeGenUtil::getPaginationDetail( $dataProvider ) ?>
		</div>
		<div class="col12x6 pagination">
			<?= LinkPager::widget( [ 'pagination' => $pagination, 'options' => [ 'class' => 'pagination-basic' ] ] ); ?>
		</div>
	</div>
</div>