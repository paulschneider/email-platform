<?php Namespace App\V1\Mailers\CampaignMonitor;

require_once('Classes/csrest_lists.php');	

Class Lists {

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
	 * class constructor
	 * @param CampaignMonitor $monitor CampaignMonitor instance
	 */
	public function __construct($monitor)
	{
		$this->monitor = $monitor;
		$this->auth = ['api_key' => getenv('CMAPIKEY')];
	}

	/**
	 * create a new subscriber list
	 * @param  array $params the details of the list to created
	 * @return null
	 */
	public function create(array $params)
	{		
		$list = New \CS_REST_Lists('', $this->auth);
		return $list->create($this->monitor->clientId, $params);
	}

	/**
	 * [addCustomField description]
	 */
	public function addCustomField($listId, array $params)
	{
		$list = New \CS_REST_Lists($listId, $this->auth);
		return $list->create_custom_field($params);		
	}

	/**
	 * [getAllLists description]
	 * @return [type] [description]
	 */
	public function getList($listId)
	{
		$list = New \CS_REST_Lists($listId, $this->auth);
		return $list->get();	
	}
}