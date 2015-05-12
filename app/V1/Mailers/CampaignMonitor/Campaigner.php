<?php Namespace App\V1\Mailers\CampaignMonitor;

Class Campaigner {

	/**
	 * error message as returned by the API call
	 * @var $error
	 */
	protected $error;

	public function getError() {
		return $this->error;
	}
}