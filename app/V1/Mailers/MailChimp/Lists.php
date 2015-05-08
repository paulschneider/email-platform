<?php Namespace App\V1\Mailers\MailChimp;

Class Lists {

	/**
	 * Mailchimp instance
	 * @var Mailchimp
	 */
	protected $chimp;

	/**
	 * A list of standard list ID's
	 * @var Mailchimp
	 */
	static $lists = [
		'registrants' => 'e7c33e39eb'
	];

	/**
	 * class constructor
	 * @param \Mailchimp $chimp MailChimp instance
	 */
	public function __construct(\Mailchimp $chimp)
	{
		$this->chimp = $chimp;
	}

	/**
	 * return the array of standard mailchimp lists
	 * @return mixed
	 */
	public static function getLists()
	{
		return is_array(self::$lists) ? self::$lists : false;
	}

	/**
	 * add an array of field names and their labels to a specified list
	 * @param string listId - unique identifier for the list
	 * @param array fields - an array of arrays containing ['fieldName', $fieldLabel]
	 * @param string fieldLabel - field label 
	 */
	public function addListFields($listName, $fields = [])
	{
		if(is_array($fields) and !empty($fields))
		{
			$listId = self::$lists[$listName];

			foreach($fields AS $field)
			{
				$result = $this->chimp->lists->mergeVarAdd(
					$listId, // unique list ID to add the field to
					strtoupper($field['fieldName']), // the name of the field to add to mailchimp. Fields labels must be in uppercase
					$field['fieldLabel'] // the label to apply to the field in mailchimp
				);		
			}
		}
		sd($result);
	}
}