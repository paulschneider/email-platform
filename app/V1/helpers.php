<?php

function apiErrorResponse($response, $data = []) {
	$responder = New App\V1\Lib\ApiResponder();
	$response = config('responsecodes')[$response];

	return $responder->setStatusCode($response['code'])->respondWithError($response['message'], $data);
}

function apiSuccessResponse($response, $data) {
	$responder = New App\V1\Lib\ApiResponder();
	$response = config('responsecodes')[$response];

	return $responder->setStatusCode($response['code'])->respondWithSuccess($response['message'], $data);
}

/**
 * Generic debug function
 */
function sd($data) {
	echo "<pre>";
	print_r($data);
	echo "</pre>";

	exit;
}