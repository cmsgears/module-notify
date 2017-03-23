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
$keywords		= Yii::$app->request->getQueryParam( 'keywords' );

// Sorting
$sortOrder		= Yii::$app->request->getQueryParam( 'sort' );

if( !isset( $sortOrder ) ) {

	$sortOrder	= '';
}

// Filters
$statusFilter	= Yii::$app->request->getQueryParam( 'status' );
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
			<?= $dataProvider->sort->link( 'title', [ 'class' => 'sort btn btn-medium' ] ) ?>
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
					<th>Title</th>
					<th>Message</th>
					<th>Follow</th>
					<th>Consumed</th>
					<th>Trash</th>
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
						<td><?= $notification->getConsumedStr() ?></td>
						<td><?= $notification->getTrashStr() ?></td>
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
	<div class="row grid-header">
		<div class="col col12x6 info">
			<?=CodeGenUtil::getPaginationDetail( $dataProvider ) ?>
		</div>
		<div class="col col12x6 pagination">
			<?= LinkPager::widget( [ 'pagination' => $pagination, 'options' => [ 'class' => 'pagination-basic' ] ] ); ?>
		</div>
	</div>
</div>