<?php namespace App\V1\Mailers\Mandrill;

use View;
use \App\V1\Interfaces\EmailSendInterface;
use \App\V1\Mailers\Mandrill\Client;

Class Unsubscribeable extends Client implements EmailSendInterface {

	/**
	 * email address to send from
	 * @var $fromEmail
	 */
	var $fromEmail = 'pschneider@theagencyonline.co.uk';

	/**
	 * name displayed to the recipient as the 'from' name
	 * @var $fromName
	 */
	var $fromName = 'Paul Schneider';

	/**
	 * email address to send to
	 * @var $toEmail
	 */
	var $toEmail;

	/**
	 * name of the recipient
	 * @var $toName
	 */
	var $toName;

	/**
	 * the subject line to show for the email
	 * @var $subject
	 */
	var $subject = 'New Unsubscribe-able User';

	/**
	 * what to tag the email as
	 * @var $subject
	 */
	var $tags = ['unsubscribeable'];

	/**
	 * what type of email are we sending
	 * @var $subject
	 */
	var $type = 'message';

	/**
	 * who to blind courtesy copy the email to
	 *
	 * @var string
	 */
	var $bcc;

	/**
	 * class constructor
	 */
	public function __construct($data = []) {
		parent::__construct();
		$this->notify($data);
	}

	/**
	 * [notify description]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	private function notify($data) {
		$this->setTo($data);
		$this->setHTML($data);
	}

	/**
	 * [setHTML description]
	 * @param [type] $data [description]
	 */
	private function setHTML($data) {
		$this->html = View("email.unsubscribeable")->render();
	}

	/**
	 * [setTo description]
	 * @param [type] $data [description]
	 */
	private function setTo($data) {
		$this->toEmail = $data['email'];
		$this->toName = $data['name'];

		# if a BCC address has been configured then use that for this email request
		$this->bcc = getenv('UNSUBSCRIBEABLE_EMAIL_BCC') ? getenv('UNSUBSCRIBEABLE_EMAIL_BCC') : null;
	}
}