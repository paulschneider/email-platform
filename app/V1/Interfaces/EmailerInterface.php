<?php Namespace App\V1\Interfaces;

Interface EmailerInterface {
	public function getBaseUrl();
	public function subscribe($listId, $data);
	public function addFieldsToList($listId, $fields);	
}