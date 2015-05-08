<?php Namespace App\V1\Mailers\MailChimp;

Use App\V1\Mailers\MailChimp\Lists AS Lister;

Class User {

	/**
	 * Mailchimp instance
	 * @var Mailchimp
	 */
	protected $chimp;

	public function __construct(\Mailchimp $chimp)
	{
		# make the passed Mailchimp instance available to the class
		$this->chimp = $chimp;

		# grab the standard lists array so we can access the list id's
		$this->lists = Lister::getLists();
	}

	/**
	 * register a single user with MailChimp
	 * @return [type] [description]
	 */
	public function subscribe($email, $listName, $mergeData)
	{
		try
		{
			$result = $this->chimp->lists->subscribe(
				$this->lists[$listName], // the list to add the subscriber to
				['email' => $email], // the email address of the subscriber
				$mergeData, // form data to add against the subscription
				'html', // email type
				false, // double opt in (have a secondary email sent to the user that they have to click on to confirm subscription)
				false, // update existing user
				true // update existing user (default is false)
			); 	

			sd($result);
		}
		# Fail silently if the user is already registered
		catch(\Mailchimp_Error $e)
		{
			$error = $e->getMessage();

			sd($error);
		}
	}
}