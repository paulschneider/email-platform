<?php Namespace App\V1\Mailers\CampaignMonitor;

require_once 'Classes/csrest_lists.php';

Class Lister extends Campaigner {

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
	public function __construct($monitor) {
		$this->monitor = $monitor;
		$this->auth = ['api_key' => getenv('CMAPIKEY')];
	}

	/**
	 * create a new subscriber list
	 * @param  array $params the details of the list to created
	 * @return null
	 */
	public function create(array $params) {
		$list = New \CS_REST_Lists('', $this->auth);
		return $list->create($this->monitor->clientId, $params);
	}

	/**
	 * [addCustomFields description]
	 * @param [type] $listId [description]
	 * @param [type] $fields [description]
	 */
	public function addCustomFields($listId, $fields = []) {

		$items = $results = [];
		$list = New \CS_REST_Lists($listId, $this->auth);

		if (is_array($fields)) {
			foreach ($fields AS $field) {
				$item = [
					"FieldName" => substr(ucwords(strtolower($field)), 0, 30),
					"DataType" => "Text",
					"VisibleInPreferenceCenter" => true,
				];

				$result = $list->create_custom_field($item);
				$responseCode = $result->http_status_code;

				if ($responseCode == 201) {
					$results['created'][] = [
						"fieldName" => $field,
						"fieldTag" => $result->response,
						"message" => "success",
					];
				} else {
					$results['errors'][] = [
						'fieldName' => $field,
						'message' => isset($result->response->Message) ? $result->response->Message : null,
					];
				}
			}
		}

		return $results;
	}

	/**
	 * [getAllLists description]
	 * @return [type] [description]
	 */
	public function getList($listId) {
		$list = New \CS_REST_Lists($listId, $this->auth);
		$result = $list->get();

		if ($result->http_status_code != 200) {
			$this->error = $result->response->Message;
			return false;
		}

		return [
			"id" => $result->response->ListID,
			"title" => $result->response->Title,
		];
	}
}