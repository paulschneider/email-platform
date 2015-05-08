<?php namespace App\V1\Controllers;

Class UserController extends Controller
{	
	/**
	 * Request an individual user be subscribed to the email list
	 * @return mixed
	 */
	public function subscribe()
	{
		# Paul List Test
		// 839d9404eaf941b9b1e89afc3e103bf6 - from API
		// D64D77B010EA8BC9 - from web

		$result = $this->mailer->subscribe("839d9404eaf941b9b1e89afc3e103bf6", [
			"EmailAddress" => "pschneider@theagencyonline.co.uk",
			"Name" => "Paul Schneider",
			"CustomFields" => [
				0 => [
					"Key" => "FirstName1",
					"Value" => "Paul John Schneider"
				],
				1 => [
					"Key" => "DOB",
					"Value" => "25/03/1980"
				]
			]
		]);

		return response()->json($result);
	}

	/**
	 * [updateSubscriber description]
	 * @return [type] [description]
	 */
	public function updateSubscriber()
	{
		$this->mailer->updateSubscriber("839d9404eaf941b9b1e89afc3e103bf6", "pschneider@theagencyonline.co.uk", [
			"CustomFields" => [
				"DOB" => "1980/03/25"
			]
		]);

		return response()->json($result);
	}

	/**
	 * Request an individual user be unsubscribed from the email list
	 * @return [type] [description]
	 */
	public function unsubscribe()
	{

	}
}