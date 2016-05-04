<?php
// Yii Imports
use \Yii;
use yii\helpers\Html;
use yii\helpers\Url;

$core	= Yii::$app->cmgCore;
$user	= Yii::$app->user->getIdentity();
?>

<?php if( $core->hasModule( 'cmgnotify' ) && $user->isPermitted( 'core' ) ) { ?>
	<div id="sidebar-notify" class="collapsible-tab has-children <?php if( strcmp( $parent, 'sidebar-notify' ) == 0 ) echo 'active';?>">
		<div class="collapsible-tab-header clearfix">
			<div class="colf colf5 wrap-icon"><span class="cmti cmti-chart-column"></span></div>
			<div class="colf colf5x4">Notifications</div>
		</div>
		<div class="collapsible-tab-content clear <?php if( strcmp( $parent, 'sidebar-notify' ) == 0 ) echo 'expanded visible';?>">
			<ul>
				<li class='notification <?php if( strcmp( $child, 'notification' ) == 0 ) echo 'active';?>'><?= Html::a( "Notifications", ['/cmgnotify/notification/all'] ) ?></li>
				<li class='notification-template <?php if( strcmp( $child, 'notification-template' ) == 0 ) echo 'active';?>'><?= Html::a( "Notification Templates", ['/cmgnotify/notification/template/all'] ) ?></li>
			</ul>
		</div>
	</div>
<?php } ?>