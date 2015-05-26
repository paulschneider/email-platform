<?php Namespace App\V1\Mailers;

CLass Mandrill {
	/**
	 * request that an email be sent
	 * @param  \App\V1\Interfaces\EmailSendInterface $email one of the email system objects
	 * @return mixed
	 */
	public function send(\App\V1\Interfaces\EmailSendInterface $email) {
		return $email->send();
	}
}