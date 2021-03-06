<?php
// Yii Imports
use yii\helpers\Html;
use yii\helpers\Url;

$siteProperties = Yii::$app->controller->getSiteProperties();

$defaultIncludes = Yii::getAlias( '@cmsgears' ) . '/module-core/common/mails/views/includes';

$siteName	= Html::encode( $coreProperties->getSiteName() );
$logoUrl	= Url::to( "@web/images/" . $siteProperties->getMailAvatar(), true );
$siteUrl	= Html::encode( $coreProperties->getSiteUrl() );
$siteBkg	= "$siteUrl/images/" . $siteProperties->getMailBanner();
$homeUrl	= $siteUrl;
?>
<?php include "$defaultIncludes/header.php"; ?>
<table cellspacing="0" cellpadding="0" border="0" margin="0" padding="0" width="80%" align="center" class="ctmax">
	<tr><td height="40"></td></tr>
	<tr>
		<td><font face="Roboto, Arial, sans-serif">Dear Administrator,</font></td>
	</tr>
	<tr><td height="20"></td></tr>
	<tr>
		<td>
			<font face="Roboto, Arial, sans-serif">Notification is triggered for you. The details are as mentioned below:</font>
		</td>
	</tr>
	<tr><td height="20"></td></tr>
	<tr>
		<td><font face="Roboto, Arial, sans-serif">Message: <?= $message ?></font></td>
	</tr>
	<tr><td height="40"></td></tr>
</table>
<?php include "$defaultIncludes/footer.php"; ?>
