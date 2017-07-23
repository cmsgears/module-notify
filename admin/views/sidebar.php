<?php
// Yii Imports
use yii\helpers\Html;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

$core		= Yii::$app->core;
$user		= Yii::$app->user->getIdentity();

$stats		= Yii::$app->eventManager->getAdminStats();
$ncount		= $stats[ 'notificationCount' ];
$rcount		= $stats[ 'reminderCount' ];
?>

<?php if( $core->hasModule( 'notify' ) && $user->isPermitted( CoreGlobal::PERM_CORE ) ) { ?>
	<div id="sidebar-activity" class="collapsible-tab has-children <?php if( strcmp( $parent, 'sidebar-activity' ) == 0 ) echo 'active'; ?>">
		<div class="row tab-header">
			<div class="tab-icon"><span class="cmti cmti-event"></span></div>
			<div class="tab-title">Activities</div>
		</div>
		<div class="tab-content clear <?php if( strcmp( $parent, 'sidebar-activity' ) == 0 ) echo 'expanded visible';?>">
			<ul>
				<li class='activity <?php if( strcmp( $child, 'activity' ) == 0 ) echo 'active'; ?>'>
					<?= Html::a( "Activities", [ '/notify/activity/all' ] ) ?>
				</li>
				<li class='template <?php if( strcmp( $child, 'template' ) == 0 ) echo 'active'; ?>'><?= Html::a( "Templates", [ '/notify/activity/template/all' ] ) ?></li>
			</ul>
		</div>
	</div>
<?php } ?>

<?php if( $core->hasModule( 'notify' ) && $user->isPermitted( CoreGlobal::PERM_CORE ) ) { ?>
	<div id="sidebar-notify" class="collapsible-tab has-children <?php if( strcmp( $parent, 'sidebar-notify' ) == 0 ) echo 'active'; ?>">
		<div class="row tab-header">
			<div class="tab-icon"><span class="cmti cmti-flag"></span></div>
			<div class="tab-title">
				Notifications
				<?php if( $ncount > 0 ) { ?>
					<span class="count-sidebar count-sidebar-header count-notification"><?= $ncount ?></span>
				<?php } ?>
			</div>
		</div>
		<div class="tab-content clear <?php if( strcmp( $parent, 'sidebar-notify' ) == 0 ) echo 'expanded visible';?>">
			<ul>
				<li class='notification <?php if( strcmp( $child, 'notification' ) == 0 ) echo 'active'; ?>'>
					<a href="<?= Url::toRoute( [ '/notify/notification/all' ], true ) ?>">
						Notifications
						<?php if( $ncount > 0 ) { ?>
							<span class="count-sidebar count-sidebar-content count-notification"><?= $ncount ?></span>
						<?php } ?>
					</a>
				</li>
				<li class='template <?php if( strcmp( $child, 'template' ) == 0 ) echo 'active'; ?>'><?= Html::a( "Templates", [ '/notify/notification/template/all' ] ) ?></li>
			</ul>
		</div>
	</div>
<?php } ?>

<?php if( $core->hasModule( 'notify' ) && $user->isPermitted( CoreGlobal::PERM_CORE ) ) { ?>
	<div id="sidebar-reminder" class="collapsible-tab has-children <?php if( strcmp( $parent, 'sidebar-reminder' ) == 0 ) echo 'active'; ?>">
		<div class="row tab-header">
			<div class="tab-icon"><span class="cmti cmti-bell"></span></div>
			<div class="tab-title">
				Reminders
				<?php if( $rcount > 0 ) { ?>
					<span class="count-sidebar count-sidebar-header count-reminder"><?= $rcount ?></span>
				<?php } ?>
			</div>
		</div>
		<div class="tab-content clear <?php if( strcmp( $parent, 'sidebar-reminder' ) == 0 ) echo 'expanded visible';?>">
			<ul>
				<li class='event <?php if( strcmp( $child, 'event' ) == 0 ) echo 'active'; ?>'><?= Html::a( "Events", [ '/notify/event/all' ] ) ?></li>
				<li class='reminder <?php if( strcmp( $child, 'reminder' ) == 0 ) echo 'active'; ?>'>
					<a href="<?= Url::toRoute( [ '/notify/reminder/all' ], true ) ?>">
						Reminders
						<?php if( $rcount > 0 ) { ?>
							<span class="count-sidebar count-sidebar-content count-reminder"><?= $rcount ?></span>
						<?php } ?>
					</a>
				</li>
				<li class='template <?php if( strcmp( $child, 'template' ) == 0 ) echo 'active'; ?>'><?= Html::a( "Templates", [ '/notify/reminder/template/all' ] ) ?></li>
			</ul>
		</div>
	</div>
<?php } ?>
