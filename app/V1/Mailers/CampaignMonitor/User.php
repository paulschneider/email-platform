<?php Namespace App\V1\Mailers\CampaignMonitor;

require_once 'Classes/csrest_subscribers.php';

Class User extends Campaigner {

	/**
	 * CampaignMonitor instance
	 * @var CampaignMonitor
	 */
	protected $monitor;

	/**
	 * CS_REST_Subscribers
	 * @var $subscriber
	 */
	protected $subscriber;

	/**
	 * class constructor
	 * @param $monitor App\V1\Mailers\CampaignMonitor
	 * @param $listId which list identifier to work with
	 */
	public function __construct($monitor, $listId) {
		$this->monitor = $monitor;
		$this->auth = ['api_key' => getenv('CMAPIKEY')];

		$this->subscriber = New \CS_REST_Subscribers($listId, $this->auth);
	}

	/**
	 * register a single user to a specified list
	 * @param string $userEmail The email address of the user to subscribe
	 * @param string $userName The full name of the user to subscribe
	 * @param array $fields a list of question identifiers and answers given by the user
	 * @return mixed
	 */
	public function subscribe($userEmail, $userName, $fields) {

		# make sure we have an array to work with
		if (is_array($fields)) {
			# the base details as required by Campaign Monitor
			$data = [
				"EmailAddress" => $userEmail,
				"Name" => $userName,
				//"Resubscribe" => true,
			];

			$customFields = [];

			# go through each of the values provided and turn them into the format required
			foreach ($fields AS $key => $field) {
				$customFields[] = [
					"Key" => $key,
					"Value" => $field,
				];
			}

			# add them to the main array we'll send to Campaign Monitor
			$data['CustomFields'] = $customFields;
		}

		# send the request
		$result = $this->subscriber->add($data);

		# if we get anything other than a success then its an error and needs to be reported as such
		if ($result->http_status_code != 201) {
			$this->error = $result->response->Message;
			return false;
		}

		# otherwise it went okay and we can say as much
		return true;
	}

	/**
	 * [updateSubscriber description]
	 * @param  [type] $listId [description]
	 * @param  [type] $data   [description]
	 * @return [type]         [description]
	 */
	public function updateSubscriber($emailAddress, $data) {
		return $this->subscriber->update($emailAddress, $data);
	}
}