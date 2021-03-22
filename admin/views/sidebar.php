<?php
// Yii Imports
use yii\helpers\Html;
use yii\helpers\Url;

// CMG Imports
use cmsgears\notify\common\config\NotifyGlobal;

$core	= Yii::$app->core;
$user	= Yii::$app->core->getUser();

$siteRootUrl = Yii::$app->core->getSiteRootUrl();
?>
<?php if( $core->hasModule( 'notify' ) && $user->isPermitted( NotifyGlobal::PERM_NOTIFY_ADMIN ) ) { ?>
	<div id="sidebar-activity" class="collapsible-tab has-children <?= $parent == 'sidebar-activity' ? 'active' : null ?>">
		<div class="row tab-header">
			<div class="tab-icon"><span class="cmti cmti-list"></span></div>
			<div class="tab-title">Activities
				<span class="count-sidebar count-sidebar-header count-activity">0</span>
			</div>
		</div>
		<div class="tab-content clear <?= $parent == 'sidebar-activity' ? 'expanded visible' : null ?>">
			<ul>
				<li class="activity <?= $child == 'activity' ? 'active' : null ?>">
					<a href="<?= Url::toRoute( [ "$siteRootUrl/notify/activity/all" ], true ) ?>">
						Activities
						<span class="count-sidebar count-sidebar-content count-activity">0</span>
					</a>
				</li>
				<li class="template <?= $child == 'template' ? 'active' : null ?>"><?= Html::a( "Templates", [ "$siteRootUrl/notify/activity/template/all" ] ) ?></li>
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
				<span class="count-sidebar count-sidebar-header count-notification">0</span>
			</div>
		</div>
		<div class="tab-content clear <?= $parent == 'sidebar-notify' ? 'expanded visible' : null ?>">
			<ul>
				<li class="notification <?= $child == 'notification' ? 'active' : null ?>">
					<a href="<?= Url::toRoute( [ "$siteRootUrl/notify/notification/all" ], true ) ?>">
						Notifications
						<span class="count-sidebar count-sidebar-content count-notification">0</span>
					</a>
				</li>
				<li class="template <?= $child == 'template' ? 'active' : null ?>"><?= Html::a( "Templates", [ "$siteRootUrl/notify/notification/template/all" ] ) ?></li>
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
				<li class="announcement <?= $child == 'announcement' ? 'active' : null ?>"><?= Html::a( "Announcements", [ "$siteRootUrl/notify/announcement/all" ] ) ?></li>
				<li class="template <?= $child == 'template' ? 'active' : null ?>"><?= Html::a( "Templates", [ "$siteRootUrl/notify/announcement/template/all" ] ) ?></li>
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
				<span class="count-sidebar count-sidebar-header count-reminder">0</span>
			</div>
		</div>
		<div class="tab-content clear <?= $parent == 'sidebar-reminder' ? 'expanded visible' : null ?>">
			<ul>
				<li class="event <?= $child == 'event' ? 'active' : null ?>"><?= Html::a( "Events", [ "$siteRootUrl/notify/event/all" ] ) ?></li>
				<li class="reminder <?= $child == 'reminder' ? 'active' : null ?>">
					<a href="<?= Url::toRoute( [ "$siteRootUrl/notify/reminder/all" ], true ) ?>">
						Reminders
						<span class="count-sidebar count-sidebar-content count-reminder">0</span>
					</a>
				</li>
				<li class="template <?= $child == 'etemplate' ? 'active' : null ?>"><?= Html::a( "Event Templates", [ "$siteRootUrl/notify/event/template/all" ] ) ?></li>
				<li class="template <?= $child == 'rtemplate' ? 'active' : null ?>"><?= Html::a( "Reminder Templates", [ "$siteRootUrl/notify/reminder/template/all" ] ) ?></li>
			</ul>
		</div>
	</div>
<?php } ?>
