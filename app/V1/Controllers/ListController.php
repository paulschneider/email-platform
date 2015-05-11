<?php namespace App\V1\Controllers;

use Illuminate\Http\Response;

Class ListController extends Controller {
	/**
	 * [getList description]
	 * @param  [type] $listId [description]
	 * @return [type]         [description]
	 */
	public function getList($listId) {
		return response()->json($this->mailer->getList($listId));
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
	 * [createList description]
	 * @return [type] [description]
	 */
	public function createList() {
		# error codes from CM
		// 250 - duplicate list title
		// 251 - list title empty
		// 261 - invalid list unsubscribe setting

		# Paul List Test
		// 839d9404eaf941b9b1e89afc3e103bf6 - from API
		// D64D77B010EA8BC9 - from web

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
	 * action on an UPDATE call to the resource
	 *
	 * @return ApiController Response
	 */
	public function update() {
		return ApiController::respondNotAllowed();
	}

	/**
	 * action on an UPDATE call to the resource
	 *
	 * @return ApiController Response
	 */
	public function destroy() {
		return ApiController::respondNotAllowed();
	}
}