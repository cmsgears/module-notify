<?php
// Yii Imports
use \Yii;
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
?>
<div class="header-content clearfix">
	<div class="header-actions col15x10">
		<span class="frm-icon-element element-small"></span>
	</div>
	<div class="header-search col15x5">
		<input id="search-terms" class="element-large" type="text" name="search" value="<?= $searchTerms ?>">
		<span class="frm-icon-element element-medium">
			<i class="cmti cmti-search"></i>
			<button id="btn-search">Search</button>
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
					<th>Message</th>
					<th>Follow</th>
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
						<td><?= $notification->content ?></td>
						<td>
							<?php if( isset( $notification->follow ) ) { ?>
								<a href="<?= Url::toRoute( [ $notification->follow ], true ) ?>">Follow</a>
							<?php } ?>
						</td>
						<td><?= $notification->createdAt ?></td>
						<td><?= $notification->modifiedAt ?></td>
						<td class="actions">
							<span class="nq-request" cmt-controller="notification" cmt-action="toggleRead" action="cmgnotify/notification/toggle-read?id=<?= $id ?>">
								<div class="spinner max-area-cover">
									<div class="valign-center cmti cmti-2x cmti-spinner-1 spin"></div>
								</div>
								<span class="cmt-click btn btn-medium">
									<?= $notification->consumed ? 'Mark Unread' : 'Mark Read' ?>
								</span>
							</span>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<div class="grid-header clearfix">
		<div class="col12x6 info">
			<?=CodeGenUtil::getPaginationDetail( $dataProvider ) ?>
		</div>
		<div class="col12x6 pagination">
			<?= LinkPager::widget( [ 'pagination' => $pagination, 'options' => [ 'class' => 'pagination-basic' ] ] ); ?>
		</div>
	</div>
</div>