<?php Namespace App\V1\ApiClient;

//http://api.guzzlephp.org/class-Guzzle.Http.Message.Request.html

use GuzzleHttp\Client AS GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

Class Client 
{
	/**
	 * GuzzleHttp\Client
	 * @var client
	 */
	protected $client;

	/**
	 * the endpoint we will attach to the base URL
	 * @var endpoint
	 */
	protected $endpoint;

	/**
	 * class constructor
	 */
	public function __construct()
	{
		# new up a new Guzzle client and define the base URL to use for all requests
		$this->client = new GuzzleClient( [ 'base_url' => getenv('MCAPIPATH') ] );
	}

	/**
	 * make a GET request 
	 * @param  string $endpoint - the endpoint to append to the base URL
	 * @param  array $params - the parameters to send as part of the request
	 * @param  array $headers - optional header parameters to send as part of the request
	 * @return mixed - the result of the API call
	 */
	public function get($endpoint = "", $params = [], $headers = [])
	{
		# make the end point available to the class
		$this->endpoint = $endpoint;

		# create the Guzzle request
		$request = $this->client->createRequest('GET', $this->endpoint, [
			'headers' => $headers,
			'query' => $params
		]);

		# send the request and return the result to the caller
		return $this->send($request);
	}

	/**
	 * Make a POST request to the requested endpoint
	 * @param  string $endpoint - the endpoint to append to the base URL
	 * @param  array $data - the data to supply to the endpoint
	 * @param  array $headers - optional headers to send as part of the request
	 * @return mixed
	 */
	public function post($endpoint = "/", $data = [], $headers = [])
	{
		# make the end point available to the class
		$this->endpoint = $endpoint;

		# create the Guzzle request
		$request = $this->client->createRequest('POST', $this->endpoint, [
			'body' => $data, 
			'headers' => $headers
		]);

		# send the request and return the result
		return $this->send($request);
	}

	/**
	 * make the call to the API
	 * 
	 * @param  Request
	 * @return Response
	 */
	public function send($request)
	{
		try 
		{
			# if it was successful, return the response
		   return $this->client->send($request);
		} 
		catch (ClientException $e) 
		{
			# if it was unsuccessful then return the error that was thrown
			return $e->getResponse();
		}
	}
}