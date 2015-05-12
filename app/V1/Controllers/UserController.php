<?php namespace App\V1\Controllers;

Class UserController extends Controller {
	/**
	 * Request an individual user be subscribed to the email list
	 * @return mixed
	 */
	public function subscribe() {
		$logger = app('Psr\Log\LoggerInterface')->info(json_encode($_POST));

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

		# try and register the users' answers to the provided list
		return $this->mailer->subscribe($listId, $userEmail, $userName, $fields);
	}

	/**
	 * [updateSubscriber description]
	 * @return [type] [description]
	 */
	public function updateSubscriber() {
		$this->mailer->updateSubscriber("839d9404eaf941b9b1e89afc3e103bf6", "pschneider@theagencyonline.co.uk", [
			"CustomFields" => [
				"DOB" => "1980/03/25",
			],
		]);

		return response()->json($result);
	}

	/**
	 * Request an individual user be unsubscribed from the email list
	 * @return [type] [description]
	 */
	public function unsubscribe() {

	}
}