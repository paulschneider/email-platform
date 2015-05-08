<?php

return [
	'ok' => [
		'code' => 200,
		'message' => "Success."
	],	
	'created' => [
		'code' => 201,
		'message' => "Resource successfully created."
	],	
	'accepted' => [
		'code' => 202,
		'message' => "Accepted."
	],	
	'noContent' => [
		'code' => 206,
		'message' => "Call successful however there is Nothing to return."
	],		
	'badRequest' => [
		'code' => 400,
		'message' => "Invalid request. Please refer to the documentation for the use of this endpoint."
	],	
	'unauthorised' => [
		'code' => 401,
		'message' => "Required security credentials could not be validated or were not provided."
	],	
	'forbidden' => [
		'code' => 403,
		'message' => "Use of this resource is not allowed."
	],	
	'notFound' => [
		'code' => 404,
		'message' => "The requested content item or resource could not be found."
	],
	'insufficientArguments' => [
		'code' => 412,
		'message' => "Not enough arguments."
	],
	'expectationFailed' => [
		'code' => 417,
		'message' => "Supplied arguments did not meet the expectations of the endpoint."
	],
	'unprocessable' => [
		'code' => 422,
		'message' => "The request could not be processed due to errors."
	],
	'locked' => [
		'code' => 423,
		'message' => "Resource use has been locked."
	],
	'failedDependency' => [
		'code' => 424,
		'message' => "Request failed to satisfy endpoint requirements."
	],
	'tooManyRequests' => [
		'code' => 429,
		'message' => "Request quota met or has been exceeded for this resource."
	],
	'notAcceptable' => [
		'code' => 442,
		'message' => "Invalid query parameter combination."
	],
	'serverError' => [
		'code' => 500,
		'message' => "There was an error processing the request."
	],
	'notImplemented' => [
		'code' => 501,
		'message' => "Method action not available. Please refer to the documentation for the use of this endpoint."
	]
];