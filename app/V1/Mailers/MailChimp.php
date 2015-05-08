<?php Namespace App\V1\Mailers;

Use App\V1\Interfaces\EmailerInterface;
Use Mailchimp AS Chimp;
Use App\V1\Mailers\MailChimp\Lists AS SubscriberList;
Use App\V1\Mailers\MailChimp\User AS UserMailer;

Class MailChimp implements EmailerInterface
{	
	/**
	 * the base URL to use for all MailChimp request
	 * @var string baseUrl
	 */
	protected $baseUrl;

	/**
	 * Vendor\MailChimp\MailChimp\Src\Mailchimp
	 * @var instance of the MailChimp API library class
	 */
	protected $chimp;

	/**
	 * Class constructor
	 */
	public function __construct()
	{
		# define the base URL for the class
		$this->baseUrl = getenv('MCAPIKEY');

		# new instance of the MailChimp API library
		$this->chimp = New Chimp(getenv('MCAPIKEY'));
	}

	/**
	 * return the base URL to use for this MailChimp integration
	 * @return string baseUrl
	 */
	public function getBaseUrl()
	{
		return $this->baseUrl();
	}

	/**
	 * register a new subscriber to a mailing list
	 * @param  [type] $email    [description]
	 * @param  [type] $listName [description]
	 * @return [type]           [description]
	 */
	public function subscribe($email, $listName, $formData = [])
	{
		$user = New UserMailer($this->chimp);
		return $user->subscribe($email, $listName, $formData);
	}

	/**
	 * add an array of form fields to a specified Mailchimp form
	 */
	public function addFieldsToList($listName, $fields = [])
	{
		$lister = New SubscriberList($this->chimp);
		return $lister->addListFields($listName, $fields);
	}
}