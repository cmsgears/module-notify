<?php
// Yii Imports
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

$core	= Yii::$app->core;
$user	= Yii::$app->user->getIdentity();

$adminStats		= Yii::$app->eventManager->getAdminStats();
$count			= $adminStats[ 'notificationCount' ];
$notifCount1	= "<span class='right inline-block'>
					<span class='upd-count upd-count-rounded upd-count-notification-all circled1 valign-center upd-count-$count'>$count</span>
				</span>";

$notifCount2	= "<span class='upd-count upd-count-notification-all upd-count-$count right padding padding-medium-h'>$count</span>";

$count			= $adminStats[ 'reminderCount' ];
$reminderCount1	= "<span class='right inline-block'>
<span class='upd-count upd-count-rounded upd-count-notification-all circled1 valign-center upd-count-$count'>$count</span>
</span>";

$reminderCount2	= "<span class='upd-count upd-count-notification-all upd-count-$count right padding padding-medium-h'>$count</span>";
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
			<div class="tab-title">Notifications <?= $notifCount1 ?></div>
		</div>
		<div class="tab-content clear <?php if( strcmp( $parent, 'sidebar-notify' ) == 0 ) echo 'expanded visible';?>">
			<ul>
				<li class='notification <?php if( strcmp( $child, 'notification' ) == 0 ) echo 'active'; ?>'>
					<?= Html::a( "Notifications $notifCount2", [ '/notify/notification/all' ] ) ?>
				</li>
				<li class='template <?php if( strcmp( $child, 'template' ) == 0 ) echo 'active'; ?>'><?= Html::a( "Templates", [ '/notify/notification/template/all' ] ) ?></li>
			</ul>
		</div>
	</div>
<?php } ?>

<?php if( $core->hasModule( 'notify' ) && $user->isPermitted( CoreGlobal::PERM_CORE ) ) { ?>
	<div id="sidebar-reminder" class="collapsible-tab has-children <?php if( strcmp( $parent, 'sidebar-reminder' ) == 0 ) echo 'active'; ?>">
		<div class="row tab-header">
			<div class="tab-icon"><span class="cmti cmti-alarm"></span></div>
			<div class="tab-title">Reminders <?= $reminderCount1 ?></div>
		</div>
		<div class="tab-content clear <?php if( strcmp( $parent, 'sidebar-reminder' ) == 0 ) echo 'expanded visible';?>">
			<ul>
				<li class='event <?php if( strcmp( $child, 'event' ) == 0 ) echo 'active'; ?>'><?= Html::a( "Events", [ '/notify/event/all' ] ) ?></li>
				<li class='reminder <?php if( strcmp( $child, 'reminder' ) == 0 ) echo 'active'; ?>'>
					<?= Html::a( "Reminders $reminderCount2", [ '/notify/reminder/all' ] ) ?>
				</li>
				<li class='template <?php if( strcmp( $child, 'template' ) == 0 ) echo 'active'; ?>'><?= Html::a( "Templates", [ '/notify/reminder/template/all' ] ) ?></li>
			</ul>
		</div>
	</div>
<?php } ?>