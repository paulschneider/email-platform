@extends('layouts.default')

@include('layouts.header')

@section('content')
    <h3>Lists</h3>
    <ul>
    	<li><a href="/v1/docs/list#get">Get a List</a></li>
    	<li><a href="/v1/docs/list#create">Create a New List</a></li>
    	<li><a href="/v1/docs/list#add-fields">Add Fields to Existing List</a></li>
        <li><a href="/v1/docs/list#get-fields">Retrieve Fields from an Existing List</a></li>
    </ul>

    <!-- GET A LIST -->
    <div id="get-a-list">
        <h4><a name="get">Get a List</a></h4>

        <dl>
            <dt>Description</dt>
                <dd>Retrieve the details of an existing list.</dd>
            <dt>Endpoint</dt>
                <dd>/v1/list/find/{listId}</dd>
            <dt>Method</dt>
                <dd>GET</dd>
            <dt>Required Parameters</dt>
                <dd>
                    <ul>
                        <li><strong>listId</strong> (type = string, max length= 250)</li>
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
                    $response = $apiClient->get('/v1/list/find/9ffe76564af6841272cafeeb3765759d');
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
                        "endpoint": "/v1/list/find/91098c78ae3e0e7e600b07b57a086cb1",
                        "time": 1431422838,
                        "data": {
                          "id": "91098c78ae3e0e7e600b07b57a086cb1",
                          "title": "All About Bees"
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
                    "message": "The requested content item or resource could not be found.",
                    "statusCode": 404,
                    "method": "GET",
                    "endpoint": "\/v1\/list\/find\/9ffe76564af6841272cafeeb3765759d",
                    "time": 1431424243
                  },
                  "data": {
                    "errors": "Invalid ListID"
                  }
                }
            </code>
        </pre>
    </div>

    <!-- CREATE A LIST -->
    <div id="create-a-list">
        <h4><a name="create">Create a New List</a></h4>

    	<dl>
            <dt>Description</dt>
                <dd>Create a new subscriber list to which users can be subscribed. This process will return a unique list identifier.</dd>
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
    </div>

    <!-- ADD FIELDS TO A LIST -->
    <div id="add-fields">
        <h4><a name="add-fields">Add Fields to Existing List</a></h4>

        <dl>
            <dt>Description</dt>
                <dd>Add a list of field names to a specified list. Field names are to be unique. Successfully added field names will return a 'fieldTag' attribute against which user data can be applied.</dd>
            <dt>Endpoint</dt>
                <dd>list/add-fields</dd>
            <dt>Method</dt>
                <dd>POST</dd>
            <dt>Required Parameters</dt>
                <dd>
                    <ul>
                        <li><strong>listId</strong> (type = string) - Unique identifier for the list</li>
                        <li><strong>fields</strong> (type = array) - A list of fields to add to the list. Field length maximum = 30 characters</li>
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
                          "fieldTag": "Name",
                          "message": "success"
                        },
                        {
                          "fieldName": "Age",
                          "fieldTag": "Age",
                          "message": "success"
                        },
                        {
                          "fieldName": "Height",
                          "fieldTag": "Height",
                          "message": "success"
                        },
                        {
                          "fieldName": "Nationality",
                          "fieldTag": "Nationality",
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
                      "fieldTag": "ArmLength",
                      "message": "success"
                    },
                    {
                      "fieldName": "Hair Colour",
                      "fieldTag": "HairColour",
                      "message": "success"
                    }
                  ]
                }
              }
            }
            </code>
        </pre>
    </div>

    <!-- ADD FIELDS TO A LIST -->
    <div id="get-fields">
        <h4><a name="get-fields">Retrieve Fields from an Existing List</a></h4>

        <dl>
            <dt>Description</dt>
                <dd>Retrieve a set of form fields attached to a specified list.</dd>
            <dt>Endpoint</dt>
                <dd>list/fields/{listId}</dd>
            <dt>Method</dt>
                <dd>GET</dd>
            <dt>Required Parameters</dt>
                <dd>
                    <ul>
                        <li><strong>listId</strong> (type = string) - Unique identifier for the list</li>
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
                $response = $apiClient->get('/v1/list/fields/9ffe76564af6841272cafeeb3765759d');
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
                "endpoint": "/v1/list/custom-fields/5d0f2b931098f3314d1488b871107316",
                "time": 1431440440,
                "data": [
                  {
                    "fieldName": "Surname",
                    "fieldTag": "[Surname]"
                  },
                  {
                    "fieldName": "Telephone",
                    "fieldTag": "[Telephone]"
                  },
                  {
                    "fieldName": "Date Of Birth",
                    "fieldTag": "[DateOfBirth]"
                  },
                  {
                    "fieldName": "Gender",
                    "fieldTag": "[Gender]"
                  },
                  {
                    "fieldName": "County \/ City",
                    "fieldTag": "[County\/City]"
                  },
                  {
                    "fieldName": "Weight",
                    "fieldTag": "[Weight]"
                  },
                  {
                    "fieldName": "Height",
                    "fieldTag": "[Height]"
                  },
                  {
                    "fieldName": "Your Bmi",
                    "fieldTag": "[YourBmi]"
                  },
                  {
                    "fieldName": "Are You A Smoker?",
                    "fieldTag": "[AreYouASmoker?]"
                  },
                  {
                    "fieldName": "I Give My Permission To Be Con",
                    "fieldTag": "[IGiveMyPermissionToBeCon]"
                  }
                ]
              }
            }
            </code>
        </pre>

        <h5>Example Failure Response</h5>

        <pre>
            <code class="php">
                {
                  "error": {
                    "message": "The requested content item or resource could not be found.",
                    "statusCode": 404,
                    "method": "GET",
                    "endpoint": "/v1/list/fields/5d0f2b931098f3314d1488b871107316-X",
                    "time": 1431440781
                  },
                  "data": {
                    "errors": "Invalid ListID"
                  }
                }
            </code>
        </pre>
   </div>
@endsection

@include('layouts.footer')