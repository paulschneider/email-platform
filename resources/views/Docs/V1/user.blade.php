@extends('layouts.default')

@include('layouts.header')

@section('content')
    <h3>Users</h3>
    <ul>
    	<li><a href="/v1/docs/user#subscribe">Subscribe a user</a></li>
    </ul>

    <h4><a name="subscribe">Subscribe a user</a></h4>

	<dl>
		<dt>Description</dt>
			<dd>Subscribe a new user to a an existing list. Submitting data of an existing user, identified by the email address, will result in an update of the account with the new data. Un-subscribed users will be re-subscribed.</dd>
		<dt>Endpoint</dt>
			<dd>/v1/user/subscribe</dd>
		<dt>Method</dt>
			<dd>POST</dd>
		<dt>Required Parameters</dt>
			<dd>
				<ul>
					<li><strong>listId</strong> <span class="define">[type = string, max length= 250]</span></li>
					<li><strong>userEmail</strong> <span class="define">[type = string]</span></li>
					<li><strong>userName</strong> <span class="define">[type = string, max length= 100]</span></li>
					<li><strong>fields</strong> <span class="define">[type = array]</span></li>
				</ul>
			</dd>
	    <dt>Response Types</dt>
        <dd>JSON</dd>
	</dl>

	<h5>Example Request</h5>

	<pre>
    	<code class="php">
    		$apiClient = New ApiClient();
    		<br />
    		$params = [
    			"listId" => "91098c78ae3e0e7e600b07b57a086cb1",
			"userEmail" => "pschneider@theagencyonline.co.uk",
			"userName" => "Paul John Schneider",
			"fields" => [
				"EyeColour" => "Blue",
				"Height" => "180",
				"Tshirt" => "blue",
			],
		];
    		<br />
    		$response = $apiClient->post('/v1/user/subscribe', $params);
    	</code>
    </pre>

    <h5>Example Success Response</h5>

    <pre>
    	<code class="php">
			{
				"success":
				{
					"message": "Success.",
					"statusCode": 200,
					"method": "POST",
					"endpoint": "/v1/user/subscribe",
					"time": 1431360791,
					"data": []
				}
			}
        </code>
    </pre>

@endsection

@include('layouts.footer')