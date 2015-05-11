@extends('layouts.default')

@include('layouts.header')

@section('content')
    <h3>Lists</h3>
    <ul>
    	<li><a href="/v1/docs/list#get">Get List</a></li>
    	<li><a href="/v1/docs/list#create">Create</a></li>
    	<li><a href="/v1/docs/list#update">Update</a></li>
    	<li><a href="/v1/docs/list#add-fields">Add Fields</a></li>
    </ul>

    <h4><a name="create">Create</a></h4>

    	<dl>
    		<dt>Endpoint</dt>
    			<dd>/v1/list/create</dd>
    		<dt>Method</dt>
    			<dd>POST</dd>
    		<dt>Required Parameters</dt>
    			<dd>
    				<ul>
    					<li><strong>listName</strong> (type = string, max length= 250)</li>
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
	    			"listName" => "A New List Name"
	    		];
	    		<br />
	    		$response = $apiClient->post('/v1/list/create', $params);
	    	</code>
	    </pre>

	    <h5>Example Success Response</h5>

	    <pre>
	    	<code class="php">
                {
                    "success":{
                        "message":"Success.",
                        "statusCode":200,
                        "method":"GET",
                        "endpoint":"/v1/list/create",
                        "time":1431092169,
                        "data":{
                            "listId":"9ffe76564af6841272cafeeb3765759d"
                        }
                    }
                }
            </code>
	    </pre>

        <h5>Example Failure Response</h5>

        <pre>
            <code class="json">
                {
                    "error": {
                            "message":"The request could not be processed due to errors.",
                            "statusCode":422,
                            "method":"GET",
                            "endpoint":"/v1/list/create",
                            "time":1431091520,
                            "time":1431091839
                    },
                    "data": {
                            "furtherResponseCode":250,
                            "furtherResponseMsg":"List title must be unique within a client"
                    }
                }
            </code>
        </pre>

    <h4><a name="update">Update</a></h4>

    <h4><a name="add-fields">Add Fields</a></h4>

    <dl>
        <dt>Endpoint</dt>
            <dd>list/add-fields</dd>
        <dt>Method</dt>
            <dd>POST</dd>
        <dt>Required Parameters</dt>
            <dd>
                <ul>
                    <li><strong>listId</strong> (type = string) - Unique identifier for the list</li>
                    <li><strong>fields</strong> (type = array) - A list of fields to add to the list</li>
                </ul>
            </dd>
        <dt>Available Response Types</dt>
            <dd>JSON</dd>
    </dl>

    <h5>Example Request</h5>

    <pre>
        <code class="php">
            $apiClient = New ApiClient();
            <br />
            $params = [
                "listId" => "0624b63fc7717b64b450e1c87cb21c38",
                "fields" => [
                    "Name", "Age", "Height", "Nationality"
                ]
            ];
            <br />
            $response = $apiClient->post('/v1/list/add-fields', $params);
        </code>
    </pre>

    <h5>Example Success Response</h5>

        <pre>
            <code class="php">
                {
                  "success": {
                    "message": "Success.",
                    "statusCode": 200,
                    "method": "GET",
                    "endpoint": "/v1/list/add-fields",
                    "time": 1431351037,
                    "data": {
                      "created": [
                        {
                          "fieldName": "Name",
                          "message": "success"
                        },
                        {
                          "fieldName": "Age",
                          "message": "success"
                        },
                        {
                          "fieldName": "Height",
                          "message": "success"
                        },
                        {
                          "fieldName": "Nationality",
                          "message": "success"
                        }
                      ]
                    }
                  }
                }
            </code>
        </pre>

    <h5>Example Failure Response</h5>

        <pre>
            <code class="php">
                {
                  "success": {
                    "message": "Success.",
                    "statusCode": 200,
                    "method": "GET",
                    "endpoint": "/v1/list/add-fields",
                    "time": 1431351207,
                    "data": {
                      "errors": [
                        {
                          "fieldName": "Name",
                          "message": "Field Key Already Exists"
                        },
                        {
                          "fieldName": "Age",
                          "message": "Field Key Already Exists"
                        },
                        {
                          "fieldName": "Height",
                          "message": "Field Key Already Exists"
                        },
                        {
                          "fieldName": "Nationality",
                          "message": "Field Key Already Exists"
                        }
                      ]
                    }
                  }
                }
            </code>
        </pre>

    <h5>Example Mixed Response</h5>
    <p><em>(based on a request to add two additional fields)</em></p>
        <pre>
            <code class="php">
            {
              "success": {
                "message": "Success.",
                "statusCode": 200,
                "method": "GET",
                "endpoint": "/v1/list/add-fields",
                "time": 1431351356,
                "data": {
                  "errors": [
                    {
                      "fieldName": "Height",
                      "message": "Field Key Already Exists"
                    },
                    {
                      "fieldName": "BMI",
                      "message": "Field Key Already Exists"
                    },
                    {
                      "fieldName": "Eye Colour",
                      "message": "Field Key Already Exists"
                    }
                  ],
                  "created": [
                    {
                      "fieldName": "Arm Length",
                      "message": "success"
                    },
                    {
                      "fieldName": "Hair Colour",
                      "message": "success"
                    }
                  ]
                }
              }
            }
            </code>
        </pre>
@endsection

@include('layouts.footer')