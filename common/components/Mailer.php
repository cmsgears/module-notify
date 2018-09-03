<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\notify\common\components;

// CMG Imports
use cmsgears\core\common\base\Mailer as BaseMailer;

/**
 * Mailer triggers the mails provided by Notify Module.
 *
 * @since 1.0.0
 */
class Mailer extends BaseMailer {

	// Variables ---------------------------------------------------

	// Globals ----------------

	const MAIL_ADMIN	= 'admin';
	const MAIL_USER		= 'user';
	const MAIL_DIRECT	= 'direct';

	// Public -----------------

	public $htmlLayout 	= '@cmsgears/module-notify/common/mails/layouts/html';
	public $textLayout 	= '@cmsgears/module-notify/common/mails/layouts/text';
	public $viewPath 	= '@cmsgears/module-notify/common/mails/views';

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Mailer --------------------------------

	// Admin Mails --------

	public function sendAdminMail( $message, $template ) {

		$fromEmail 	= $this->mailProperties->getSenderEmail();
		$fromName 	= $this->mailProperties->getSenderName();

		$toEmail = $this->mailProperties->getContactEmail();

		// Send Mail
		$this->getMailer()->compose( self::MAIL_ADMIN, [ 'coreProperties' => $this->coreProperties, 'email' => $toEmail, 'message' => $message ] )
			->setTo( $toEmail )
			->setFrom( [ $fromEmail => $fromName ] )
			->setSubject( "Notification | " . $this->coreProperties->getSiteName() )
			//->setTextBody( "heroor" )
			->send();
	}

	// App Mails ----------

	public function sendUserMail( $message, $user, $template ) {

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

	public function sendDirectMail( $message, $email, $template ) {

		$fromEmail 	= $this->mailProperties->getSenderEmail();
		$fromName 	= $this->mailProperties->getSenderName();

		// Send Mail
		$this->getMailer()->compose( self::MAIL_DIRECT, [ 'coreProperties' => $this->coreProperties, 'email' => $email, 'message' => $message ] )
			->setTo( $email )
			->setFrom( [ $fromEmail => $fromName ] )
			->setSubject( "Notification | " . $this->coreProperties->getSiteName() )
			//->setTextBody( "heroor" )
			->send();
	}

}
