<?php Namespace App\V1\Mailers;

require_once 'CampaignMonitor/Classes/csrest_general.php';

use App\V1\Interfaces\EmailerInterface;
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
	 * * @param string $userName The full name of the user to subscribe
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
	 * retrieve the base details of an existing list
	 * @param  string $listId unique list identifier
	 * @return mixed
	 */
	public function getList() {
		$lister = New Lister($this, $listId);

		if (!$result = $lister->getList()) {
			return apiErrorResponse('notFound', ['errors' => $lister->getError()]);
		}

		return apiSuccessResponse('ok', $result);
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
}