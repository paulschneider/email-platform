<?php Namespace App\V1\Mailers;

require_once 'CampaignMonitor/Classes/csrest_general.php';

use App\V1\Interfaces\EmailerInterface;
use App\V1\Mailers\CampaignMonitor\Lister;

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
	 * register a new subscriber to a mailing list
	 * @param  [type] $email    [description]
	 * @param  [type] $listName [description]
	 * @return [type]           [description]
	 */
	public function subscribe($listId, $data) {
		$user = New User($this);
		return $user->subscribe($listId, $data);
	}

	/**
	 * [updateSubscriber description]
	 * @return [type] [description]
	 */
	public function updateSubscriber($listId, $data) {
		$user = New User($this);
		return $user->updateSubscriber($listId, $data);
	}

	/**
	 * add an array of form fields to a specified Mailchimp form
	 */
	public function addFieldsToList($listName, $fields = []) {
		$lister = New Lister($this);
		return $lister->addListFields($listName, $fields);
	}

	/**
	 * create a new mailing list
	 * @param string $listName the name of the list to create
	 * @return [type] [description]
	 */
	public function createList($listName) {
		$lister = New Lister($this);

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
		$lister = New Lister($this);
		return $lister->addCustomFields($listId, $fields);
	}

	/**
	 * [getList description]
	 * @param  [type] $listId [description]
	 * @return [type]         [description]
	 */
	public function getList($listId) {
		$lister = New Lister($this);
		return $lister->getList($listId);
	}
}