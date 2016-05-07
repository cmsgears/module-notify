<?php
namespace cmsgears\notify\common\components;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\cms\common\config\CmsGlobal;

/**
 * The mail component for CMSGears cms module. It must be initialised for app using the name cmgCmsMailer.
 */
class Mailer extends \cmsgears\core\common\base\Mailer {

	// Various mail views
	const MAIL_ADMIN		= "admin";
	const MAIL_USER			= "user";

    public $htmlLayout 		= '@cmsgears/module-notify/common/mails/layouts/html';
    public $textLayout 		= '@cmsgears/module-notify/common/mails/layouts/text';
    public $viewPath 		= '@cmsgears/module-notify/common/mails/views';

	// Admin Mails --------------

	public function sendAdminMail( $message ) {

		$fromEmail 	= $this->mailProperties->getSenderEmail();
		$fromName 	= $this->mailProperties->getSenderName();

		$toEmail 	= $this->mailProperties->getContactEmail();

		// Send Mail
        $this->getMailer()->compose( self::MAIL_ADMIN, [ 'coreProperties' => $this->coreProperties, 'message' => $message ] )
            ->setTo( $toEmail )
            ->setFrom( [ $fromEmail => $fromName ] )
            ->setSubject( "Notification | " . $this->coreProperties->getSiteName() )
            //->setTextBody( "heroor" )
            ->send();
	}

	// Website Mails ------------

	public function sendUserMail( $message, $user ) {

		$fromEmail 	= $this->mailProperties->getSenderEmail();
		$fromName 	= $this->mailProperties->getSenderName();

		// Send Mail
        $this->getMailer()->compose( self::MAIL_USER, [ 'coreProperties' => $this->coreProperties, 'message' => $message, 'user' => $user ] )
            ->setTo( $user->email )
            ->setFrom( [ $fromEmail => $fromName ] )
            ->setSubject( "Notification | " . $this->coreProperties->getSiteName() )
            //->setTextBody( "heroor" )
            ->send();
	}
}

?>