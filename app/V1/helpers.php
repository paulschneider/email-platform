<?php

function getClientIdentifier() {
	return Request::header('ClientId') ? Request::header('ClientId') : getenv('CM_CLIENT_ID');
}

function stripbrackets($data) {
	$str = str_replace("[", "", $data);
	return str_replace("]", "", $str);
}

function isProtected($listId) {
	return in_array($listId, config('protectedlists')) ? true : false;
}

function getDateTime() {
	return date('Y-m:d H:i:s');
}

function isApiResponse($data) {
	return $data instanceOf Illuminate\Http\JsonResponse ? true : false;
}

function apiErrorResponse($response, $data = []) {
	$responder = New App\V1\Lib\ApiResponder();
	$response = config('responsecodes')[$response];

	return $responder->setStatusCode($response['code'])->respondWithError($response['message'], $data);
}

function apiSuccessResponse($response, $data = []) {
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