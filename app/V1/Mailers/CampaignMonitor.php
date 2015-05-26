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
	 * Vendor\MailChimp\MailChimp\Src\Mailchimp
	 * @var instance of the MailChimp API library class
	 */
	protected $mailer;

	/**
	 * list identifiers protected from API request actions
	 * @var array $protected
	 */
	protected $protected = [
		"5d0f2b931098f3314d1488b871107316",
	];

	/**
	 * [$clientId description]
	 * @var [type]
	 */
	public $clientId = 'c209170c996ff9997059dc429c92f33e';

	/**
	 * Class constructor
	 */
	public function __construct() {
		# define the base URL for the class
		$this->baseUrl = getenv('CMAPIPATH');
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
			"UnsubscribePage" => "http://www.example.com/unsubscribed.html",
			"UnsubscribeSetting" => "OnlyThisList",
			"ConfirmedOptIn" => false,
			"ConfirmationSuccessPage" => "http://www.example.com/joined.html",
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
}