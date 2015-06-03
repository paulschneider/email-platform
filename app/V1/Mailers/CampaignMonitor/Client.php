<?php Namespace App\V1\Mailers\CampaignMonitor;

require_once 'Classes/csrest_clients.php';

Class Client extends Campaigner {

	/**
	 * CampaignMonitor instance
	 * @var CampaignMonitor
	 */
	protected $monitor;

	/**
	 * A list of standard list ID's
	 * @var Mailchimp
	 */
	static $lists = [];

	/**
	 * authentication details to use for the class.
	 * @var array
	 */
	protected $auth;

	/**
	 * CS_REST_Clients
	 * @var $client
	 */
	protected $client;

	/**
	 * class constructor
	 * @param CampaignMonitor $monitor CampaignMonitor instance
	 */
	public function __construct($monitor) {
		$this->monitor = $monitor;
		$this->auth = ['api_key' => $monitor->getApiKey()];

		$this->client = New \CS_REST_Clients($monitor->getClientId(), $this->auth);
	}

	/**
	 * retrieve an array of lists registered with the client account
	 * @return mixed
	 */
	public function getLists() {
		return $this->client->get_lists();
	}
}