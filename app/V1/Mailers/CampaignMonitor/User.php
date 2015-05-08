<?php Namespace App\V1\Mailers\CampaignMonitor;

require_once('Classes/csrest_subscribers.php');	

Class User {

	/**
	 * CampaignMonitor instance
	 * @var CampaignMonitor
	 */
	protected $monitor;

	/**
	 * [__construct description]
	 * @param $monitor [description]
	 */
	public function __construct($monitor)
	{
		$this->monitor = $monitor;
		$this->auth = ['api_key' => getenv('CMAPIKEY')];
	}

	/**
	 * register a single user to a specified list
	 * @param string $listId
	 * @param array $data 
	 * @return [type] [description]
	 */
	public function subscribe($listId, $data)
	{
		$subscriber = New \CS_REST_Subscribers($listId, $this->auth);
		return $subscriber->add($data);	
	}

	/**
	 * [updateSubscriber description]
	 * @param  [type] $listId [description]
	 * @param  [type] $data   [description]
	 * @return [type]         [description]
	 */
	public function updateSubscriber($listId, $emailAddress, $data)
	{
		$subscriber = New \CS_REST_Subscribers($listId, $this->auth);
		return $subscriber->update($emailAddress, $data);	
	}
}