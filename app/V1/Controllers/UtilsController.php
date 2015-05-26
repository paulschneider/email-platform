<?php namespace App\V1\Controllers;

Class UtilsController extends Controller {

	/**
	 * manually process the email queue
	 * @return null
	 */
	public function process(\App\V1\Repositories\QueueRepository $repo) {
		$repo->process();
	}

	/**
	 * request the send of a transactional email
	 * @return null
	 */
	public function transactionEmail(\App\V1\Mailers\Mandrill $mailer) {
		$data = [
			"email" => "pschneider@theagencyonline.co.uk",
			"name" => "Paul Schneider",
		];

		sd($mailer->send(new \App\V1\Mailers\Mandrill\Unsubscribeable($data)));
	}
}