<?php Namespace App\V1\Mailers;

require_once 'CampaignMonitor/Classes/csrest_general.php';

use App\V1\Interfaces\EmailerInterface;
use App\V1\Mailers\CampaignMonitor\Client;
use App\V1\Mailers\CampaignMonitor\Lister;
use App\V1\Mailers\CampaignMonitor\User;

Class CampaignMonitor implements EmailerInterface {
	/**
	 * the base URL to use for all MailChimp request
	 * @var string baseUrl
	 */
	protected $baseUrl;

	/**
	 */
	protected $mailer;

	/**
	 * [$clientId description]
	 * @var [type]
	 */
	public $clientId;

	/**
	 * API key
	 * @var string
	 */
	public $apiKey;

	protected $coreValues = [
		"UnsubscribePage" => "http://www.example.com/unsubscribed.html",
		"UnsubscribeSetting" => "OnlyThisList",
		"ConfirmedOptIn" => false,
		"ConfirmationSuccessPage" => "http://www.example.com/joined.html",
		"AddUnsubscribesToSuppList" => true,
		"ScrubActiveWithSuppList" => true,
	];

	/**
	 * Class constructor
	 */
	public function __construct() {

		# define the base URL for the class
		$this->baseUrl = getenv('CMAPIPATH');

		# helpers.php function
		$this->clientId = $this->setClientId(getClientIdentifier());
	}

	/**
	 * retrieve this sessions client ID
	 * @return string
	 */
	public function getClientId() {
		return $this->clientId;
	}

	/**
	 * retrieve the API key for this client
	 * @return string
	 */
	public function getApiKey() {
		return $this->apiKey;
	}

	/**
	 * [setClientId description]
	 * @param [type] $clientId [description]
	 */
	public function setClientId($clientId) {
		$this->clientId = $clientId;

		$this->setApiKey();

		return $this->clientId;
	}

	/**
	 * [setApiKey description]
	 */
	private function setApiKey() {
		# retrieve and set the API API based on the clientId provided

		$this->apiKey = config('apikeys')[$this->clientId];
	}

	/**
	 * return the base URL to use for this MailChimp integration
	 * @return string baseUrl
	 */
	public function getBaseUrl() {
		return $this->baseUrl();
	}

	/**
	 * register a single user to a specified list
	 * @param string $listId the unique identifier for the list to subscribe the user to
	 * @param string $userEmail The email address of the user to subscribe
	 * @param string $userName The full name of the user to subscribe
	 * @param array $fields a list of question identifiers and answers given by the user
	 * @return mixed
	 */
	public function subscribe($listId, $userEmail, $userName, $fields) {
		$user = New User($this, $listId);

		if (!$user->subscribe($userEmail, $userName, $fields)) {
			return apiErrorResponse('unprocessable', ['errors' => $user->getError()]);
		}

		return apiSuccessResponse('ok');
	}

	/**
	 * register a single user to a specified list. This does not return the usual API response
	 * @param string $listId the unique identifier for the list to subscribe the user to
	 * @param string $userEmail The email address of the user to subscribe
	 * @param string $userName The full name of the user to subscribe
	 * @param array $fields a list of question identifiers and answers given by the user
	 * @return mixed
	 */
	public function processSubscribe($listId, $userEmail, $userName, $fields) {
		$user = New User($this, $listId);

		if (!$user->subscribe($userEmail, $userName, $fields)) {
			return false;
		}

		return true;
	}

	/**
	 * [updateSubscriber description]
	 * @return [type] [description]
	 */
	public function updateSubscriber($listId, $data) {
		$user = New User($this, $listId);
		return $user->updateSubscriber($data);
	}

	/**
	 * create a new mailing list
	 * @param string $listName the name of the list to create
	 * @return [type] [description]
	 */
	public function createList($listName) {
		$lister = New Lister($this, "");

		$params = [
			"Title" => $listName,
			$this->coreValues,
		];

		return $lister->create($params, $this->clientId);
	}

	/**
	 * add one or more custom fields to an existing list
	 * @param string $listId unique identifier for the list
	 * @param array $fields an array of field names to add to the list
	 */
	public function addCustomFields($listId, $fields) {
		$lister = New Lister($this, $listId);
		return $lister->addCustomFields($fields);
	}

	/**
	 * check to see whether a given list ID is a valid list
	 * @param  string  $listId  unique identifier for the list
	 * @return boolean
	 */
	public function isValidList($listId) {
		$lister = New Lister($this, $listId);

		if (!$lister->getList()) {
			return false;
		}

		return true;
	}

	/**
	 * retrieve the base details of an existing list
	 * @param  string $listId unique list identifier
	 * @return mixed
	 */
	public function getList($listId) {
		$lister = New Lister($this, $listId);

		if (!$result = $lister->getList()) {
			return apiErrorResponse('notFound', ['errors' => $lister->getError()]);
		}

		return apiSuccessResponse('ok', $result);
	}

	/**
	 * return an list of all client lists
	 * @return mixed
	 */
	public function getAllLists() {
		$client = New Client($this);
		$result = $client->getLists();

		if ($result->http_status_code != 200) {
			return apiErrorResponse('notFound', ['errors' => $result->response]);
		}

		$data = [
			"count" => count($result->response),
			"lists" => $result->response,
		];

		return apiSuccessResponse('ok', $data);
	}

	/**
	 * retrieve a set of custom fields assigned to a specified list
	 * @param  string $listId unique list identifier
	 * @return mixed
	 */
	public function getCustomFields($listId) {
		$lister = New Lister($this, $listId);

		if (!$result = $lister->getListFields()) {
			return apiErrorResponse('notFound', ['errors' => $lister->getError()]);
		}

		return apiSuccessResponse('ok', $result);
	}

	/**
	 * delete a specified list from the system
	 * @param  string $listId unique identifier for the list
	 * @return mixed
	 */
	public function deleteList($listId) {
		$lister = New Lister($this, $listId);
		$result = $lister->deleteList();

		if (isProtected($listId)) {
			return apiErrorResponse('forbidden', ['errors' => "Removal of list not allowed."]);
		}

		return apiSuccessResponse('ok', $result);
	}

	/**
	 * delete all non-standard lists from the client
	 * @return mixed
	 */
	public function deleteAllLists() {
		$client = New Client($this);
		$result = $client->getLists();

		if ($result->http_status_code != 200) {
			return apiErrorResponse('notFound', ['errors' => $result->response]);
		}

		$ids = [];
		$counter = 0;

		foreach ($result->response AS $item) {
			$listId = $item->ListID;

			if (!isProtected($item->ListID)) {
				(New Lister($this, $listId))->deleteList();
				$counter++;
			}
		}

		return apiSuccessResponse('ok', ["listsDeleted" => $counter]);
	}

	/**
	 * Update the details of a list with supplied data
	 * @param  array  $updates the details
	 * @return App\V1\Lib\ApiResponder
	 */
	public function updateList(array $data) {

		if (!is_array($data) or !isset($data["listId"]) or !isset($data["listName"])) {
			return apiErrorResponse('failedDependency', []);
		}

		$lister = New Lister($this, $data["listId"]);

		# create the basic array needed by the campaign monitor API
		$listData = ["Title" => $data["listName"]];

		# add in the default values for new lists as required by the CM API
		$core = array_merge($listData, $this->coreValues);

		# send it
		$response = $lister->updateList($core);

		if ($response->http_status_code == 200) {
			return apiSuccessResponse('ok', $response);
		}

		return apiErrorResponse('badRequest', ['errors' => $response->response->Message]);
	}

	/**
	 * update an array of fields with new label values
	 * @param array $data fields to update
	 * @return App\V1\Lib\ApiResponder
	 */
	public function updateFields($data) {
		if (!is_array($data) or !isset($data['listId']) or !isset($data['fields'])) {
			return apiErrorResponse('failedDependency', []);
		}

		$listId = $data['listId'];
		$fields = $data['fields'];

		$lister = New Lister($this, $listId);

		$updated = [];
		$failed = [];

		foreach ($fields AS $k => $field) {

			$response = $lister->updateFields("[" . $k . "]", [
				'FieldName' => $field,
				'VisibleInPreferenceCenter' => true, // whether this field will be show in the Campaign Monitor UI.
			]);

			# if the update of the field succeeded, sent the relevant data back
			if ($response->http_status_code == 200) {
				$updated[] = [
					"oldLabel" => $k,
					"newLabel" => $response->response,
				];
			}
			# otherwise we'll let the caller know which ones failed so they can try again
			else {
				$failed[] = [
					"oldLabel" => $k,
					"reason" => "Unknown or invalid key supplied.",
					"code" => $response->response->Code,
				];
			}
		}

		return apiSuccessResponse('ok', ["succeeded" => $updated, "failed" => $failed]);
	}
}
