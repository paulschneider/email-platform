<?php namespace App\V1\Controllers;

use Illuminate\Http\Response;

Class ListController extends Controller {

	/**
	 * [getList description]
	 * @param  [type] $listId [description]
	 * @return [type]         [description]
	 */
	public function getList($listId) {
		return $this->mailer->getList($listId);
	}

	/**
	 * retrieve a list of all existing lists
	 * @return mixed
	 */
	public function getAll() {
		return $this->mailer->getAllLists();
	}

	/**
	 * delete a single or set of lists from the mail client
	 * @return mixed
	 */
	public function deleteList($listId) {

		# make sure supplied list ID is a valid list
		if (!$this->mailer->isValidList($listId)) {
			return apiErrorResponse('unprocessable', ['errors' => 'Unknown list ID']);
		}

		return $this->mailer->deleteList($listId);
	}

	/**
	 * delete all lists from the client
	 * @return mixed
	 */
	public function deleteAllLists() {
		return $this->mailer->deleteAllLists();
	}

	/**
	 * add an array of fields to a specified list
	 */
	public function addCustomFields() {
		//$logger = app('Psr\Log\LoggerInterface')->info(json_encode($_POST));

		# check to make sure we have everything we need to proceed. Error if not
		if (!isset($_POST['listId']) or !isset($_POST['fields']) or !is_array($_POST['fields'])) {
			return apiErrorResponse('badRequest');
		}

		$listId = $_POST['listId'];
		$fields = $_POST['fields'];

		# make sure supplied list ID is a valid list
		if (!$this->mailer->getList($listId)) {
			return apiErrorResponse('unprocessable', ['errors' => 'Unknown list ID']);
		}

		# add the fields to the list
		if ($result = $this->mailer->addCustomFields($listId, $fields)) {
			return apiSuccessResponse('ok', $result);
		}

		return apiErrorResponse('unprocessable', $result);
	}

	/**
	 * retrieve a set of custom fields assigned to a list
	 * @param string $listId Unique identifier for the list to retrieve
	 * @return mixed
	 */
	public function getCustomFields($listId) {
		return $this->mailer->getCustomFields($listId);
	}

	/**
	 * create a new list
	 * @return mixed
	 */
	public function createList() {

		# check to see if we have the required fields
		if (!isset($_POST['listName'])) {
			return apiErrorResponse('insufficientArguments');
		}

		# make the call to create the form
		$result = $this->mailer->createList($_POST['listName']);

		# if we get a successful response back from the Mail API
		if ($result->http_status_code == 201) {
			return apiSuccessResponse('ok', ['listId' => $result->response]);
		}

		# otherwise its failed for some reason. So report it
		return apiErrorResponse('unprocessable', [
			'furtherResponseCode' => $result->response->Code,
			'furtherResponseMsg' => $result->response->Message,
		]);
	}

	/**
	 * request an update of a list name be made via the API
	 * @return mixed
	 */
	public function updateList() {
		return $this->mailer->updateList($_POST);
	}

	/**
	 * Update a list of field names to the supplied values
	 * @return mixed
	 */
	public function updateCustomField() {
		return $this->mailer->updateFields($_POST);
	}
}
