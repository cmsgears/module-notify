<?php
// Yii Imports
use yii\helpers\Html;
use yii\helpers\Url;

// CMG Imports
use cmsgears\notify\common\config\NotifyGlobal;

$core	= Yii::$app->core;
$user	= Yii::$app->user->getIdentity();

$stats	= Yii::$app->eventManager->getAdminStats();
$acount	= $stats[ 'activityCount' ];
$ncount	= $stats[ 'notificationCount' ];
$rcount	= $stats[ 'reminderCount' ];
?>

<?php if( $core->hasModule( 'notify' ) && $user->isPermitted( NotifyGlobal::PERM_NOTIFY_ADMIN ) ) { ?>
	<div id="sidebar-activity" class="collapsible-tab has-children <?= $parent == 'sidebar-activity' ? 'active' : null ?>">
		<div class="row tab-header">
			<div class="tab-icon"><span class="cmti cmti-event"></span></div>
			<div class="tab-title">Activities
			<?php if( $acount > 0 ) { ?>
					<span class="count-sidebar count-sidebar-header"><?= $acount ?></span>
				<?php } ?>
			</div>
		</div>
		<div class="tab-content clear <?= $parent == 'sidebar-activity' ? 'expanded visible' : null ?>">
			<ul>
				<li class="activity <?= $child == 'activity' ? 'active' : null ?>">
					<a href="<?= Url::toRoute( [ '/notify/activity/all' ], true ) ?>">
						Activities
						<?php if( $acount > 0 ) { ?>
							<span class="count-sidebar count-sidebar-content "><?= $acount ?></span>
						<?php } ?>
					</a>
				</li>
				<li class="template <?= $child == 'template' ? 'active' : null ?>"><?= Html::a( "Templates", [ '/notify/activity/template/all' ] ) ?></li>
			</ul>
		</div>
	</div>
<?php } ?>

<?php if( $core->hasModule( 'notify' ) && $user->isPermitted( NotifyGlobal::PERM_NOTIFY_ADMIN ) ) { ?>
	<div id="sidebar-notify" class="collapsible-tab has-children <?= $parent == 'sidebar-notify' ? 'active' : null ?>">
		<div class="row tab-header">
			<div class="tab-icon"><span class="cmti cmti-flag"></span></div>
			<div class="tab-title">
				Notifications
				<?php if( $ncount > 0 ) { ?>
					<span class="count-sidebar count-sidebar-header count-notification"><?= $ncount ?></span>
				<?php } ?>
			</div>
		</div>
		<div class="tab-content clear <?= $parent == 'sidebar-notify' ? 'expanded visible' : null ?>">
			<ul>
				<li class="notification <?= $child == 'notification' ? 'active' : null ?>">
					<a href="<?= Url::toRoute( [ '/notify/notification/all' ], true ) ?>">
						Notifications
						<?php if( $ncount > 0 ) { ?>
							<span class="count-sidebar count-sidebar-content count-notification"><?= $ncount ?></span>
						<?php } ?>
					</a>
				</li>
				<li class="template <?= $child == 'template' ? 'active' : null ?>"><?= Html::a( "Templates", [ '/notify/notification/template/all' ] ) ?></li>
			</ul>
		</div>
	</div>
<?php } ?>

<?php if( $core->hasModule( 'notify' ) && $user->isPermitted( NotifyGlobal::PERM_NOTIFY_ADMIN ) ) { ?>
	<div id="sidebar-notify" class="collapsible-tab has-children <?= $parent == 'sidebar-announcement' ? 'active' : null ?>">
		<div class="row tab-header">
			<div class="tab-icon"><span class="fa fa-bullhorn"></span></div>
			<div class="tab-title">Announcements</div>
		</div>
		<div class="tab-content clear <?= $parent == 'sidebar-announcement' ? 'expanded visible' : null ?>">
			<ul>
				<li class="announcement <?= $child == 'announcement' ? 'active' : null ?>"><?= Html::a( "Announcements", [ '/notify/announcement/all' ] ) ?></li>
				<li class="template <?= $child == 'template' ? 'active' : null ?>"><?= Html::a( "Templates", [ '/notify/announcement/template/all' ] ) ?></li>
			</ul>
		</div>
	</div>
<?php } ?>

<?php if( $core->hasModule( 'notify' ) && $user->isPermitted( NotifyGlobal::PERM_NOTIFY_ADMIN ) ) { ?>
	<div id="sidebar-reminder" class="collapsible-tab has-children <?= $parent == 'sidebar-reminder' ? 'active' : null ?>">
		<div class="row tab-header">
			<div class="tab-icon"><span class="cmti cmti-bell"></span></div>
			<div class="tab-title">
				Reminders
				<?php if( $rcount > 0 ) { ?>
					<span class="count-sidebar count-sidebar-header count-reminder"><?= $rcount ?></span>
				<?php } ?>
			</div>
		</div>
		<div class="tab-content clear <?= $parent == 'sidebar-reminder' ? 'expanded visible' : null ?>">
			<ul>
				<li class="event <?= $child == 'event' ? 'active' : null ?>"><?= Html::a( "Events", [ '/notify/event/all' ] ) ?></li>
				<li class="reminder <?= $child == 'reminder' ? 'active' : null ?>">
					<a href="<?= Url::toRoute( [ '/notify/reminder/all' ], true ) ?>">
						Reminders
						<?php if( $rcount > 0 ) { ?>
							<span class="count-sidebar count-sidebar-content count-reminder"><?= $rcount ?></span>
						<?php } ?>
					</a>
				</li>
				<li class="template <?= $child == 'etemplate' ? 'active' : null ?>"><?= Html::a( "Event Templates", [ '/notify/event/template/all' ] ) ?></li>
				<li class="template <?= $child == 'rtemplate' ? 'active' : null ?>"><?= Html::a( "Reminder Templates", [ '/notify/reminder/template/all' ] ) ?></li>
			</ul>
		</div>
	</div>
<?php } ?>
