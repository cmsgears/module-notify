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
	//const MAIL_CONTACT			= "contact";

    public $htmlLayout 		= '@cmsgears/module-notify/common/mails/layouts/html';
    public $textLayout 		= '@cmsgears/module-notify/common/mails/layouts/text';
    public $viewPath 		= '@cmsgears/module-notify/common/mails/views';

	/*
    public function sendContactMail( $contactForm ) {

		$mailProperties	= $this->mailProperties;
		$adminEmail		= $mailProperties->getSenderEmail();
		$adminName		= $mailProperties->getSenderName();

		$fromEmail 		= $mailProperties->getContactEmail();
		$fromName 		= $mailProperties->getContactName();

		// User Mail
        $this->getMailer()->compose( self::MAIL_CONTACT, [ 'coreProperties' => $this->coreProperties, FormsGlobal::FORM_CONTACT => $contactForm ] )
            ->setTo( $contactForm->email )
            ->setFrom( [ $fromEmail => $fromName ] )
            ->setSubject( $contactForm->subject )
            //->setTextBody( $contact->contact_message )
            ->send();
    }
	*/
}

?>