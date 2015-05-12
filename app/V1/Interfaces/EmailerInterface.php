<?php Namespace App\V1\Interfaces;

Interface EmailerInterface {
	public function getBaseUrl();
	public function subscribe($listId, $userEmail, $userName, $fields);
	public function addCustomFields($listId, $fields);
}