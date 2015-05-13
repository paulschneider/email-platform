<?php Namespace App\V1\Lib;

use Event;

Class MailLogger {

	/**
	 * class constructor
	 */
	public function __construct() {
		$this->repo = New \App\V1\Repositories\VerificationRepository();
	}

	/**
	 * log a subscription request to the database
	 * @param  string $recipientEmail the email address of the recipient
	 * @param  string $recipientName  the name of the recipient
	 * @param  array $fields set of fields (answers) to assign to the user record
	 * @param  string $listId unique identifier for the list
	 * @return null
	 */
	public function log($recipientEmail, $recipientName, $fields, $listId) {
		$logged = $this->repo->store([
			"list_id" => $listId,
			"recipient_email" => $recipientEmail,
			"recipient_name" => $recipientName,
			"fields" => json_encode($fields),
			"requested" => getDateTime(),
		]);

		if (!$logged) {
			return apiErrorResponse('badRequest', ['errors' => "There was a problem with the request."]);
		}

		Event::fire('RequestWasLogged');

		return apiSuccessResponse('ok');
	}
}