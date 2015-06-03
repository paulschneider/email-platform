<?php namespace App\V1\Controllers;

Class UserController extends Controller {
	/**
	 * Request an individual user be subscribed to the email list
	 * @return mixed
	 */
	public function subscribe(\App\V1\Lib\MailLogger $logger) {

		if (!isset($_POST['listId']) or !isset($_POST['userEmail']) or !isset($_POST['fields']) or !isset($_POST['userName'])) {
			return apiErrorResponse('badRequest');
		}

		$listId = $_POST['listId'];

		# make sure supplied list ID is a valid list
		if (!$this->mailer->getList($listId)) {
			return apiErrorResponse('unprocessable', ['errors' => 'Unknown list ID']);
		}

		$userEmail = $_POST['userEmail'];
		$userName = $_POST['userName'];
		$fields = $_POST['fields'];

		# log the subscription request which we'll try and action later on
		$response = $logger->log($userEmail, $userName, $fields, $listId);

		# try and register the users' answers to the provided list
		return $this->mailer->subscribe($listId, $userEmail, $userName, $fields);
	}
}