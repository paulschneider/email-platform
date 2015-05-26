<?php Namespace App\V1\Mailers\Mandrill;

Class Client {

	/**
	 * the API key to use
	 * @var $apiKey
	 */
	protected $apiKey;

	/**
	 * the API client class to send the request
	 * @var App\V1\Lib\ApiClient
	 */
	protected $apiClient;

	/**
	 * Mandrill API base URL. Used for all mail requests
	 * @var $baseUrl
	 */
	protected $baseUrl = 'https://mandrillapp.com/api/1.0/';

	/**
	 * Mandrill API endpoints to use
	 * @var $endpoints
	 */
	protected $endpoints = [
		'message' => 'messages/send.json',
	];

	/**
	 * class construct
	 */
	public function __construct() {
		$this->apiKey = getenv('MANDRILL_API_KEY');
		$this->apiClient = New \App\V1\Lib\ApiClient();
	}

	/**
	 * make the request to the Mandrill API
	 * @return mixed
	 */
	public function send() {
		$request = [
			'key' => $this->apiKey,
			'message' => [
				'html' => $this->html,
				'text' => strip_tags($this->html),
				'subject' => $this->subject,
				'from_email' => $this->fromEmail,
				'from_name' => $this->fromName,
				'to' => [
					[
						'email' => $this->toEmail,
						'name' => $this->toName,
					],
				],
				'headers' => [
					'Reply-To' => $this->fromEmail,
				],
				'tags' => $this->tags, // an array of tags
			],
		];

		# if we have a blind courtesy copy address then include it in the request
		if (isset($this->bcc) && !empty($this->bcc)) {
			$request['message']['bcc_address'] = $this->bcc;
		}

		# make up the Mandrill API endpoint
		$url = $this->baseUrl . $this->endpoints[$this->type];

		# make the request
		return $this->apiClient->post($url, json_encode($request));
	}
}