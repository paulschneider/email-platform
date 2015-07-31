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
	 * CS_REST_Lists
	 * @var $list
	 */
	protected $list;

	/**
	 * class constructor
	 * @param CampaignMonitor $monitor CampaignMonitor instance
	 */
	public function __construct($monitor, $listId) {
		$this->monitor = $monitor;
		$this->auth = ['api_key' => $monitor->getApiKey()];

		$this->list = New \CS_REST_Lists($listId, $this->auth);
	}

	/**
	 * create a new subscriber list
	 * @param  array $params the details of the list to created
	 * @return null
	 */
	public function create(array $params) {
		return $this->list->create($this->monitor->clientId, $params);
	}

	/**
	 * add a set of custom fields to a specified list
	 * @param array $fields a list of field names to add
	 */
	public function addCustomFields($fields = []) {

		$items = $results = [];

		if (is_array($fields)) {
			foreach ($fields AS $field) {
				$item = [
					"FieldName" => substr(ucwords(strtolower($field)), 0, 30),
					"DataType" => "Text",
					"VisibleInPreferenceCenter" => true,
				];

				$result = $this->list->create_custom_field($item);
				$responseCode = $result->http_status_code;

				if ($responseCode == 201) {
					$results['created'][] = [
						"fieldName" => $field,
						"fieldTag" => stripbrackets($result->response),
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
	 * retrieve a specified list. Note that the list ID is passed to this class's constructor, not to this method
	 * @return mixed
	 */
	public function getList() {
		$result = $this->list->get();

		if ($result->http_status_code != 200) {
			$this->error = $result->response->Message;
			return false;
		}

		return [
			"id" => $result->response->ListID,
			"title" => $result->response->Title,
		];
	}

	/**
	 * retrieve a list of fields assigned to a specified List ID.
	 * Note that the list ID is passed to this class's constructor, not to this method
	 * @param  string $listId unique identifier for the list
	 * @return mixed
	 */
	public function getListFields() {

		$result = $this->list->get_custom_fields();

		if ($result->http_status_code != 200) {
			$this->error = $result->response->Message;
			return false;
		}

		$data = [];
		foreach ($result->response AS $field) {
			$data[] = [
				'fieldName' => $field->FieldName,
				'fieldTag' => $field->Key,
			];
		}

		return $data;
	}

	/**
	 * delete a list from the client
	 * @return mixed
	 */
	public function deleteList() {
		return $this->list->delete();
	}

	/**
	 * update the title of a list on Campaign Monitor
	 * @return HTTP response
	 */
	public function updateList($data) {
		return $this->list->update($data);
	}

	/**
	 * [updateFields description]
	 * @return [type] [description]
	 */
	public function updateFields($fieldKey, $fieldData) {
		return $this->list->update_custom_field($fieldKey, $fieldData);
	}
}
