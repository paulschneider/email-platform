<?php namespace App\V1\Controllers;

use Illuminate\Http\Response;

Class ListController extends Controller {
	/**
	 * [getList description]
	 * @param  [type] $listId [description]
	 * @return [type]         [description]
	 */
	public function getList($listId) {
		return response()->json($this->mailer->getList($listId)->response);
	}

	/**
	 * add an array of fields to a specified list
	 */
	public function addCustomFields() {
		$result = $this->mailer->addCustomField('839d9404eaf941b9b1e89afc3e103bf6', [
			"FieldName" => "DOB",
			"DataType" => "Text",
			"VisibleInPreferenceCenter" => true,
		]);

		return apiSuccessResponse('ok', $result);
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
}