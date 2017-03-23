<?php
// Yii Imports
use yii\helpers\Html;
use yii\helpers\Url;

$core	= Yii::$app->core;
$user	= Yii::$app->user->getIdentity();

$adminStats		= Yii::$app->eventManager->getAdminStats();
$count			= $adminStats[ 'notificationCount' ];
$notifCount1	= "<span class='right inline-block'>
					<span class='upd-count upd-count-rounded upd-count-notification-all circled1 valign-center upd-count-$count'>$count</span>
				</span>";

$notifCount2	= "<span class='upd-count upd-count-notification-all upd-count-$count right padding padding-medium-h'>$count</span>";
?>

<?php if( $core->hasModule( 'notify' ) && $user->isPermitted( 'core' ) ) { ?>
	<div id="sidebar-notify" class="collapsible-tab has-children <?php if( strcmp( $parent, 'sidebar-notify' ) == 0 ) echo 'active'; ?>">
		<div class="row tab-header">
			<div class="tab-icon"><span class="cmti cmti-chart-column"></span></div>
			<div class="tab-title">Notifications <?= $notifCount1 ?></div>
		</div>
		<div class="tab-content clear <?php if( strcmp( $parent, 'sidebar-notify' ) == 0 ) echo 'expanded visible';?>">
			<ul>
				<li class='notification <?php if( strcmp( $child, 'notification' ) == 0 ) echo 'active'; ?>'>
					<?= Html::a( "Notifications $notifCount2", [ '/notify/notification/all' ] ) ?>
				</li>
				<li class='notification-template <?php if( strcmp( $child, 'notification-template' ) == 0 ) echo 'active'; ?>'><?= Html::a( "Notification Templates", [ '/notify/notification/template/all' ] ) ?></li>
			</ul>
		</div>
	</div>
<?php } ?>